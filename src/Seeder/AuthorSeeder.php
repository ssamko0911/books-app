<?php

namespace App\Seeder;

use App\Utils\Logger;

class AuthorSeeder extends Seeder
{
    public function run(int $count = 20): void
    {
        $stmt = $this->connection->prepare("
            INSERT INTO `book_recommendations_authors` (first_name, last_name, bio) VALUES (:first_name, :last_name, :bio)
            ");

        for ($i = 0; $i < $count; $i++) {
            $stmt->execute([
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'bio' => $this->faker->text,
            ]);
        }

        Logger::getLogger()->info("Seeded {$count} authors.");
    }
}