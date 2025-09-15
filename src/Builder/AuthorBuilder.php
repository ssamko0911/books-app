<?php declare(strict_types=1);

namespace App\Builder;
use App\Dto\AuthorCreateDTO;
use App\Entity\Author;

final class AuthorBuilder
{
    public function buildFromDto(AuthorCreateDTO $authorDTO): Author
    {
        $author = new Author();
        $author->setFirstName($authorDTO->firstName);
        $author->setLastName($authorDTO->lastName);
        $author->setBiography($authorDTO->biography);

        return $author;
    }
}