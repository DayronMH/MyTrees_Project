<?php
require_once 'baseModel.php';

class UsersModel extends BaseModel
{
    public function __construct() {
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

    public function createUser (string $name, string $email, string $password, string $role, string $phone, string $address, string $country): bool
    {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `address`, `country`) 
                  VALUES (:name, :email, :password, :role, :phone, :address, :country)";
        
        return $this->executeQuery($query, [
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
    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $query = "INSERT INTO `users` (`name`, `email`, `password`, `phone`, `address`, `country`) 
              VALUES (:name, :email, :password, :phone, :address, :country)";
    
    return $this->executeQuery($query, [
        ':name' => $name,
        ':email' => $email,
        ':password' => $hashedPassword,
        ':phone' => $phone,
        ':address' => $address,
        ':country' => $country
    ]);
}

    /**
     * Handles user login by checking email and password.
     *
     * @param string $email The user's email.
     * @param string $password The user's password.
     * @return array|null Returns user data if successful, null otherwise.
     */
    public function handleLogin(string $email): ?array
    {
        $query = "SELECT * FROM `users` WHERE `email` = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
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
    

    /**
     * Executes a query and fetches the results.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return mixed Returns the result of the query execution.
     */
    private function executeQuery(string $query, array $params = []): mixed
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
}