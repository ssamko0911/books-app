<?php declare(strict_types=1);

namespace App\Database;
use PDO;
use PDOException;

class Database
{
    private static ?PDO $dbInstance = null;

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (is_null(self::$dbInstance)) {
            $host = $_ENV['DB_HOST'];
            $db = $_ENV['MYSQL_DATABASE'];
            $user = $_ENV['MYSQL_USER'];
            $password = $_ENV['MYSQL_PASSWORD'];
            $certPath = __DIR__ . $_ENV['CERT_PATH'];

            try {
                self::$dbInstance = new PDO("mysql:host=$host;dbname=$db", $user, $password, [
                    PDO::MYSQL_ATTR_SSL_CA => $certPath,
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die ('DB Connection failed: ' . $e->getMessage());
            }
        }

        return self::$dbInstance;
    }
}
