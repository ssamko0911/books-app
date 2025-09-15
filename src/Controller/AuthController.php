<?php declare(strict_types=1);

namespace App\Controller;

use App\Enum\AppStrings;
use App\Enum\Path;
use App\Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;
use PDO;

final class AuthController extends BaseController
{
    private UserRepository $userRepository;

    public function __construct(PDO $connection)
    {
        $this->userRepository = new UserRepository($connection);
    }

    #[NoReturn] public function showLoginForm(): void
    {
        $this->render(Path::LOGIN->value);
    }

    public function login(string $email, string $password): bool|string
    {
        $user = $this->userRepository->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            return true;
        }

        return AppStrings::WRONG_INPUT->value;
    }

    #[NoReturn] public function logout(): void
    {
        session_destroy();
        $this->redirect('/');
    }

    public function showRegisterForm(): void
    {
        $this->render(Path::REGISTER->value);
    }

    public function register(string $username, string $email, string $password): bool|string
    {
        $user = $this->userRepository->findByEmail($email);

        if ($user) {
            return AppStrings::EMAIL_EXISTS->value;
        }

        $data = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ];

        $id = $this->userRepository->create($data);

        if ($id) {
            return true;
        }

        return AppStrings::REG_FAILED->value;
    }
}
