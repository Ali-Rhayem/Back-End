<?php
require "../connection.php";

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST["hotelName"] ?? null;
    $city = $_POST["hotelCity"] ?? null;
    $country = $_POST["hotelCountry"] ?? null;
    $address = $_POST["hotelAddress"] ?? null;
    $available_rooms = $_POST['hotelAvailableRooms'] ?? null;
    $price_per_night = $_POST['hotelPricePerNight'] ?? null;
    $rate = $_POST["hotelRate"] ?? null;

    if ($name && $city && $country && $address && $available_rooms && $price_per_night && $rate) {
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
        echo json_encode(["error" => "Missing required fields"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
