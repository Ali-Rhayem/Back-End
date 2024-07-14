<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $phone_number = $_POST["phone_number"];
    $city = $_POST["city"];
    $country = $_POST["country"];
    $available_cars = $_POST['available_cars'];
    $price_per_km = $_POST['price_per_km'];
    $rate = $_POST["rate"];
    
    $stmt = $conn->prepare("UPDATE taxis SET name = ?, phone_number= ?, city = ?, country = ?, available_cars = ?, price_per_km = ?, rate = ? WHERE taxi_id = ?");
    $stmt->bind_param('ssssiii', $name,$phone_number, $city, $country, $available_cars, $price_per_km,$rate, $id);
    
    try {
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "taxi of id $id got updated", "status" => "success"]);
        } else {
            echo json_encode(["message" => "No changes made or taxi not found", "status" => "failure"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error, "status" => "failure"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
