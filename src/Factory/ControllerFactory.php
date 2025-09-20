<?php declare(strict_types=1);

namespace App\Factory;

use App\Builder\AuthorEntityBuilder;
use App\Builder\BookEntityBuilder;
use App\Controller\AuthController;
use App\Controller\AuthorController;
use App\Controller\BookController;
use App\Controller\RecommendationsController;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\RecommendationRepository;
use App\Repository\UserRepository;
use App\Service\BookService;
use PDO;
use RuntimeException;

final readonly class ControllerFactory
{
    public function __construct(
        private PDO $connection
    )
    {
    }

    public function make(string $controllerClass): object
    {
        return match ($controllerClass) {
            AuthController::class => $this->makeAuthController(),
            BookController::class => $this->makeBookController(),
            AuthorController::class => $this->makeAuthorController(),
            RecommendationsController::class => $this->makeRecommendationsController(),
            // TODO: Logging
            default => throw new RuntimeException('Unexpected Controller class: ' . $controllerClass),
        };
    }

    private function makeBookController(): BookController
    {
        $authorBuilder = new AuthorEntityBuilder();
        $bookBuilder = new BookEntityBuilder($authorBuilder);
        $bookService = new BookService(
            bookRepository: new BookRepository($this->connection),
            authorRepository: new AuthorRepository($this->connection),
            bookBuilder: $bookBuilder,
            authorBuilder: $authorBuilder
        );

        return new BookController(
            service: $bookService,
        );
    }

    private function makeAuthorController(): AuthorController
    {
        return new AuthorController(
            new AuthorRepository($this->connection),
            new AuthorEntityBuilder(),
        );
    }

    private function makeRecommendationsController(): RecommendationsController
    {
        return new RecommendationsController(
            new RecommendationRepository($this->connection),
        );
    }

    private function makeAuthController(): AuthController
    {
        return new AuthController(
            new UserRepository($this->connection)
        );
    }
}
