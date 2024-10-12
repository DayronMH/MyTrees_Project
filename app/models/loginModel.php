<?php
require_once 'databaseModel.php';
class loginModel extends BaseModel
{
    public function getUserByUsername($username)
    {
        $query = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE username = :username");
        $query->bindParam(':username', $username);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}