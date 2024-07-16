<?php
require "../connection.php";
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header('Content-type: application/json; charset=utf-8');

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $taxi_id = $_POST["taxi_id"];
    $name = $_POST["name"];
    $phone_number = $_POST["phone_number"];
    $city = $_POST["city"];
    $country = $_POST["country"];
    $available_cars = $_POST['available_cars'];
    $price_per_km = $_POST['price_per_km'];
    $rate = $_POST["rate"];
    
    $stmt = $conn->prepare('insert into taxis (name, phone_number,city,country,available_cars,price_per_km,rate) values (?,?,?,?,?,?,?)');
    $stmt->bind_param('ssssiii', $name,$phone_number ,$city, $country, $available_cars,$price_per_km,$rate);
    try {
        $stmt->execute();
        echo json_encode(["message" => "new taxi is added","status"=>"success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
