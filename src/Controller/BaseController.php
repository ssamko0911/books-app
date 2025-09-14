<?php

namespace App\Controller;

use App\Enum\Path;
use App\Utils\UrlTool;
use JetBrains\PhpStorm\NoReturn;

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

    protected function requireLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            UrlTool::view(Path::LOGIN->value);
            exit();
        }
    }
}