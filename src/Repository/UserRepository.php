<?php declare(strict_types=1);

namespace App\Repository;

use PDO;

final class UserRepository extends BaseRepository
{
    protected string $table = 'book_recommendations_users';

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
