<?php declare(strict_types=1);

namespace App\Service;

use App\Builder\AuthorEntityBuilder;
use App\Builder\BookEntityBuilder;
use App\Dto\AuthorSelectDTO;
use App\Dto\BookDTO;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;


readonly class BookService
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
        $rows = $this->bookRepository->getAllWithAuthors();
        $books = $this->bookBuilder->buildEntitiesFromRows($rows);

        return $this->bookBuilder->buildDTOs($books);
    }

    public function getBookWithAuthor(int $id): ?BookDTO
    {
        $row = $this->bookRepository->findOneByIdWithAuthor($id);

        if (null === $row) {
            return null;
        }

        $book = $this->bookBuilder->buildEntityFromRow($row);

        return $this->bookBuilder->buildDTO($book);
    }

    public function getAuthorChoices(): array
    {
        $rows = $this->authorRepository->getAllOrdered();
        $authors = $this->authorBuilder->buildEntitiesFromRows($rows);

        return $this->authorBuilder->buildSelectDTOs($authors);
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
