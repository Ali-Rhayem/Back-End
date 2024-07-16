<?php
require "../connection.php";
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["hotelName"];
    $city = $_POST["hotelCity"];
    $country = $_POST["hotelCountry"];
    $address = $_POST["hotelAddress"];
    $available_rooms = $_POST['hotelAvailableRooms'];
    $price_per_night = $_POST['hotelPricePerNight'];
    $rate = $_POST["hotelRate"];
    
    $stmt = $conn->prepare("UPDATE hotels SET name = ?, city = ?, country = ?, address = ?, available_rooms = ?, price_per_night = ?, rate = ? WHERE hotel_id = ?");
    $stmt->bind_param('ssssiiii', $name, $city, $country, $address, $available_rooms, $price_per_night, $rate, $id);
    
    try {
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "Hotel of id $id got updated", "status" => "success"]);
        } else {
            echo json_encode(["message" => "No changes made or hotel not found", "status" => "failure"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error, "status" => "failure"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
