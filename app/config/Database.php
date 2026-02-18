<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class Database
{
    public static $db;
    public static function connect()
    {
        if (self::$db != null) {
            return self::$db;
        } else {
            $db = new PDO(
                "mysql:host=localhost;dbname=flashcard_db",
                "root",
                $_ENV['DB_PASS'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                ]
            );
        }
        return self::$db = $db;
    }
}
