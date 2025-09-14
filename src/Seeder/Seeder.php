<?php declare(strict_types=1);

namespace App\Seeder;
use Faker\Generator;
use PDO;
use \Faker\Factory as Factory;
abstract class Seeder
{
    protected PDO $connection;
    protected Generator $faker;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->faker = Factory::create();
    }

    abstract public function run(): void;
}
