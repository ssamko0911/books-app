<?php declare(strict_types=1);

namespace App\Builder;
use App\Dto\AuthorCreateDTO;
use App\Dto\AuthorSelectDTO;
use App\Entity\Author;

final class AuthorBuilder
{
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
}