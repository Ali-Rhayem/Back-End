<?php
require "../connection.php";
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $city = $_POST["city"];
    $country = $_POST["country"];
    $address = $_POST["address"];
    $available_rooms = $_POST['available_rooms'];
    $price_per_night = $_POST['price_per_night'];
    $rate = $_POST["rate"];
    
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

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
