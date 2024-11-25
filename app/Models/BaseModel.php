<?php

namespace App\Models;

use Illuminate\Support\Facades\Config;
use PDO;
use Exception;

class BaseModel 
{
    protected $db;
    protected $table;

    protected function __construct(string $table)
    {
        $this->table = $table;
        $this->connectToDatabase();
    }

    private function connectToDatabase(): void
    {
        try {
            // Usar Config facade en lugar de env() directamente
            $host = Config::get('database.connections.mysql.host', 'localhost');
            $dbname = Config::get('database.connections.mysql.database', 'mytrees');
            $username = Config::get('database.connections.mysql.username', 'root');
            $password = Config::get('database.connections.mysql.password', '');
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];

            $this->db = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                $options
            );
        } catch (Exception $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function disconnect(): void
    {
        $this->db = null;
    }

    /**
     * Get the database connection instance
     *
     * @return PDO
     */
    protected function getConnection(): PDO
    {
        if ($this->db === null) {
            $this->connectToDatabase();
        }
        return $this->db;
    }

    /**
     * Prepare a statement with the database connection
     *
     * @param string $query
     * @return \PDOStatement
     */
    protected function prepare(string $query): \PDOStatement
    {
        return $this->getConnection()->prepare($query);
    }
}