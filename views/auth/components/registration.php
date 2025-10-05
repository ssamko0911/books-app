<?php declare(strict_types=1); ?>

<form action="/register" method="POST">
    <label>
        <input type="text" name="username" placeholder="Username" required>
    </label>
    <label>
        <input type="email" name="email" placeholder="Email" required>
    </label>
    <label>
        <input type="password" name="password" placeholder="Password" required>
    </label>
    <label>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
    </label>
    <button type="submit">Sign Up</button>
</form>

<?php if (isset($error)) echo "<p>$error</p>"; ?>
