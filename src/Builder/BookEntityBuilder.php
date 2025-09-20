<?php declare(strict_types=1);

namespace App\Builder;

use App\Dto\BookDTO;
use App\Entity\Book;

final readonly class BookEntityBuilder
{
    public function __construct(
        private AuthorEntityBuilder $authorBuilder
    )
    {
    }
    public function buildEntityFromRow(array $row): Book
    {
        return new Book(
            id: $row['id'],
            title: $row['title'],
            author: $this->authorBuilder->buildEntityFromRow($row),
            description: $row['description'],
            publishedYear: $row['published_year'],
            addedByUserId: $row['added_by_user'],
        );
    }

    public function buildEntitiesFromRows(array $rows): array
    {
        $books = [];

        foreach ($rows as $row) {
            $books[] = $this->buildEntityFromRow($row);
        }

        return $books;
    }

    public function buildDTOFromRequest(array $data, int $userId): BookDTO
    {
        $bookDto = new BookDTO();

        $addedByUserId = $userId;
        $bookDto->id = (int) ($data['book_id'] ?? null);
        $bookDto->title = $data['title'];
        $bookDto->author = $this->authorBuilder->buildDTOFromRequest($data);
        $bookDto->description = $data['description'];
        $bookDto->publishedYear = (int) $data['published_year'];
        $bookDto->addedByUserId = $addedByUserId;

        return $bookDto;
    }

    public function buildDTO(Book $book): BookDTO
    {
        $bookDto = new BookDTO();
        $bookDto->id = $book->getId();
        $bookDto->title = $book->getTitle();
        $bookDto->author = $this->authorBuilder->buildSelectDTO($book->getAuthor());
        $bookDto->description = $book->getDescription();
        $bookDto->publishedYear = $book->getPublishedYear();
        $bookDto->addedByUserId = $book->getAddedByUserId();

        return $bookDto;
    }

    /**
     * @param Book[] $books
     * @return BookDTO[]
     */
    public function buildDTOs(array $books): array
    {
        $bookDTOs = [];
        foreach ($books as $book) {
            $bookDTOs[] = $this->buildDTO($book);
        }

        return $bookDTOs;
    }
}
