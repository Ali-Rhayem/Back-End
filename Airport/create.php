<?php
require "../connection.php";

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST["name"];
    $city = $_POST["city"];
    $country = $_POST["country"];
    $code = $_POST["code"];
   

    $stmt = $conn->prepare('INSERT INTO airports (name, city, country, code) 
                           VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $name, $city, $country, $code);
    
    try {
        $stmt->execute();
        echo json_encode(["message" => "New airport added", "status" => "success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
    
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
