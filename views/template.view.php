<?php declare(strict_types=1); ?>

    <!-- TPL start -->
<?php require __DIR__ . '/main/head.php'; ?>

    <!-- Navbar -->
<?php require __DIR__ . '/main/nav.php'; ?>

<div class="container">
    <?php if (isset($heading)): ?>
        <h1 class="mb-4">
            <?= $heading ?>
        </h1>
    <?php endif; ?>

    <?= $slot ?? '' ?>
</div>

<!-- Footer -->
<?php require __DIR__ . '/main/footer.php'; ?>
