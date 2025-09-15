<?php declare(strict_types=1); ?>

<!-- Book List -->
<div class="row g-3">
    <?php foreach ($recommendations as $rec): ?>
        <div class="col-md-4">
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <p class="card-text"><?= htmlspecialchars($rec['title']) ?></p>
                    <p class="text-muted mb-0">
                        — <?= htmlspecialchars($rec['text']) ?>
                    </p>
                    <p class="text-muted mb-0">
                        Recommended by Dummy User
                    </p>
                    <a href="/recommendations/<?= $rec['id'] ?>" class="btn btn-sm btn-primary mt-2">Read More</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!--htmlspecialchars($rec['username'])-->