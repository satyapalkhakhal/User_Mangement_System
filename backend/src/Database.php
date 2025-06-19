<?php
class Database {
    private static $instance = null;
    private $connection;

    private function __construct($config) {
        try {
            $this->connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['name']};charset={$config['charset']}",
                $config['user'],
                $config['pass']
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance($config) {
        if (!self::$instance) {
            self::$instance = new Database($config);
        }
        return self::$instance->connection;
    }
}