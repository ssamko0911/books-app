<?php declare(strict_types=1); ?>

<div class="container mt-4">
    <h2>Add a Book</h2>

    <form action="/books/store" method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Book Title</label>
            <input type="text"
                   name="title"
                   id="title"
                   class="form-control form-control <?= !empty($errors['title']) ? 'is-invalid' : '' ?>"
                   value="<?= htmlspecialchars($old['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   required>
            <?php if (!empty($errors['title'])): ?>
                <div class="invalid-feedback">
                    <?= htmlspecialchars($errors['title']) ?>
                </div>
            <?php endif; ?>
        </div>



        <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <select name="author" id="author" class="form-select" required>
                <option value="" disabled selected>Select an author</option>
                <?php foreach ($authors as $author): ?>
                    <option value="<?= htmlspecialchars((string)$author->id) ?>">
                        <?= htmlspecialchars($author->fullName) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden"
                   name="author_name"
                   id="author_name"
                   value="<?= htmlspecialchars($book->author->fullName ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="published_year" class="form-label">Published Year</label>
            <input type="number" name="published_year" id="published_year" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Add Book</button>
    </form>
</div>

