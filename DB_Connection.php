<?php

class DB_Connection {
    private static $instance = null;

    private static $db_host = '172.28.0.2';
    private static $db_name = 'blog';
    private static $db_user = 'root';
    private static $db_password = 'secret';

    private function __construct() {
    }

    private function __clone()
    {
        throw new Exception("Can't clone a singleton");
    }

    private function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new \PDO('mysql:host=' . self::$db_host . ';dbname=' . self::$db_name, self::$db_user, self::$db_password);
        }
        return self::$instance;
    }
}