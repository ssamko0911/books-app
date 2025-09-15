<?php declare(strict_types=1); ?>

<!-- Book List -->
<div class="row g-3">
    <?php foreach ($books as $book): ?>
        <?php require __DIR__ . '/components/card.php'; ?>
    <?php endforeach; ?>
</div>
