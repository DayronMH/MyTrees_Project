<?php
require_once 'baseModel.php';

class UsersModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct('users');
    }

    /**
     * Creates a new user record.
     *
     * @param string $name The name of the user.
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     * @param string $role The role of the user.
     * @param string $phone The phone number of the user.
     * @param string $address The address of the user.
     * @param string $country The country of the user.
     * @return bool Returns true on success, false on failure.
     */
    public function createUser(string $name, string $email, string $password, string $role, string $phone, string $address, string $country): bool
    {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `address`, `country`) 
                  VALUES (:name, :email, :password, :role, :phone, :address, :country)";

        return $this->executeNonQuery($query, [
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role,
            ':phone' => $phone,
            ':address' => $address,
            ':country' => $country
        ]);
    }

    /**
     * Creates a new default Friend user record.
     *
     * @param string $name The name of the user.
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     * @param string $phone The phone number of the user.
     * @param string $address The address of the user.
     * @param string $country The country of the user.
     * @return bool Returns true on success, false on failure.
     */
    public function createFriendUser(string $name, string $email, string $password, string $phone, string $address, string $country): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO `users` (`name`, `email`, `password`, `phone`, `address`, `country`) 
                  VALUES (:name, :email, :password, :phone, :address, :country)";

        return $this->executeNonQuery($query, [
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':phone' => $phone,
            ':address' => $address,
            ':country' => $country
        ]);
    }

    /**
     * Executes a non-query SQL statement like INSERT, UPDATE, DELETE.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return bool Returns true if the query was successful, false otherwise.
     */
    private function executeNonQuery(string $query, array $params = []): bool
    {
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            // Handle the error, log it, or rethrow it
            throw new Exception("Database query failed: " . $e->getMessage());
        }
    }

    /**
     * Handles user login by checking email.
     *
     * @param string $email The user's email.
     * @return array|null Returns user data if found, null otherwise.
     */
    public function handleLogin(string $email)
    {
        $query = "SELECT * FROM `users` WHERE `email` = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Devuelve el resultado como un array o null si no se encuentra el usuario
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countFriends(): int
    {
        $query = "SELECT COUNT(*) as friend_count 
              FROM `users` 
              WHERE `role` = 'friend'";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['friend_count'];
    }
    /**
     * Retrieves all users.
     *
     * @return array Returns an array of users.
     */
    public function getUsers(): array
    {
        $query = "SELECT * FROM `users`";
        return $this->executeQuery($query);
    }
    public function getFriends(){
        $query = "SELECT * FROM `users` WHERE `role` = 'friend'";
        return $this->executeQuery($query);
    }
    public function getUserById($id): ?array
{
    $query = "SELECT * FROM `users` WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->execute([':id' => $id]);
    
    // Usar fetch en lugar de fetchAll para obtener un solo registro
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
}

    /**
     * Executes a query and fetches the results (used for SELECT queries).
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return array Returns the result set as an array.
     */
    private function executeQuery(string $query, array $params = []): array
    {
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the error, log it, or rethrow it
            throw new Exception("Database query failed: " . $e->getMessage());
        }
    }
    public function createFirstAdmin($role): bool 
    {
        // Check for previous admins
        $checkAdminQuery = "SELECT COUNT(*) as admin_count FROM `users` WHERE `role` = 'admin'";
        $result = $this->executeQuery($checkAdminQuery);
        $adminExists = $result[0]['admin_count'] > 0;

        if (!$adminExists && $role === 'admin') {
            $name = "Admin";
            $email = "Admin@gmail.com";
            $password = "123";
            $phone = "123";
            $address = "123";
            $country = $country = "123";
        }

        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `address`, `country`)
                VALUES (:name, :email, :password, :role, :phone, :address, :country)";

        return $this->executeNonQuery($query, [
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role,
            ':phone' => $phone,
            ':address' => $address,
            ':country' => $country
        ]);
    }
}
