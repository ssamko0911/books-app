<?php declare(strict_types=1);

use App\Database\Database;
use App\Seeder\AuthorSeeder;
use App\Utils\Logger;

require __DIR__ . '/../vendor/autoload.php';

$dbConnection = Database::getInstance();
$authorSeeder = new AuthorSeeder($dbConnection);
$authorSeeder->run();

Logger::getLogger()->info('DB seeding completed.');
