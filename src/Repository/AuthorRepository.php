<?php declare(strict_types=1);

namespace App\Repository;

use App\Dto\AuthorCreateDTO;
use App\Entity\Author;
use PDO;

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

    public function getAllOrdered(): array
    {
        return $this->connection->query(
            "SELECT id, first_name, last_name, bio FROM {$this->table} ORDER BY first_name, last_name"
        )->fetchAll(PDO::FETCH_ASSOC);
    }
}