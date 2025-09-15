<?php declare(strict_types=1);

use App\Controller\AuthController;
use App\Controller\BookController;
use App\Controller\RecommendationsController;
use App\Database\Database;
use App\Utils\UrlTool;

//TODO: Refactor as a class
$dbConnection = Database::getInstance();
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
    '/' => [RecommendationsController::class, 'index'],
    '/recommendations' => [RecommendationsController::class, 'index'],
    '/recommendations/{id}' => [RecommendationsController::class, 'show'],
    '/authors/add' => [AuthController::class, 'showAddAuthorForm'],
    '/authors/store' => [AuthController::class, 'store'],
    '/books' => [BookController::class, 'index'],
    '/books/{id}' => [BookController::class, 'show'],
    '/books/{id}/edit' => [BookController::class, 'showEditBookForm'],
    '/books/{id}/update' => [BookController::class, 'update'],
    '/books/{id}/delete' => [BookController::class, 'delete'],
    '/books/add' => [BookController::class, 'showAddBookForm'],
    '/books/store' => [BookController::class, 'store'],
    '/login' =>  [AuthController::class, 'login'],
    '/login-form' =>  [AuthController::class, 'showLoginForm'],
    '/logout' =>  [AuthController::class, 'logout'],
    '/register' =>  [AuthController::class, 'register'],
    '/register-form' =>  [AuthController::class, 'showRegisterForm'],
];

function route($uri, $routes, $dbConnection): void
{
    $routeFound = false;
    foreach ($routes as $pattern => [$controllerClass, $method]) {
        $regex = preg_replace('#\{[a-zA-Z_]+\}#', '([0-9]+)', $pattern);
        $regex = "#^$regex$#";

        if (preg_match($regex, $uri, $matches)) {
            array_shift($matches);

            $controller = new $controllerClass($dbConnection);
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $method === 'login') {
                $controller->$method();
            } else {
                $matches = array_map(static fn($v) => (int)$v, $matches);
                $controller->$method(...$matches);
            }

            $routeFound = true;
            break;
        }
    }

    if (!$routeFound) {
        UrlTool::abort();
    }
}

route($uri, $routes, $dbConnection);
