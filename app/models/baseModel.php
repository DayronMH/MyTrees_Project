<?php
require_once '../../config/database.php';
class baseModel
{
    /**
     * @var PDO Database connection instance.
     */
    protected $db;

    /**
     * @var string Table name associated with the model.
     */
    protected $table;

    /**
     * BaseModel constructor.
     *
     * @param string $table The name of the database table.
     * @throws Exception If the database connection fails.
     */
    protected function __construct(string $table)
    {
        $this->table = $table;
        $this->connectToDatabase();
    }

    /**
     * Establishes a connection to the database.
     *
     * @throws Exception If the connection fails.
     */
    private function connectToDatabase(): void
    {
        try {
            $this->db = Database::connect('my_trees');
        } catch (Exception $e) {
            // Handle the error, log it, or rethrow it
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Closes the database connection.
     */
    public function disconnect(): void
    {
        $this->db = null; // Setting the db property to null will close the connection
    }
}
