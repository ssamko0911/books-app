<?php declare(strict_types=1);

namespace App\Builder;
use App\Dto\AuthorCreateDTO;
use App\Dto\AuthorSelectDTO;
use App\Entity\Author;

final class AuthorEntityBuilder
{
    public function buildFromRow(array $row): Author
    {
        return new Author(
            id: $row['author_id'],
            firstName: $row['first_name'],
            lastName: $row['last_name'],
            biography: $row['bio'],
        );
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

    public function buildAuthorSelectDTO(Author $author): AuthorSelectDTO
    {
        $selectDTO = new AuthorSelectDTO();
        $selectDTO->id = $author->getId();
        $selectDTO->fullName = $author->getFullName();

        return $selectDTO;
    }
}
