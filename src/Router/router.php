<?php declare(strict_types=1);

use App\Utils\UrlTool;

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
    '/' => '/../Controller/books.php',
    '/books' => '/../Controller/books.php',
];

function route($uri, $routes)
{
    if (array_key_exists($uri, $routes)) {
        require __DIR__ . $routes[$uri];
    } else {
        UrlTool::abort();
    }
}

route($uri, $routes);
