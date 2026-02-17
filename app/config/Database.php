<?php
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
                "cyberlantern",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                ]
            );
        }
        return self::$db = $db;
    }
}
