<div class="col-md-4">
    <div class="card h-100 shadow-sm">
<!--        <img src="--><?php //= htmlspecialchars($book['cover_url']) ?><!--" class="card-img-top" alt="cover">-->
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
            <p class="card-text">
                <strong>Author:</strong> <?= htmlspecialchars($book['author_id']) ?><br>
                <span class="badge bg-primary"><?= htmlspecialchars($book['created_at']) ?></span>
            </p>
            <div class="d-flex gap-2">
            <a href="books/<?= urlencode($book['id']) ?>" class="btn btn-outline-primary">
                View Details
            </a>
            <?php if (isset($_SESSION['user_id']) && 'admin' === $_SESSION['role']): ?>
                <a href="books/<?= urlencode($book['id']) ?>/edit" class="btn btn-outline-primary">
                    Edit
                </a>
                <form action="/books/<?= urlencode($book['id']) ?>/delete" method="post" onsubmit="return confirm('Are you sure you want to delete this book?');">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>
