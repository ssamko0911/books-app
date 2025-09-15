<?php declare(strict_types=1);

namespace App\Repository;

use PDO;

final class BookRepository extends BaseRepository
{
    protected string $table = 'book_recommendations_books';
    protected string $joiningTable = 'book_recommendations_authors';

    public function getAllWithAuthors(): array
    {
        return $this->connection->query("SELECT b.*, a.first_name, a.last_name FROM {$this->table} b JOIN {$this->joiningTable} a ON a.id = b.author_id")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOneByIdWithAuthor(int $id): ?array
    {
        $statement = $this->connection->prepare("SELECT b.*, a.first_name, a.last_name FROM {$this->table} b JOIN {$this->joiningTable} a ON a.id = b.author_id WHERE b.id = :id");
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
