<?php declare(strict_types=1);

namespace App\Controller;

use App\Enum\Path;
use App\Repository\BookRepository;
use App\Utils\UrlTool;

class BookController
{
    private BookRepository $bookRepository;

    public function __construct()
    {
        $this->bookRepository = new BookRepository();
    }

    public function index(): void
    {
        $books = $this->bookRepository->getAll();

        UrlTool::view('/../../views/books/index.view.php', [
            'books' => $books,
        ]);
    }

    public function show(int $id): void
    {
        $book = $this->bookRepository->findOneById($id);

        if (null === $book) {
            UrlTool::abort();
        }

        UrlTool::view('/../../views/books/show.view.php', [
            'book' => $book,
        ]);
    }

    public function showAddBookForm(): void
    {
        if (!isset($_SESSION['user_id'])) {
            UrlTool::view(Path::LOGIN->value);
            exit();
        }

        UrlTool::view(Path::ADD_BOOK->value);
    }

    public function store(): void
    {
        if (!isset($_SESSION['user_id'])) {
            UrlTool::view(Path::LOGIN->value);
            exit();
        }

        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $title = $_POST['title'] ?? '';
            $author_id = $_POST['author'] ?? '';
            $description = $_POST['description'] ?? '';
            $year = $_POST['published_year'] ?? null;

            if ($title && $author_id) {
                $this->bookRepository->create($title, $author_id, $description, $year ? (int)$year : null);
                UrlTool::view(Path::BOOKS_LIST->value, [
                        'books' => $this->bookRepository->getAll(),
                    ]
                );
                exit;
            }
        }
    }
}
