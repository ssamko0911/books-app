<?php declare(strict_types=1);

namespace App\Repository;

use PDO;

abstract class BaseRepository
{
    protected PDO $connection;
    protected string $table;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        return $this->connection->query("SELECT * FROM {$this->table}")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOneById(int $id): ?array
    {
        $statement = $this->connection->prepare("SELECT * FROM {$this->table} WHERE `id` = :id");
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool|string
    {
        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));

        $stmt = $this->connection->prepare("INSERT INTO {$this->table} ($columns) VALUES ($values)");
        $stmt->execute($data);

        return $this->connection->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = implode(', ', array_map(static function (string $column): string {
                return $column . ' = :' . $column;
            }, array_keys($data))
        );

        $data['id'] = $id;

        $stmt = $this->connection->prepare(
            "UPDATE {$this->table} SET $fields WHERE id = :id"
        );

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare("DELETE FROM {$this->table} WHERE `id` = :id");

        return $stmt->execute([
            ':id' => $id,
        ]);
    }
}
