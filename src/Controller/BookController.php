<?php declare(strict_types=1);

namespace App\Controller;

use App\Builder\AuthorBuilder;
use App\Builder\BookEntityBuilder;
use App\Dto\AuthorSelectDTO;
use App\Entity\Author;
use App\Enum\AppStrings;
use App\Enum\Path;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Utils\Logger;
use App\Utils\SanitizerImpls\BookSanitizer;
use App\Utils\ValidatorImpls\BookValidator;
use JetBrains\PhpStorm\NoReturn;
use PDO;
use PH7\JustHttp\StatusCode;

final class BookController extends BaseController
{
    private BookRepository $repository;
    private AuthorBuilder $authorBuilder;
    private AuthorRepository $authorRepository;
    private BookEntityBuilder $builder;
    private BookSanitizer $sanitizer;
    private BookValidator $validator;

    public function __construct(PDO $connection)
    {
        $this->repository = new BookRepository($connection);
        $this->authorBuilder = new AuthorBuilder();
        $this->builder = new BookEntityBuilder();
        $this->authorRepository = new AuthorRepository($connection);
        $this->sanitizer = new BookSanitizer();
        $this->validator = new BookValidator();
    }

    public function index(): void
    {
        $books = $this->repository->getAllWithAuthors();

        $bookDTOs = $this->builder->buildDTOs($books);

        $this->render(Path::BOOKS_LIST->value, [
            'books' => $bookDTOs,
        ]);
    }

    public function show(int $id): void
    {
        $this->requireLogin();

        $book = $this->repository->findOneByIdWithAuthor($id);

        if (null === $book) {
            $this->abort();
        }

        $bookDto = $this->builder->buildDTO($book) ;

        $this->render(Path::SHOW_BOOK->value, [
            'book' => $bookDto,
        ]);
    }

    public function showAddBookForm(): void
    {
        $this->requireLogin();

        $authors = $this->authorRepository->getForDropdown();

        $authorDTOs = array_map(function (Author $author): AuthorSelectDTO {
            return $this->authorBuilder->buildSelectDTO($author);
        }, $authors);

        $errors = $_SESSION['errors'] ?? [];
        $old = $_SESSION['old'] ?? [];

        unset($_SESSION['errors'], $_SESSION['old']);

        $this->render(Path::ADD_BOOK->value, [
            'authors' => $authorDTOs,
            'errors' => $errors,
            'old' => $old,
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();

        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $sanitized = $this->sanitizer->sanitize($_POST);

            $errors = $this->validator->validate($sanitized);

            if([] !== $errors) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $sanitized;
                $this->redirect('/books/add');
            }

            $bookDto = $this->builder->buildDTOFromRequest($sanitized, $_SESSION['user_id']);
            $this->repository->createFromDTO($bookDto);
            $this->redirect('/books');
        }
    }

    public function showEditBookForm(int $id): void
    {
        $this->requireLogin();

        $book = $this->repository->findOneByIdWithAuthor($id);

        if (null === $book) {
            $this->abort();
        }

        if ('admin' !== $_SESSION['role'] && $book->getAddedByUserId() !== $_SESSION['user_id']) {
            Logger::getLogger()->warning(AppStrings::NOT_AUTHORISED_EDIT->value, [
                'book_id' => $book['id'],
                'user_id' => $_SESSION['user_id'] ?? null,
            ]);

            $this->abort(StatusCode::FORBIDDEN);
        }

        $authors = $this->authorRepository->getForDropdown();

        $authorDTOs = array_map(function (Author $author): AuthorSelectDTO {
            return $this->authorBuilder->buildSelectDTO($author);
        }, $authors);

        $this->render(Path::EDIT_BOOK->value, [
            'book' => $this->builder->buildDTO($book),
            'authors' => $authorDTOs,
        ]);
    }

    #[NoReturn] public function update(int $id): void
    {
        $this->requireLogin();

        $book = $this->repository->findOneByIdWithAuthor($id);

        if (null === $book) {
            $this->abort();
        }

        $bookDto = $this->builder->buildDTOFromRequest($_POST, $_SESSION['user_id']);
        $this->repository->updateFromDTO($bookDto);

        $this->redirect('/books/' . $id);
    }

    #[NoReturn] public function delete(int $id): void
    {
        $this->requireLogin();

        if ('admin' !== $_SESSION['role']) {
            Logger::getLogger()->warning(AppStrings::NOT_AUTHORISED_DELETE->value, [
                'book_id' => $id,
                'user_id' => $_SESSION['user_id'] ?? null,
                'role' => $_SESSION['role'] ?? null,
            ]);

            $this->abort(StatusCode::FORBIDDEN);
        }

        $book = $this->repository->findOneById($id);

        if (null === $book) {
            $this->abort();
        }

        $this->repository->delete($id);

        Logger::getLogger()->info(AppStrings::BOOK_DELETE->value, [
            'book_id' => $id,
            'user_id' => $_SESSION['user_id'],
        ]);

        $this->redirect('/books');
    }
}
