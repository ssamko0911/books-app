<?php declare(strict_types=1); ?>

<div class="container mt-4">
    <h2>Edit Book</h2>

    <form action="/books/<?= $book->id ?>/update" method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Book Title</label>
            <input type="text"
                   name="title"
                   id="title"
                   class="form-control"
                   value="<?= htmlspecialchars($book->title) ?>"
                   required>
            <input type="hidden"
                   name="book_id"
                   id="book_id"
                   value="<?= htmlspecialchars((string)($book->id ?? '')) ?>">
        </div>

        <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <select name="author" id="author" class="form-select" required>
                <option value="" disabled>Select an author</option>
                <?php foreach ($authors as $author): ?>
                    <option value="<?= htmlspecialchars((string)$author->id) ?>"
                        <?= $author->id === $book->author->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($author->fullName) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="author_name" id="author_name" value="<?= htmlspecialchars($book->author->fullName ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description"
                      id="description"
                      class="form-control"
                      rows="5"><?= htmlspecialchars($book->description) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="published_year" class="form-label">Published Year</label>
            <input type="number"
                   name="published_year"
                   id="published_year"
                   class="form-control"
                   value="<?= htmlspecialchars((string)$book->publishedYear) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Update Book</button>
        <a href="/books/<?= $book->id ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>