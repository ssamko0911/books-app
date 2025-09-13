<?php declare(strict_types=1);

$heading = 'Available Books';

ob_start();
require __DIR__ . '/../../views/books/index.view.php';
$slot = ob_get_clean();

require __DIR__ . '/../../views/template.view.php';
