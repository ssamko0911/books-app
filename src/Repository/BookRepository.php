<?php declare(strict_types=1);

namespace App\Repository;

use App\Database\Database;
use PDO;

final class BookRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->connection->query("SELECT * FROM `book_recommendations_books`")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOneById(int $id): ?array
    {
        $statement = $this->connection->prepare("SELECT * FROM `book_recommendations_books` WHERE `id` = :id");
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(string $title, string $author, ?string $description = null, ?int $publishedYear = null): bool|string
    {
        $stmt = $this->connection->prepare("INSERT INTO `book_recommendations_books` (title,author_id,description,published_year) VALUES (?,?,?,?)");
        $stmt->execute([
            $title,
            $author,
            $description,
            $publishedYear
        ]);

        return $this->connection->lastInsertId();
    }
}
