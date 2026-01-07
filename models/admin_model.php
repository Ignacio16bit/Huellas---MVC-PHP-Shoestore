<?php
//CRUD de admin
class AdminModel{

    private $db;

    public function __construct($mysqli){
        $this->db= $mysqli;
    }

    public function createAdmin ($name, $email, $password, $role){
        //crear/ INSERT
        $query ="INSERT INTO usuarios (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt -> bind_param("ssss", $name, $email, $password, $role);

    return $stmt -> execute();//True/false
    }

    public function getUserByEmail($email){
        $query = "SELECT id, name, email, role FROM usuarios WHERE email= ?";
        $stmt= $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getAllUsers(){
        $query = "SELECT id, name, email, role FROM usuarios WHERE activo = TRUE";
        $stmt = $this->db->prepare($query);
        $stmt -> execute();
        $result = $stmt->get_result();

        return $result -> fetch_all(MYSQLI_ASSOC);
    }

    public function modifyUser($name, $email, $id){
        $query = "UPDATE usuarios SET name=?, email=? WHERE id= ?";
        $stmt = $this->db->prepare($query);
        $stmt -> bind_param("ssi", $name, $email, $id);

        return $stmt->execute();
    }

    public function deleteUser($id){
        $query = "UPDATE usuarios SET activo = FALSE WHERE id=?";
        $stmt = $this-> db ->prepare($query);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

}
?>