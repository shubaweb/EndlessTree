<?php
class DB
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'categoriestree';
    const DB_USER = 'shuba';
    const DB_PASS = '198509';
    const DB_CHAR = 'utf8';

    protected static $instance = null;
    
    private function __construct() {
    }
    private function __clone() {
    }
    private static function getInstance()
    {
        if (self::$instance === null)
        {
            $opt  = array(
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => TRUE
            );
            $dsn = 'mysql:host='.self::DB_HOST.';dbname='.self::DB_NAME.';charset='.self::DB_CHAR;
            self::$instance = new PDO($dsn, self::DB_USER, self::DB_PASS, $opt);
        }
        return self::$instance;
    }
    public static function execQuery($sql, $args = [])
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
    public static function __callStatic($method, $args)
    {
        return call_user_func_array(array(self::getInstance(), $method), $args);
    }
}