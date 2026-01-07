<?php
class Order {
    private $db;

    public function __construct($mysqli){
        $this-> db = $mysqli;
    }

    public function createOrder($user_id, $date, $total){
        $query ="INSERT INTO pedidos (user_id,fecha, total) VALUES (?,?,?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isd", $user_id, $date, $total);

        if($stmt->execute()){
            return $this->db->insert_id;
        } else {
            return false;
        }
    }

    public function createDetail($product_id, $order_id, $price){
        $query = "INSERT INTO detalle_pedidos (product_id, pedido_id, precio) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iid", $product_id, $order_id, $price);
        $stmt->execute();
    }
    
    public function getOrdersByUserId($user_id){
        //Armo consulta multitabla para poder mostrar en base al usuario
        $query ="SELECT ped.id AS pedido_id, pr.name, pr.price, pr.image, ped.fecha FROM pedidos ped
                JOIN detalle_pedidos d ON ped.id = d.pedido_id
                JOIN product pr ON d.product_id = pr.id
                WHERE ped.user_id = ?
                ORDER BY ped.fecha DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>