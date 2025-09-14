<div class="col-md-4">
    <div class="card h-100 shadow-sm">
<!--        <img src="--><?php //= htmlspecialchars($book['cover_url']) ?><!--" class="card-img-top" alt="cover">-->
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
            <p class="card-text">
                <strong>Author:</strong> <?= htmlspecialchars($book['author_id']) ?><br>
                <span class="badge bg-primary"><?= htmlspecialchars($book['created_at']) ?></span>
            </p>
            <a href="books/<?= urlencode($book['id']) ?>" class="btn btn-outline-primary">
                View Details
            </a>
        </div>
    </div>
</div>
