<?php
require_once 'baseModel.php';

class UsersModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct('users');
    }

    public function createUser(string $name, string $email, string $password, string $role, string $phone, string $address, string $country): bool
    {
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
    
    private function executeNonQuery(string $query, array $params = []): bool
    {
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new Exception("Database query failed: " . $e->getMessage());
        }
    }

    public function handleLogin(string $email)
    {
        $query = "SELECT * FROM `users` WHERE `email` = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
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
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    private function executeQuery(string $query, array $params = []): array
    {
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database query failed: " . $e->getMessage());
        }
    }
    public function createFirstAdmin($role): bool 
    {
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
