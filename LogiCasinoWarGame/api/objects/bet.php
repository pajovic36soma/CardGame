<?php

class Bet{
    private $conn;
    private $table_name = "bets";

    public $id;
    public $user_id;
    public $dealer_card;
    public $client_card;
    public $normal_bet;
    public $tie_bet;
    public $status;

    public function __construct($db){
       $this->conn = $db;
    }   
    
    function create(){
        
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    user_id = :user_id,
                    dealer_card = :dealer_card,
                    client_card = :client_card,
                    normal_bet = :normal_bet,
                    tie_bet = :tie_bet,
                    status = :status";
        
        $stmt = $this->conn->prepare($query);

        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->dealer_card=htmlspecialchars(strip_tags($this->dealer_card));
        $this->client_card=htmlspecialchars(strip_tags($this->client_card));
        $this->normal_bet=htmlspecialchars(strip_tags($this->normal_bet));
        $this->tie_bet=htmlspecialchars(strip_tags($this->tie_bet));
        $this->status=htmlspecialchars(strip_tags($this->status));
        
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':dealer_card', $this->dealer_card);
        $stmt->bindParam(':client_card', $this->client_card);
        $stmt->bindParam(':normal_bet', $this->normal_bet);
        $stmt->bindParam(':tie_bet', $this->tie_bet);
        $stmt->bindParam(':status', $this->status);
        
        if($stmt->execute()){
            return true;
        }

        return false;
    }
    
    function getBetsForUser($id){
        
        $sql = "SELECT * FROM " . $this->table_name .
                " WHERE user_id = '$id' ORDER BY id ASC";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->execute();
  
        return $stmt;
    }
}
