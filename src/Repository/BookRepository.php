<?php declare(strict_types=1);

namespace App\Repository;

final class BookRepository extends BaseRepository
{
    protected string $table = 'book_recommendations_books';

    public function create(
        string  $title,
        int     $author,
        int     $user,
        ?string $description = null,
        ?int    $publishedYear = null
    ): bool|string
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO {$this->table} (title,author_id,description,published_year,added_by_user) VALUES (?,?,?,?,?)"
        );
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
            "UPDATE {$this->table} SET title = :title, author_id = :author_id, description = :description, published_year = :published_year WHERE id = :id"
        );

        return $stmt->execute([
            ':id' => $id,
            ':title' => $data["title"],
            ':author_id' => $data["author_id"],
            ':description' => $data["description"],
            ':published_year' => $data["published_year"],
        ]);
    }
}
