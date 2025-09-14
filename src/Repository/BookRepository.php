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

    public function update(int $id, array $data): bool
    {
        $stmt = $this->connection->prepare(
            "UPDATE `book_recommendations_books` SET title = :title, author_id = :author_id, description = :description, published_year = :published_year, added_by_user = :added_by_user WHERE id = :id"
        );

        return $stmt->execute([
            ':id' => $id,
            ':title' => $data["title"],
            ':author_id' => $data["author_id"],
            ':description' => $data["description"],
            ':published_year' => $data["published_year"],
            ':added_by_user' => $data["added_by_user"],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare("DELETE FROM `book_recommendations_books` WHERE `id` = :id");

        return $stmt->execute([
            ":id" => $id,
        ]);
    }
}
