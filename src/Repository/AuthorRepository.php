<?php declare(strict_types=1);

namespace App\Repository;

use App\Dto\AuthorCreateDTO;

final class AuthorRepository extends BaseRepository
{
    protected string $table = 'book_recommendations_authors';

    public function createFromDto(AuthorCreateDTO $author): bool|string
    {
        $data = [
            'first_name' => $author->firstName,
            'last_name' => $author->lastName,
            'bio' => $author->biography,
        ];

        return $this->create($data);
    }
}