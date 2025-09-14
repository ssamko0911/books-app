<?php declare(strict_types=1);

namespace App\Controller;

use App\Enum\Path;
use App\Utils\Logger;
use App\Utils\UrlTool;
use JetBrains\PhpStorm\NoReturn;
use PH7\JustHttp\StatusCode;

abstract class BaseController
{
    #[NoReturn] protected function redirect(string $url): void
    {
        header("Location: $url");
        exit();
    }

    protected function render(string $view, array $data = []): void
    {
        UrlTool::view($view, $data);
    }

    #[NoReturn] protected function abort(int $code = StatusCode::NOT_FOUND):void
    {
        UrlTool::abort($code);
    }

    protected function requireLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            UrlTool::view(Path::LOGIN->value);
            exit();
        }
    }

    protected function requireOwnerOrAdmin(array $book, string $message): void
    {
        if ('admin' !== $_SESSION['role'] && $book['added_by_user'] !== $_SESSION['user_id']) {
            Logger::getLogger()->warning($message, [
                'book_id' => $book['id'],
                'user_id' => $_SESSION['user_id'] ?? null,
            ]);

            $this->abort(StatusCode::FORBIDDEN);
        }
    }
}
