<?php declare(strict_types=1);

namespace App\Controller;

use App\Builder\AuthorBuilder;
use App\Builder\BookBuilder;
use App\Dto\AuthorSelectDTO;
use App\Entity\Author;
use App\Enum\AppStrings;
use App\Enum\Path;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Utils\Logger;
use JetBrains\PhpStorm\NoReturn;
use PDO;
use PH7\JustHttp\StatusCode;

final class BookController extends BaseController
{
    private BookRepository $bookRepository;
    private AuthorBuilder $authorBuilder;
    private AuthorRepository $authorRepository;
    private BookBuilder $bookBuilder;

    public function __construct(PDO $connection)
    {
        $this->bookRepository = new BookRepository($connection);
        $this->authorBuilder = new AuthorBuilder();
        $this->bookBuilder = new BookBuilder();
        $this->authorRepository = new AuthorRepository($connection);
    }

    public function index(): void
    {
        $books = $this->bookRepository->getAllWithAuthors();

        $bookDtos = [];
        foreach ($books as $book) {
            $bookDtos[] = $this->bookBuilder->buildBookDTOFromEntity($book);
        }

        $this->render(Path::BOOKS_LIST->value, [
            'books' => $bookDtos,
        ]);
    }

    public function show(int $id): void
    {
        $this->requireLogin();

        $book = $this->bookRepository->findOneByIdWithAuthor($id);

        if (null === $book) {
            $this->abort();
        }

        $bookDto = $this->bookBuilder->buildBookDTOFromEntity($book) ;

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

        $this->render(Path::ADD_BOOK->value, [
            'authors' => $authorDTOs,
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();

        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $bookDto = $this->bookBuilder->buildBookDTO($_POST);
            $this->bookRepository->createFromDTO($bookDto);
            $this->redirect('/books');
        }
    }

    public function showEditBookForm(int $id): void
    {
        $this->requireLogin();

        $book = $this->bookRepository->findOneByIdWithAuthor($id);

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
            'book' => $this->bookBuilder->buildBookDTOFromEntity($book),
            'authors' => $authorDTOs,
        ]);
    }

    #[NoReturn] public function update(int $id): void
    {
        $this->requireLogin();

        $book = $this->bookRepository->findOneByIdWithAuthor($id);

        if (null === $book) {
            $this->abort();
        }

        $bookDto = $this->bookBuilder->buildBookDTO($_POST);
        $this->bookRepository->updateFromDTO($bookDto);

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

        $book = $this->bookRepository->findOneById($id);

        if (null === $book) {
            $this->abort();
        }

        $this->bookRepository->delete($id);

        Logger::getLogger()->info(AppStrings::BOOK_DELETE->value, [
            'book_id' => $id,
            'user_id' => $_SESSION['user_id'],
        ]);

        $this->redirect('/books');
    }
}
