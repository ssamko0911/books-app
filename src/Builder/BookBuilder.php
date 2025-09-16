<?php declare(strict_types=1);

namespace App\Builder;

use App\Dto\AuthorSelectDTO;
use App\Dto\BookDTO;
use App\Entity\Author;
use App\Entity\Book;

final class BookBuilder
{
    public function buildBookDTOFromRequest(array $data, int $userId): BookDTO
    {
        $bookDto = new BookDTO();

        $id = (int)($data['book_id'] ?? null);
        $title = $data['title'] ?? '';
        $authorId =  (int)$data['author'];
        $authorFullName = $data['author_name'] ?? '';
        $description = $data['description'] ?? '';
        $publishedYear = $data['published_year'] ?? null;
        $addedByUserId = $userId;

        $bookDto->id = $id;
        $bookDto->title = $title;

        $authorDto = new AuthorSelectDTO();
        $authorDto->id = $authorId;
        $authorDto->fullName = $authorFullName;

        $bookDto->author = $authorDto;
        $bookDto->description = $description;
        $bookDto->publishedYear = $publishedYear ? (int)$publishedYear : null;
        $bookDto->addedByUserId = $addedByUserId;

        return $bookDto;
    }

    public function buildBookDTOFromEntity(Book $book): BookDTO
    {
        $bookDto = new BookDTO();
        $bookDto->id = $book->getId();
        $bookDto->title = $book->getTitle();

        $authorDto = new AuthorSelectDTO();
        $authorDto->id = $book->getAuthor()->getId();
        $authorDto->fullName = $book->getAuthor()->getFullName();

        $bookDto->author = $authorDto;
        $bookDto->description = $book->getDescription();
        $bookDto->publishedYear = $book->getPublishedYear();
        $bookDto->addedByUserId = $book->getAddedByUserId();

        return $bookDto;
    }
}