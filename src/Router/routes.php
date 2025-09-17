<?php declare(strict_types=1);

use App\Controller\AuthController;
use App\Controller\AuthorController;
use App\Controller\BookController;
use App\Controller\RecommendationsController;
use App\Database\Database;
use App\Router\Router;

$dbConnection = Database::getInstance();
$router = new Router($dbConnection);

$router
    ->get('/', [RecommendationsController::class, 'index'])
    ->get('/recommendations', [RecommendationsController::class, 'index'])
    ->get('/recommendations/{id}', [RecommendationsController::class, 'show'])

    ->get('/authors/add', [AuthorController::class, 'showAddAuthorForm'])
    ->post('/authors/store', [AuthorController::class, 'store'])

    ->get('/books', [BookController::class, 'index'])
    ->get('/books/{id}', [BookController::class, 'show'])
    ->get('/books/{id}/edit', [BookController::class, 'showEditBookForm'])
    ->post('/books/{id}/update', [BookController::class, 'update']) // PUT/UPD method?
    ->post('/books/{id}/delete', [BookController::class, 'delete']) // DELETE Method?
    ->get('/books/add', [BookController::class, 'showAddBookForm'])
    ->post('/books/store', [BookController::class, 'store'])

    ->get('/login-form', [AuthController::class, 'showLoginForm'])
    ->post('/login', [AuthController::class, 'login'])
    ->post('/logout', [AuthController::class, 'logout'])
    ->get('/register-form', [AuthController::class, 'showRegisterForm'])
    ->post('/register', [AuthController::class, 'register']);

$router->launchRouter();
