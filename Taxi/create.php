<?php
require "../connection.php";
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
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
