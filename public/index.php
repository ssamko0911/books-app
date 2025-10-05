<?php declare(strict_types=1);

use App\Utils\UrlTool;
use PH7\JustHttp\StatusCode;
ob_start();
session_start();

require __DIR__ . '/../vendor/autoload.php';

try {
    require __DIR__ . '/../src/Router/routes.php';
} catch (RuntimeException $e) {
    // Todo: Add logging
    UrlTool::abort(StatusCode::INTERNAL_SERVER_ERROR);
}

ob_end_flush();
