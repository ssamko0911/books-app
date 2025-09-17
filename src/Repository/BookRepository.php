<?php declare(strict_types=1);

namespace App\Repository;

use App\Dto\BookDTO;
use App\Entity\Author;
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
        $rows = $this->connection->query("SELECT b.*, a.first_name, a.last_name, a.bio FROM {$this->table} b JOIN {$this->joiningTable} a ON a.id = b.author_id")->fetchAll(PDO::FETCH_ASSOC);
        $books = [];

        foreach ($rows as $row) {
            $book = $this->mapRowToBook($row);
            $books[] = $book;
        }

        return $books;
    }

    public function findOneByIdWithAuthor(int $id): ?Book
    {
        $statement = $this->connection->prepare("SELECT b.*, a.first_name, a.last_name, a.bio FROM {$this->table} b JOIN {$this->joiningTable} a ON a.id = b.author_id WHERE b.id = :id");
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->mapRowToBook($row);
    }

    public function createFromDTO(BookDTO $bookDto): bool|string
    {
        $data = [
            'title' => $bookDto->title,
            'author_id' => $bookDto->author->id,
            'description' => $bookDto->description,
            'published_year' => $bookDto->publishedYear,
            'added_by_user' => $bookDto->addedByUserId,
        ];

        return $this->create($data);
    }

    public function updateFromDTO(BookDTO $bookDto): bool|string
    {
        $data = [
            'title' => $bookDto->title,
            'author_id' => $bookDto->author->id,
            'description' => $bookDto->description,
            'published_year' => $bookDto->publishedYear,
            'added_by_user' => $bookDto->addedByUserId,
        ];

        return $this->update($bookDto->id, $data);
    }

    public function mapRowToBook(array $row): Book
    {
        $author = new Author(
            id: $row['author_id'],
            firstName: $row['first_name'],
            lastName: $row['last_name'],
            biography: $row['bio'],
        );

        return new Book(
            id: $row['id'],
            title: $row['title'],
            author: $author,
            description: $row['description'],
            publishedYear: $row['published_year'],
            addedByUserId: $row['added_by_user'],
        );
    }
}
