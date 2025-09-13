<?php declare(strict_types=1);

use App\Controller\AuthController;

$auth = new AuthController();
$error = '';

if ('POST' === $_SERVER['REQUEST_METHOD']) {
    $result = $auth->login($_POST['email'], $_POST['password']);

    if (true === $result) {
        header('Location: /');
        exit();
    }

    $error = $result;
}

?>

<form method="POST">
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
