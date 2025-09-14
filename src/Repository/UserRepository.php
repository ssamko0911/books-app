<?php declare(strict_types=1);

namespace App\Repository;

use PDO;

final class UserRepository extends BaseRepository
{
    protected string $table = 'book_recommendations_users';
    public function createUser(string $name, string $email, string $password): bool|string
    {
        $stmt = $this->connection->prepare("INSERT INTO {$this->table} (username,email,password) VALUES (?,?,?)");
        $stmt->execute([
            $name,
            $email,
            password_hash($password, PASSWORD_BCRYPT),
        ]);

        return $this->connection->lastInsertId();
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
