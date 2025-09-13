<?php declare(strict_types=1);

namespace App\Controller;

use App\Enum\AppStrings;
use App\Enum\Path;
use App\Repository\UserRepository;
use App\Utils\UrlTool;
use JetBrains\PhpStorm\NoReturn;

final readonly class AuthController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    #[NoReturn] public function showLoginForm(): void
    {
        UrlTool::view(Path::LOGIN->value);
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
        header('Location: /');
        exit();
    }

    public function showRegisterForm(): void
    {
        UrlTool::view(Path::REGISTER->value);
    }

    public function register(string $username, string $email, string $password): bool|string
    {
        $user = $this->userRepository->findByEmail($email);

        if ($user) {
            return AppStrings::EMAIL_EXISTS->value;
        }

        $id = $this->userRepository->createUser($username, $email, $password);

        if ($id) {
            return true;
        }

        return AppStrings::REG_FAILED->value;
    }
}
