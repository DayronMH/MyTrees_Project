<?php
require_once 'databaseModel.php';
require_once 'loginModel.php';

class RegisterModel extends BaseModel
{
    public function createUser($name, $lastname, $phone, $address, $loginId, $province_id, $role_id)
    {
        $query = "INSERT INTO `users`( `name`, `lastname`, `phone`, `address`, `login_id`, `province_id`, `role_id`) 
                  VALUES (:name, :lastname, :phone, :address, :login_id, :province_id, :role_id)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':login_id', $loginId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':province_id', $province_id);
        $stmt->bindParam(':role_id', $role_id);
        return $stmt->execute();
    }
    public function getAllUsers()
    {
        $queryStr = "SELECT * FROM " . "users";
        $query = $this->db->prepare($queryStr);
        $query->execute();
        Database::disconnect();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getUserByLoginId($loginId)
    {
        $query = $this->db->prepare("SELECT * FROM users WHERE login_id = :login_Id");
        $query->bindParam(':login_Id', $loginId);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    public function getUserById($id)
    {
        $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    public function deleteUser($id)
    {
        $queryStr = "DELETE FROM users WHERE `id` = $id";
        $query = $this->db->prepare($queryStr);
        $query->execute();
    }
    public function updateUser($id, $name, $lastname, $phone, $province_id, $address)
    {
        $query = "UPDATE users SET name = :name, lastname = :lastname, phone = :phone, 
              province_id = :province_id, address = :address WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':province_id', $province_id);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
    public function getActiveUsers($timeLimit) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE status = 'active' AND last_login_datetime < ?");
        $stmt->execute([$timeLimit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsInactive($userId) {
        $stmt = $this->db->prepare("UPDATE users SET status = 'inactive' WHERE id = ?");
        $stmt->execute([$userId]);
    }
}
