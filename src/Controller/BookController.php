<?php declare(strict_types=1);

namespace App\Controller;
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
}
