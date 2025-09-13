<?php

namespace App\Utils;

class UrlTool
{
    public static function urlIs(string $url): bool
    {
        return $_SERVER['REQUEST_URI'] === $url;
    }

    public static function abort(): void
    {
        http_response_code(404);
        require __DIR__ . '/../../views/template.view.php';
        die();
    }
}