<?php declare(strict_types=1);

namespace App\Repository;

use PDO;

final readonly class UserRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function createUser(string $name, string $email, string $password): bool|string
    {
        $stmt = $this->connection->prepare("INSERT INTO `book_recommendations_users` (username,email,password) VALUES (?,?,?)");
        $stmt->execute([
            $name,
            $email,
            password_hash($password, PASSWORD_BCRYPT),
        ]);

        return $this->connection->lastInsertId();
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->connection->prepare("SELECT * FROM `book_recommendations_users` WHERE email = ?");
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
