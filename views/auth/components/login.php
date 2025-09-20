<?php declare(strict_types=1); ?>

<form action="/login" method="POST">
    <label>
        <input type="email" name="email" placeholder="Email" required>
    </label>
    <label>
        <input type="password" name="password" placeholder="Password" required>
    </label>
    <button type="submit">Login</button>
</form>

<?php

if (isset($error)) {
    echo $error;
}

?>
