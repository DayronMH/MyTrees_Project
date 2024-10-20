<?php
require_once 'databaseModel.php';
class usersModel extends BaseModel
{
    public function createUser($name, $email, $password, $role, $phone, $address, $country)
    {
        $query = "INSERT INTO `users`( `name`, `email`, `password`, `phone`, `address`, `country`) 
                  VALUES ( :name, :email, :password, :phone, :address, :country)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':country', $country);
        return $stmt->execute();
    }

    public function handleLogin($email, $password)
    {
        $query = "SELECT * FROM `users` WHERE `email` = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUsers()
    {
        $query = "SELECT * FROM `users`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
