<?php declare(strict_types=1);

namespace App\Repository;

use PDO;

class RecommendationRepository
{
    private PDO $connection;
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        return $this->connection->query("SELECT * FROM `book_recommendations_recommendations`")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOneById(int $id): ?array
    {
        $statement = $this->connection->prepare("SELECT * FROM `book_recommendations_recommendations` WHERE `id` = :id");
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}