<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private ?PDO $connection = null;

    public function connection(): PDO
    {
        if ($this->connection === null) {
            $config = require __DIR__ . '/../../config/database.php';
            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";

            try {
                $this->connection = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int) $e->getCode());
            }
        }

        return $this->connection;
    }
}
