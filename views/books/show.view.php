<?php declare(strict_types=1); ?>


<h1><?= htmlspecialchars($book['title']) ?></h1>
<p><strong>Author:</strong> <?= htmlspecialchars((string)$book['author_id']) ?></p>
<p><strong>Published:</strong> <?= htmlspecialchars((string)$book['published_year']) ?></p>
<p><?= htmlspecialchars($book['description']) ?></p>


<h3 class="mt-4">Recommendations</h3>
<?php if (empty($recommendations)): ?>
    <p>No recommendations yet.</p>
<?php else: ?>
    <ul class="list-group">
        <?php foreach ($recommendations as $rec): ?>
            <li class="list-group-item">
                <strong><?= htmlspecialchars($rec['username']) ?></strong> rated:
                <span class="badge bg-success"><?= htmlspecialchars($rec['rating']) ?></span>
                <p><?= htmlspecialchars($rec['comment']) ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="/books/<?= urlencode((string)$book['id']) ?>/edit" class="btn btn-outline-primary mt-3">Edit</a>
<a href="/books" class="btn btn-secondary mt-3">Back to Books</a>

