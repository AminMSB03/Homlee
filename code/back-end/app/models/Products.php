<?php 




class Products extends DB 
{
    private $table = "products";
    private $conn; 

    public function __construct()
    {
        $this->conn = $this->connect();
        
    }

    public function get()
    {
        $query = "SELECT * FROM $this->table ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function addProduct($name, $category, $price, $quantity,$img,$description)
    {
        $query = "INSERT INTO $this->table (`name`,`category`,`price`,`quantity`,`img`,`description`) VALUES (?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $name,
            $category,
            $price,
            $quantity,
            $img,
            $description
        ]);
        return $this->conn->lastInsertId();
    }

    public function delete($id){
        $query = "DELETE FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(1, $id);

        return $stmt->execute();
    } 



    public function single($id)
    {   
        $query = "SELECT * FROM $this->table WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(1, $id);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id,$name,$price,$category,$status,$quantity,$img,$description)
    {
        $query = "UPDATE $this->table SET `name`=:name,`price`=:price,`category`=:category,`status`=:status,`quantity`=:quantity,`img`=:img,`description`=:description WHERE id =:id ";
        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':name',$name);
        $stmt->bindValue(':price',$price);
        $stmt->bindValue(':category',$category);
        $stmt->bindValue(':quantity',$quantity);
        $stmt->bindValue(':status',$status);
        $stmt->bindValue(':img',$img);
        $stmt->bindValue(':description',$description);
        $stmt->bindValue(':id',$id);


        $stmt->execute();
        return $stmt;
    }


    public function getTwenty()
    {
        $query = "SELECT * FROM $this->table ORDER BY sales LIMIT 12";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}