<?php declare(strict_types=1);

namespace App\Builder;

use App\Dto\AuthorSelectDTO;
use App\Dto\BookDTO;
use App\Entity\Book;

final class BookBuilder
{
    public function buildBookDTOFromRequest(array $data, int $userId): BookDTO
    {
        $bookDto = new BookDTO();

        $addedByUserId = $userId;
        $bookDto->id = $data['id'];
        $bookDto->title = $data['title'];

        $authorDto = new AuthorSelectDTO();
        $authorDto->id = $data['author_id'];
        $authorDto->fullName = $data['author_name'];

        $bookDto->author = $authorDto;
        $bookDto->description = $data['description'];
        $bookDto->publishedYear = $data['publishedYear'];
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
