<?php
require "../connection.php";
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST["name"];
    $city = $_POST["city"];
    $country = $_POST["country"];
    $address = $_POST["address"];
    $available_rooms = $_POST['available_rooms'];
    $price_per_night = $_POST['price_per_night'];
    $rate = $_POST["rate"];

    $stmt = $conn->prepare('insert into hotels (name,city,country,address,available_rooms,price_per_night,rate) 
values (?,?,?,?,?,?,?);');
    $stmt->bind_param('ssssiii', $name, $city, $country, $address, $available_rooms,$price_per_night,$rate);
    try {
        $stmt->execute();
        echo json_encode(["message" => "new hotel is added","status"=>"success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
    
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
