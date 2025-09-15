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

    /**
     * @return Author[]
     */
    public function getForDropdown(): array
    {
        $rows = $this->connection->query(
            "SELECT id, first_name, last_name, bio FROM {$this->table} ORDER BY first_name, last_name"
        )->fetchAll(PDO::FETCH_ASSOC);

        $authors = [];
        foreach ($rows as $row) {
            $authors[] = new Author(
                $row['id'],
                $row['first_name'],
                $row['last_name'],
                $row['bio']
            );
        }

        return $authors;
    }
}