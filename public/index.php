<?php declare(strict_types=1);

$host = $_ENV['DB_HOST'];
$db = $_ENV['MYSQL_DATABASE'];
$user = $_ENV['MYSQL_USER'];
$password = $_ENV['MYSQL_PASSWORD'];
$certPath = __DIR__ . $_ENV['CERT_PATH'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password, [
        PDO::MYSQL_ATTR_SSL_CA => $certPath,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo 'Connected';

    return $pdo;
} catch (PDOException $e) {
    echo 'Not connected: ' . $e->getMessage();
}
