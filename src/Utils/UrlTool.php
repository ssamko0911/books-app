<?php declare(strict_types=1);

namespace App\Utils;

use App\Enum\Path;
use JetBrains\PhpStorm\NoReturn;
use PH7\JustHttp\StatusCode;

class UrlTool
{
    public static function urlIs(string $url): bool
    {
        return $_SERVER['REQUEST_URI'] === $url;
    }

    #[NoReturn]
    public static function abort(int $code = StatusCode::NOT_FOUND): void
    {
        http_response_code($code);

        ob_start();
        require __DIR__ . "/../../views/$code.php";
        $slot = ob_get_clean();

        require __DIR__ . Path::MAIN_TEMPLATE->value;
        die();
    }

    public static function view(string $templatePath, array $data = []): void
    {
        extract($data);

        ob_start();
        require __DIR__ . $templatePath;
        $slot = ob_get_clean();

        require __DIR__ . Path::MAIN_TEMPLATE->value;
    }
}
