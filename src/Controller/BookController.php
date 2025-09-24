<?php declare(strict_types=1);

namespace App\Controller;

use App\Enum\AppStrings;
use App\Enum\Path;
use App\Service\BookService;
use App\Utils\Logger;
use App\Utils\SanitizerImpls\BookSanitizer;
use App\Utils\ValidatorImpls\BookValidator;
use JetBrains\PhpStorm\NoReturn;
use PH7\JustHttp\StatusCode;

final class BookController extends BaseController
{
    public function __construct(
        private readonly BookService $service,
    )
    {
    }

    public function index(): void
    {
        $this->render(Path::BOOKS_LIST->value, [
            'books' => $this->service->getAllBooksWithAuthors(),
        ]);
    }

    public function show(int $id): void
    {
        $this->requireLogin();

        $book = $this->service->getBookWithAuthor($id);

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

        $errors = $_SESSION['errors'] ?? [];
        $old = $_SESSION['old'] ?? [];

        unset($_SESSION['errors'], $_SESSION['old']);

        $this->render(Path::ADD_BOOK->value, [
            'authors' => $this->service->getAuthorChoices(),
            'errors' => $errors,
            'old' => $old,
        ]);
    }

    #[NoReturn] public function store(): void
    {
        $this->requireLogin();

        if ('POST' !== $_SERVER['REQUEST_METHOD']) {
            $this->redirect('/books/add');
        }

        // TODO: refactor -> inject into BookService
        $sanitized = BookSanitizer::sanitize($_POST);
        $errors = BookValidator::validate($sanitized);

        if ([] !== $errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $sanitized;
            $this->redirect('/books/add');
        }

        $this->service->createBook($sanitized);
        $this->redirect('/books');
    }

    public function showEditBookForm(int $id): void
    {
        $this->requireLogin();

        $book = $this->service->getBookWithAuthor($id);

        if (null === $book) {
            $this->abort();
        }

        if ('admin' !== $_SESSION['role'] && $book->addedByUserId !== $_SESSION['user_id']) {
            Logger::getLogger()->warning(AppStrings::NOT_AUTHORISED_EDIT->value, [
                'book_id' => $book->id,
                'user_id' => $_SESSION['user_id'] ?? null,
            ]);

            $this->abort(StatusCode::FORBIDDEN);
        }

        $this->render(Path::EDIT_BOOK->value, [
            'book' => $book,
            'authors' => $this->service->getAuthorChoices(),
        ]);
    }

    #[NoReturn] public function update(int $id): void
    {
        $this->requireLogin();

        $book = $this->service->getBookWithAuthor($id);

        if (null === $book) {
            $this->abort();
        }

        $this->service->updateBook($_POST);

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

        if (!$this->service->deleteBook($id)) {
            $this->abort();
        }

        Logger::getLogger()->info(AppStrings::BOOK_DELETE->value, [
            'book_id' => $id,
            'user_id' => $_SESSION['user_id'],
        ]);

        $this->redirect('/books');
    }
}
