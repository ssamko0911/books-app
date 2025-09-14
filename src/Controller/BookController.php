<?php declare(strict_types=1);

namespace App\Controller;

use App\Enum\Path;
use App\Repository\BookRepository;
use App\Utils\Logger;
use App\Utils\UrlTool;
use JetBrains\PhpStorm\NoReturn;
use PDO;
use PH7\JustHttp\StatusCode;

final class BookController extends BaseController
{
    private BookRepository $bookRepository;

    public function __construct(PDO $connection)
    {
        $this->bookRepository = new BookRepository($connection);
    }

    public function index(): void
    {
        $books = $this->bookRepository->getAll();

        $this->render(Path::BOOKS_LIST->value, [
            'books' => $books,
        ]);
    }

    public function show(int $id): void
    {
        $this->requireLogin();

        $book = $this->bookRepository->findOneById($id);

        if (null === $book) {
            UrlTool::abort();
        }

        $this->render(Path::SHOW_BOOK->value, [
            'book' => $book,
        ]);
    }

    public function showAddBookForm(): void
    {
        $this->requireLogin();
        $this->render(Path::ADD_BOOK->value);
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

            if ($title && $author_id) {
                $this->bookRepository->create(
                    $title,
                    (int)$author_id,
                    $user_id,
                    $description,
                    $year ? (int)$year : null,
                );

                $this->redirect('/books');
            }
        }
    }

    public function showEditBookForm(int $id): void
    {
        $this->requireLogin();

        $book = $this->bookRepository->findOneById($id);

        if (null === $book) {
            UrlTool::abort();
        }

        if ('admin' !== $_SESSION['role'] && $book['added_by_user'] !== $_SESSION['user_id']) {
            Logger::getLogger()->warning('Unauthorized book edit attempt', [
                'book_id' => $id,
                'user_id' => $_SESSION['user_id'] ?? null,
            ]);

            UrlTool::abort(StatusCode::FORBIDDEN);
        }

        UrlTool::view(Path::EDIT_BOOK->value, [
            'book' => $book,
        ]);
    }

    #[NoReturn] public function update(int $id): void
    {
        $this->requireLogin();

        $book = $this->bookRepository->findOneById($id);

        if (null === $book) {
            UrlTool::abort();
        }

        $title = $_POST['title'] ?? '';
        $author_id = $_POST['author'] ?? '';
        $description = $_POST['description'] ?? '';
        $year = $_POST['published_year'] ?? null;
        $user_id = $_SESSION['user_id'];

        $this->bookRepository->update($id, [
            'title' => $title,
            'author_id' => $author_id,
            'description' => $description,
            'published_year' => $year,
            'added_by_user' => $user_id,
        ]);

        $this->redirect('/books/' . $id);
    }

    #[NoReturn] public function delete(int $id): void
    {
        $this->requireLogin();

        if ('admin' !== $_SESSION['role']) {
            Logger::getLogger()->warning('Unauthorized book delete attempt', [
                'book_id' => $id,
                'user_id' => $_SESSION['user_id'] ?? null,
                'role' => $_SESSION['role'] ?? null,
            ]);

            UrlTool::abort(StatusCode::FORBIDDEN);
        }

        $book = $this->bookRepository->findOneById($id);

        if (null === $book) {
            UrlTool::abort();
        }

        $this->bookRepository->delete($id);

        Logger::getLogger()->info('Book deleted', [
            'book_id' => $id,
            'user_id' => $_SESSION['user_id'],
        ]);

        $this->redirect('/books');
    }
}
