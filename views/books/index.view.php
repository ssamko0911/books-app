<?php declare(strict_types=1); ?>

<!-- Book List -->
<div class="row g-3">
    <?php
    // Dummy data for tests;
    $books = [
        ["title" => "1984", "author" => "George Orwell", "genre" => "Dystopian", "cover_url" => "uploads/1984.jpg"],
        ["title" => "The Hobbit", "author" => "J.R.R. Tolkien", "genre" => "Fantasy", "cover_url" => "uploads/hobbit.jpg"]
    ];
    foreach ($books as $book): ?>
        <?php require __DIR__ . '/components/card.php'; ?>
    <?php endforeach; ?>
</div>
