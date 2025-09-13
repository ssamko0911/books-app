<?php declare(strict_types=1);

use App\Controller\AuthController;

$auth = new AuthController();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['password_confirmation'];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $result = $auth->register($username, $email, $password);

        if (true === $result) {
            $auth->login($email, $password);
            header('Location: /');
            exit();
        }

        $error = $result;
    }
}
?>
<form method="POST">
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

<?php if ($error) echo "<p>$error</p>"; ?>
