<?php declare(strict_types=1);

namespace App\Database;

use App\Utils\Logger;
use App\Utils\UrlTool;
use PDO;
use PDOException;
use PH7\JustHttp\StatusCode;

class Database
{
    private static ?PDO $dbInstance = null;

    private function __construct()
    {
    }

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

                Logger::getLogger()->info('DB connected successfully');
            } catch (PDOException $e) {
                Logger::getLogger()->error('DB Connection failed: ' . $e->getMessage(), [
                    'host' => $host,
                    'db' => $db,
                    'exception' => $e,
                ]);

                UrlTool::abort(StatusCode::INTERNAL_SERVER_ERROR);
            }
        }

        return self::$dbInstance;
    }
}
