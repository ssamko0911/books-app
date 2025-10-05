<?php declare(strict_types=1);

namespace App\Controller;

use App\Enum\AppStrings;
use App\Enum\Path;
use App\Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;

final class AuthController extends BaseController
{
    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    #[NoReturn] public function showLoginForm(): void
    {
        $this->render(Path::LOGIN->value);
    }

    public function login(): void
    {
        if ('POST' !== $_SERVER['REQUEST_METHOD']) {
            $this->render(Path::LOGIN->value);
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->loginUser($email, $password);

        if (true === $result) {
            header('Location: /');
            exit();
        }

        $error = $result;

        $this->render(Path::LOGIN->value, [
                'error' => $error,
            ]
        );
    }

    private function loginUser(string $email, string $password): bool|string
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

    public function register(): void
    {
        if ('POST' !== $_SERVER['REQUEST_METHOD']) {
            $this->render(Path::REGISTER->value);
        }

        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            $this->render(Path::REGISTER->value, [
                'error' => AppStrings::EMAIL_EXISTS->value,
            ]);
        }

        $data = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ];

        $id = $this->userRepository->create($data);

        if (!$id) {
            $this->render(Path::REGISTER->value, [
                'error' => AppStrings::REG_FAILED->value,
            ]);
        }

        $result = $this->loginUser($email, $password);

        if (true === $result) {
            header('Location: /');
            exit();
        }

        $this->render(Path::REGISTER->value, [
                'error' => $result,
            ]
        );
    }
}
