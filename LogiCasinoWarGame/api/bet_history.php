<?php

    header("Access-Control-Allow-Origin: http://localhost/LogiCasinoWarGame/");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once 'config/database.php';
    include_once 'objects/bet.php';

    $database = new Database();
    $db = $database->getConnection();

    $bet = new Bet($db);
    
    include_once 'config/core.php';
    include_once 'libs/php-jwt-master/src/BeforeValidException.php';
    include_once 'libs/php-jwt-master/src/ExpiredException.php';
    include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
    include_once 'libs/php-jwt-master/src/JWT.php';
    use \Firebase\JWT\JWT;
    
    $data = json_decode(file_get_contents("php://input"));
    
    $decoded = JWT::decode($data->jwt, $key, array('HS256'));
    
    $stmt = $bet->getBetsForUser($decoded->data->id);
    $num = $stmt->rowCount();

    if($num>0){

        $bet_arr = array();
        $bet_arr["bets"]=array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $bet_item=array(
                "id" => $id,
                "user_id" => $user_id,
                "dealer_card" => $dealer_card,
                "client_card" => $client_card,
                "normal_bet" => $normal_bet,
                "tie_bet" => $tie_bet,
                "status" => $status
            );

            array_push($bet_arr["bets"], $bet_item);
        }

        http_response_code(200);

        echo json_encode($bet_arr);
    }
    
    else{
  
        http_response_code(400);

        echo json_encode(
            array("No bets found")
        );
    }