<?php declare(strict_types=1);

namespace App\Repository;

use PDO;

class BaseRepository
{
    protected PDO $connection;
    protected string $table;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        return $this->connection->query("SELECT * FROM {$this->table}")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOneById(int $id): ?array
    {
        $statement = $this->connection->prepare("SELECT * FROM {$this->table} WHERE `id` = :id");
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(string $title, int $author, int $user, ?string $description = null, ?int $publishedYear = null ): bool|string
    {
        $stmt = $this->connection->prepare("INSERT INTO `book_recommendations_books` (title,author_id,description,published_year,added_by_user) VALUES (?,?,?,?,?)");
        $stmt->execute([
            $title,
            $author,
            $description,
            $publishedYear,
            $user,
        ]);

        return $this->connection->lastInsertId();
    }


    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare("DELETE FROM {$this->table} WHERE `id` = :id");

        return $stmt->execute([
            ":id" => $id,
        ]);
    }
}