<?php
require_once '../../config/database.php';
class baseModel
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
            $this->db = Database::connect('mytrees');
        } catch (Exception $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function disconnect(): void
    {
        $this->db = null;
    }
}
