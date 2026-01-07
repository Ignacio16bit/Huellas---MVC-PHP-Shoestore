<?php

class UserModel{
    //PROPIEDADES
    private $db;

    //FUNCIONES
    public function __construct($mysqli){
        $this->db = $mysqli;
    }

    //CRUD
    public function createUser ($name, $email, $password, $role){
    //crear/ INSERT
    $stmt = $this->db->prepare("INSERT INTO usuarios (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt -> bind_param("ssss", $name, $email, $password, $role);

    return $stmt -> execute();//True/false
    }

    public function getUserByEmail($email){
        //SELECT * FROM users WHERE email = $email
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email= ? AND activo=TRUE");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateUser($name, $email, $id){
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

    public function changePass($password, $id){
        $query = "UPDATE usuarios SET password=? WHERE id=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $password, $id);

        return $stmt->execute();
    }

}
?>