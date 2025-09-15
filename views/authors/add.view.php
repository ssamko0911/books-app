<?php declare(strict_types=1); ?>

<div class="container mt-4">
    <h2>Add Author</h2>

    <form action="/authors/store" method="post">
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" name="first_name" id="first_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" name="last_name" id="last_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="bio" class="form-label">Biography</label>
            <textarea name="bio" id="bio" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add Author</button>
    </form>
</div>

