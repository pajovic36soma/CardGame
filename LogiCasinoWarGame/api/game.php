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
    
    $decoded = JWT::decode($data->jwt, $key, array('HS256'));
    $stake = $data->stake;
    $tie = $data->tie;
    
    
    // check if the normal bet is not empty
    if(!is_numeric($stake)){
        
        http_response_code(400);

        echo json_encode("Enter a numeric value for bet");
        
        return;
    }

    // if tie bet is empty => set to 0
    if(!is_numeric($tie)){
        $tie = 0;
    }
    
    // currently user balance
    $currently_balance = $user->balanceState($decoded->data->id);
    
    // check if user has balance for bet
    if($currently_balance < $stake + $tie){
        http_response_code(400);

        echo json_encode("You don't have enough money for this bet. Your balance is " . $currently_balance . "$");
        
        return;
    }
    // if user has money for bet => tako off the bet
    else{
        $user->updateBalance($decoded->data->id, $currently_balance - $stake - $tie);
    }
    
    // setting bet details
    $bet->user_id = $decoded->data->id;
    $bet->normal_bet = $stake;
    $bet->tie_bet = $tie;
    
    // user balance after removed bet money
    $temp_balance = $user->balanceState($decoded->data->id);
    
    // cards array
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
        
        $bet->status = "lose";
        
        echo json_encode(array(
            "message"=>"You lost " . ($stake + $tie) ."$<br><br>Dealer: " . $cards[$dealer]
                                 . "<br>You: " .  $cards[$client]
        ));
    }
    else if($dealer < $client){
        
        $bet->status = "win";
        
        $cf = 2;
        $user->updateBalance($decoded->data->id, $temp_balance + $cf * $stake);
        
        echo json_encode(array(
            "message"=>"You won " . ($cf * $stake) .  "$<br><br>Dealer: " . $cards[$dealer]
                                 . "<br>You: " .  $cards[$client]
        ));
    }
    else if($dealer == $client && $tie == 0){
        
        $bet->status = "tie";
        
        echo json_encode(array(
            "tie"=>"1",
            "message"=>"Tie<br>Dealer: " . $cards[$dealer]
                                 . "<br>You: " .  $cards[$client],
            "content"=>"<a href='#' id='fold'>Fold</a><br><a href='#' id='war'>War</a><input type='hidden' id='hbet' value='" . $stake . "'/>"
        ));
    }
    else{
        
        $bet->status = "tie win";
        
        $cf = 7.5;
        $user->updateBalance($decoded->data->id, $temp_balance + $stake * $cf * $tie);
        
        echo json_encode(array(
            "message"=>"Tie - You won " . $stake * $cf * $tie . "$<br><br>Dealer: " . $cards[$dealer]
                                 . "<br>You: " .  $cards[$client]
        ));
    }
    
    $bet->create();