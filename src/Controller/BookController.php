<?php declare(strict_types=1);

namespace App\Controller;

use App\Builder\AuthorBuilder;
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

    public function __construct(PDO $connection)
    {
        $this->bookRepository = new BookRepository($connection);
        $this->authorBuilder = new AuthorBuilder();
        $this->authorRepository = new AuthorRepository($connection);
    }

    public function index(): void
    {
        $books = $this->bookRepository->getAllWithAuthors();

        $this->render(Path::BOOKS_LIST->value, [
            'books' => $books,
        ]);
    }

    public function show(int $id): void
    {
        $this->requireLogin();

        $book = $this->bookRepository->findOneByIdWithAuthor($id);

        if (null === $book) {
            $this->abort();
        }

        $this->render(Path::SHOW_BOOK->value, [
            'book' => $book,
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
            $title = $_POST['title'] ?? '';
            $author_id = $_POST['author'] ?? '';
            $description = $_POST['description'] ?? '';
            $year = $_POST['published_year'] ?? null;
            $user_id = (int)$_SESSION['user_id'];

            $data = [
                'title' => $title,
                'author_id' => $author_id,
                'description' => $description,
                'published_year' => $year ? (int)$year : null,
                'added_by_user' => $user_id,
            ];

            if ($title && $author_id) {
                $this->bookRepository->create($data);
                $this->redirect('/books');
            }
        }
    }

    public function showEditBookForm(int $id): void
    {
        $this->requireLogin();

        $book = $this->bookRepository->findOneById($id);

        if (null === $book) {
            $this->abort();
        }

        if ('admin' !== $_SESSION['role'] && $book['added_by_user'] !== $_SESSION['user_id']) {
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
            'book' => $book,
            'authors' => $authorDTOs,
        ]);
    }

    #[NoReturn] public function update(int $id): void
    {
        $this->requireLogin();

        $book = $this->bookRepository->findOneById($id);

        if (null === $book) {
            $this->abort();
        }

        $title = $_POST['title'] ?? '';
        $author_id = $_POST['author'] ?? '';
        $description = $_POST['description'] ?? '';
        $year = $_POST['published_year'] ?? null;
        $added_by_user = $book['added_by_user'];

        $data = [
            'title' => $title,
            'author_id' => $author_id,
            'description' => $description,
            'published_year' => $year ? (int)$year : null,
            'added_by_user' => $added_by_user,
        ];

        $this->bookRepository->update($id, $data);

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
