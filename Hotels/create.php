<?php
require "../connection.php";

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST["hotelName"];
    $city = $_POST["hotelCity"];
    $country = $_POST["hotelCountry"];
    $address = $_POST["hotelAddress"];
    $available_rooms = $_POST['hotelAvailableRooms'];
    $price_per_night = $_POST['hotelPricePerNight'];
    $rate = $_POST["hotelRate"];

    $stmt = $conn->prepare('INSERT INTO hotels (name, city, country, address, available_rooms, price_per_night, rate) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssiii', $name, $city, $country, $address, $available_rooms, $price_per_night, $rate);
    
    try {
        $stmt->execute();
        echo json_encode(["message" => "New hotel added", "status" => "success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
    
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
