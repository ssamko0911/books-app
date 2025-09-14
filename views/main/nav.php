<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="/">📚 BookRec</a>

        <!-- Main links -->
        <div class="collapse navbar-collapse justify-content-between">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/books">Books</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-2" href="/authors/add"> Add Author</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-2" href="/books/add"> Add Book</a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Login / Sign Up -->
            <div class="d-flex">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="btn btn-outline-light ms-2">Welcome, <?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role'] ?>)</span>
                    <a href="/logout" class="btn btn-outline-light ms-2">Logout</a>
                <?php else: ?>
                    <a href="/login-form" class="btn btn-outline-light me-2">Login</a>
                    <a href="/register-form" class="btn btn-warning">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
