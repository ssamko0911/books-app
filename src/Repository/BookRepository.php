<?php declare(strict_types=1);

namespace App\Repository;

use App\Dto\BookDTO;
use App\Entity\Book;
use PDO;

final class BookRepository extends BaseRepository
{
    protected string $table = 'book_recommendations_books';
    protected string $joiningTable = 'book_recommendations_authors';

    /**
     * @return Book[]
     */
    public function getAllWithAuthors(): array
    {
        return $this->connection->query("SELECT b.*, a.first_name, a.last_name, a.bio FROM {$this->table} b JOIN {$this->joiningTable} a ON a.id = b.author_id")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOneByIdWithAuthor(int $id): ?array
    {
        $statement = $this->connection->prepare("SELECT b.*, a.first_name, a.last_name, a.bio FROM {$this->table} b JOIN {$this->joiningTable} a ON a.id = b.author_id WHERE b.id = :id");
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $row;
    }

    public function createFromDTO(BookDTO $bookDto): bool|string
    {
        return $this->create(
            $this->getDTOData($bookDto)
        );
    }

    public function updateFromDTO(BookDTO $bookDto): bool|string
    {
        return $this->update(
            $bookDto->id,
            $this->getDTOData($bookDto)
        );
    }

    private function getDTOData($dto): array
    {
        return [
            'title' => $dto->title,
            'author_id' => $dto->author->id,
            'description' => $dto->description,
            'published_year' => $dto->publishedYear,
            'added_by_user' => $dto->addedByUserId,
        ];
    }
}
