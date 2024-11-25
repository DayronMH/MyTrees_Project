<?php

namespace App\Models;

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
            $host = env('DB_HOST', 'localhost');
            $dbname = env('DB_DATABASE', 'mytrees');
            $username = env('DB_USERNAME', 'root');
            $password = env('DB_PASSWORD', '');

            $this->db = new \PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $username,
                $password,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        } catch (\Exception $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function disconnect(): void
    {
        $this->db = null;
    }
}