<?php declare(strict_types=1); ?>

<div class="card">
    <div class="card-body">
        <!-- Book title -->
        <h3><?= htmlspecialchars($recommendation['title']) ?></h3>
        <h6 class="text-muted">by <?= htmlspecialchars((string)$recommendation['book_id']) ?></h6>

        <!-- Recommendation full text -->
        <p class="mt-3"><?= nl2br(htmlspecialchars($recommendation['text'])) ?></p>

        <!-- User -->
        <p class="text-muted">Recommended by <?= htmlspecialchars((string)$recommendation['user_id']) ?></p>

        <!-- Book description if available -->
        <?php if (!empty($recommendation['text'])): ?>
            <hr>
            <p><strong>About the book:</strong></p>
            <p><?= nl2br(htmlspecialchars($recommendation['text'])) ?></p>
        <?php endif; ?>

        <!-- Back link -->
        <a href="/" class="btn btn-outline-secondary mt-3">Back to Recommendations</a>
    </div>
</div>

<!--htmlspecialchars($rec['username'])-->