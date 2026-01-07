<?php
class Product{

    private $db;

    public function __construct($mysqli){
        $this -> db= $mysqli;
    }

    public function createProduct ($name, $price, $image, $descript, $stock){
        $query = "INSERT INTO product (name, price, image, descript, stock) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt -> bind_param("sdssi", $name, $price, $image, $descript, $stock);
        return $stmt->execute();
    }

    public function deleteProduct ($id){
        $query = "UPDATE product SET disponible = FALSE WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function alterProduct($name, $price, $id){
        $query = "UPDATE product SET name=?, price=? WHERE id= ?";
        $stmt = $this->db->prepare($query);
        $stmt -> bind_param("sdi", $name, $price, $id);
        return $stmt->execute();
    }

    public function getProductById($id){
        //SELECT * FROM product WHERE id = '$id'
        $query = "SELECT * FROM product WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt -> bind_param("i", $id);
        $stmt -> execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc(); //Devuelve UN solo producto (por id)
    }

    public function getAllProducts(){
        $query = "SELECT * FROM product WHERE disponible = TRUE";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt -> get_result();

        return $result->fetch_all(MYSQLI_ASSOC); //Devuelve la totalidad de productos en array
}
}
?>