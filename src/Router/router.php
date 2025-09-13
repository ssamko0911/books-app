<?php declare(strict_types=1);

use App\Controller\BookController;
use App\Utils\UrlTool;

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
    '/' => [BookController::class, 'index'],
    '/books' => [BookController::class, 'index'],
    '/books/{id}' => [BookController::class, 'show'],
];

function route($uri, $routes)
{
    $routeFound = false;
    foreach ($routes as $pattern => [$controllerClass, $method]) {
        $regex = preg_replace('#\{[a-zA-Z_]+\}#', '([0-9]+)', $pattern);
        $regex = "#^$regex$#";

        if (preg_match($regex, $uri, $matches)) {
            array_shift($matches);

            $controller = new $controllerClass();
            $matches = array_map(static fn($v) => (int)$v, $matches);
            $controller->$method(...$matches);
            $routeFound = true;
            break;
        }
    }

    if (!$routeFound) {
        UrlTool::abort();
    }
}

route($uri, $routes);
