<?php declare(strict_types=1); ?>

    <!-- TPL start -->
<?php require __DIR__ . '/main/head.php'; ?>

    <!-- Navbar -->
<?php require __DIR__ . '/main/nav.php'; ?>

<div class="container">
    <h1 class="mb-4">
        <?= $heading ?? 'Sorry, page not found.' ?>
    </h1>
    <?= $slot ?? '' ?>
</div>

<!-- Footer -->
<?php require __DIR__ . '/main/footer.php'; ?>
