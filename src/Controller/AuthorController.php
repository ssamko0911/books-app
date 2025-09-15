<?php declare(strict_types=1);

namespace App\Controller;

use App\Builder\AuthorBuilder;
use App\Dto\AuthorCreateDTO;
use App\Enum\Path;
use App\Repository\AuthorRepository;
use PDO;

final class AuthorController extends BaseController
{
    private AuthorRepository $authorRepository;
    private AuthorBuilder $authorBuilder;

    public function __construct(PDO $connection)
    {
        $this->authorRepository = new AuthorRepository($connection);
        $this->authorBuilder = new AuthorBuilder();
    }

    public function showAddAuthorForm(): void
    {
        $this->requireLogin();
        $this->render(Path::ADD_AUTHOR->value);
    }

    public function store(): void
    {
        $this->requireLogin();

        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $authorDto = new AuthorCreateDTO();
            $authorDto->firstName = $_POST['first_name'];
            $authorDto->lastName = $_POST['last_name'];
            $authorDto->biography = $_POST['bio'];

            $this->authorRepository->createFromDto($authorDto);
            $this->redirect('/books/add');
        }
    }
}