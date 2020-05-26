<?php

    header("Access-Control-Allow-Origin: http://localhost/LogiCasinoWarGame/");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once 'config/database.php';
    include_once 'objects/bet.php';
    include_once 'objects/user.php';

    $database = new Database();
    $db = $database->getConnection();

    $bet = new Bet($db);
    $user = new User($db);
    
    include_once 'config/core.php';
    include_once 'libs/php-jwt-master/src/BeforeValidException.php';
    include_once 'libs/php-jwt-master/src/ExpiredException.php';
    include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
    include_once 'libs/php-jwt-master/src/JWT.php';
    use \Firebase\JWT\JWT;
    
    $data = json_decode(file_get_contents("php://input"));
    
    $stake = $data->stake;
    
    $decoded = JWT::decode($data->jwt, $key, array('HS256'));
    
    
    // user balance after removed first initial bet
    $currently_balance = $user->balanceState($decoded->data->id);
    
    // check if user has enough balance for one more initial war bet
    if($currently_balance < $stake){
        http_response_code(400);

        echo json_encode("You don't have enough money for War. Your balance is " . $currently_balance . "$");
        
        return;
    }
    // take off money for war bet
    else{
        $user->updateBalance($decoded->data->id, $currently_balance - $stake);
    }
    
    // setting bet details
    $bet->user_id = $decoded->data->id;
    $bet->normal_bet = $stake;
    
    // user balance after removed war bet money
    $temp_balance = $user->balanceState($decoded->data->id);
    
    $cards = array("2"=>"2",
                       "3"=>"3",
                       "4"=>"4",
                       "5"=>"5",
                       "6"=>"6",
                       "7"=>"7",
                       "8"=>"8",
                       "9"=>"9",
                       "10"=>"T",
                       "11"=>"J",
                       "12"=>"Q",
                       "13"=>"K",
                       "14"=>"A");
        
    $dealer = rand(2,14); $bet->dealer_card = $cards[$dealer];
    $client = rand(2,14); $bet->client_card = $cards[$client];
    
    if($dealer > $client){
        
        $bet->status = "war lose";
        
        echo json_encode(array(
            "message"=>"You lost " . (2 * $stake) ."$<br><br>Dealer: " . $cards[$dealer] . " X X X"
                                 . "<br>You: " .  $cards[$client]
        ));
    }
    else{
        
        $bet->status = "war win";
        
        $cf = 3;
        $user->updateBalance($decoded->data->id, $temp_balance + $cf * $stake);
        
        echo json_encode(array(
            "message"=>"You won " . ($cf * $stake) ."$<br><br>Dealer: " . $cards[$dealer] . " X X X"
                                 . "<br>You: " .  $cards[$client]
        ));
    }
    
    $bet->create();
