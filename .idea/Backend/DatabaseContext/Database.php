<?php
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'volkstuinen';
//De connectie naar de Database.
class Database
{
    private static $connection = null;

    private function __construct() {}

    public static function GetConnection()
    {
        if (self::$connection === null)
        {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            try
            {
                self::$connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            }
            catch (PDOException $e)
            {
                error_log('Database connection error: ' . $e->getMessage());
                throw new Exception('Could not connect to the database. Please try again later.');
            }
        }
        return self::$connection;
    }
}