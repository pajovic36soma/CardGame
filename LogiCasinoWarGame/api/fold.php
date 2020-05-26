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
    
    // user balance after removed the bet money
    $temp_balance = $user->balanceState($decoded->data->id);
    
    
    // adding half of the bet which means he lost half of the bet
    $user->updateBalance($decoded->data->id, $temp_balance + $stake / 2);
    
    // setting bet details
    $bet->user_id = $decoded->data->id;
    $bet->normal_bet = $stake;
    $bet->status = "fold";
    $bet->create();
    
    echo json_encode(array(
            "message"=>"You fold. You lost  " . ($stake / 2) . "$"
        ));
