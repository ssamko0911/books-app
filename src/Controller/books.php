<?php declare(strict_types=1);

$heading = 'Available Books';

$db = \App\Database\Database::getInstance();

$statement = $db->query("SELECT * FROM `book_recommendations_books`");
$books = $statement->fetchAll();

//\App\Utils\Debug::dd($books);

ob_start();
require __DIR__ . '/../../views/books/index.view.php';
$slot = ob_get_clean();

require __DIR__ . '/../../views/template.view.php';
