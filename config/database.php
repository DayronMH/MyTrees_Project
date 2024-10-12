<?php    
class Database {
    private static $host = "localhost";
    private static $username = "root"; 
    private static $password = "";
    private static $connection = null;
    public static function connect($dbname) {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . $dbname,
                    self::$username,
                    self::$password
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error en la conexión: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
    public static function disconnect() {
        self::$connection = null;
    }
}
