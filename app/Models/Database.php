<?php

class Database {
    private $pdo;

    public function __construct() {
        $config = require __DIR__ . '/../../config/database.php';
        $this->pdo = new PDO("mysql:host={$config['host']};dbname={$config['name']}", $config['user'], $config['pass']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getPdo() {
        return $this->pdo;
    }
}
