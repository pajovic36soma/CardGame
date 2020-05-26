<?php

class User{
 
    private $conn;
    private $table_name = "users";
 
    public $id;
    public $username;
    public $password;
    public $email;
    public $balance;
 
    public function __construct($db){
        $this->conn = $db;
    }
    
    // create user function
    function create(){

        $query = "INSERT INTO " . $this->table_name . "
                SET
                    username = :username,
                    password = :password,
                    email = :email,
                    balance = :balance";

        $stmt = $this->conn->prepare($query);

        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->balance=htmlspecialchars(strip_tags($this->balance));
        

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':balance', $this->balance);
        $stmt->bindParam(':email', $this->email);

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        if($stmt->execute()){
            return true;
        }

        return false;
    }
    
    function balanceState($id){
        
        $sql = "SELECT * FROM " . $this->table_name .
                " WHERE id = '$id'";
        
        $sol = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        
        return $sol[0]['balance'];
    }

    function updateBalance($id, $new_balance){
        $sql = "UPDATE " . $this->table_name . " SET balance = :balance "
                . "WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':balance', $new_balance);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    function emailExists(){
 
        $query = "SELECT id, username, password
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare( $query );

        $this->email=htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(1, $this->email);

        $stmt->execute();

        $num = $stmt->rowCount();

        if($num>0){

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = $row['password'];

            return true;
        }

        return false;
    }
}

