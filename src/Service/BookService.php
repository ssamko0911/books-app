<?php declare(strict_types=1);

namespace App\Service;

use App\Builder\AuthorEntityBuilder;
use App\Builder\BookEntityBuilder;
use App\Dto\AuthorSelectDTO;
use App\Dto\BookDTO;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;


class BookService
{
    public function __construct(
        private BookRepository      $bookRepository,
        private AuthorRepository    $authorRepository,
        private BookEntityBuilder   $bookBuilder,
        private AuthorEntityBuilder $authorBuilder,
    )
    {
    }

    /**
     * @return BookDTO[]
     */
    public function getAllBooksWithAuthors(): array
    {
        $data = $this->bookRepository->getAllWithAuthors();
        $books = $this->bookBuilder->buildBooksFromRow($data);

        return $this->bookBuilder->buildDTOs($books);
    }

    public function getBookWithAuthor(int $id): ?BookDTO
    {
        $data = $this->bookRepository->findOneByIdWithAuthor($id);

        if (null === $data) {
            return null;
        }

        $book = $this->bookBuilder->buildFromRow($data);

        return $this->bookBuilder->buildDTO($book);
    }

    public function getAuthorsForBookInsert(): array
    {
        $authors = $this->authorRepository->getForDropdown();

        return array_map(function (Author $author): AuthorSelectDTO {
            return $this->authorBuilder->buildAuthorSelectDTO($author);
        }, $authors);
    }

    public function createBook(array $data): bool|string
    {
        $bookDto = $this->bookBuilder->buildDTOFromRequest($data, $_SESSION['user_id']);

        return $this->bookRepository->createFromDTO($bookDto);
    }

    public function updateBook(array $data): bool|string
    {
        $bookDto = $this->bookBuilder->buildDTOFromRequest($data, $_SESSION['user_id']);

        return $this->bookRepository->updateFromDTO($bookDto);
    }

    public function deleteBook(int $id): ?bool
    {
        $book = $this->getBookWithAuthor($id);

        // TODO: rewrite
        if (null === $book) {
            return false;
        }

        $this->bookRepository->delete($id);

        return true;
    }
}
