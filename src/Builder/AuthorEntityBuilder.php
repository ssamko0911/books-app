<?php declare(strict_types=1);

namespace App\Builder;
use App\Dto\AuthorCreateDTO;
use App\Dto\AuthorSelectDTO;
use App\Entity\Author;

final class AuthorEntityBuilder
{
    public function buildEntityFromRow(array $row): Author
    {
        return new Author(
            id: $row['id'],
            firstName: $row['first_name'],
            lastName: $row['last_name'],
            biography: $row['bio'],
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

    public function buildDTOFromRequest(array $data): AuthorSelectDTO
    {
        $authorDto = new AuthorSelectDTO();
        $authorDto->id = (int) $data['author'];
        $authorDto->fullName = $data['author_name'];

        return $authorDto;
    }
    public function buildAuthorCreateDTO(array $data): AuthorCreateDTO
    {
        $authorDto = new AuthorCreateDTO();
        $authorDto->firstName = trim($data['first_name'] ?? '');
        $authorDto->lastName = trim($data['last_name'] ?? '');
        $authorDto->biography = trim($data['bio'] ?? '');

        return $authorDto;
    }

    public function buildSelectDTO(Author $author): AuthorSelectDTO
    {
        $selectDTO = new AuthorSelectDTO();
        $selectDTO->id = $author->getId();
        $selectDTO->fullName = $author->getFullName();

        return $selectDTO;
    }

    public function buildSelectDTOs(array $entities): array
    {
        $selectDTOs = [];

        foreach ($entities as $entity) {
            $selectDTOs[] = $this->buildSelectDTO($entity);
        }

        return $selectDTOs;
    }
}
