<?php

/**
 * Class Database
 * Gère la connexion PDO à la base de données MySQL.
 */
class Database
{
    private static ?PDO $instance = null;

    private const HOST = '127.0.0.1';
    private const PORT = '3306';
    private const DB_NAME = 'poo_zoo';
    private const USERNAME = 'root';
    private const PASSWORD = '';

    private function __construct()
    {
    }

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                self::HOST,
                self::PORT,
                self::DB_NAME
            );

            self::$instance = new PDO($dsn, self::USERNAME, self::PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return self::$instance;
    }
}
