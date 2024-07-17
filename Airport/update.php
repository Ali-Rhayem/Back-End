<?php
require "../connection.php";
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $city = $_POST["city"];
    $country = $_POST["country"];
    $code = $_POST["code"];
    
    $stmt = $conn->prepare("UPDATE airports SET name = ?, city = ?, country = ?, code = ? WHERE airport_id = ?");
    $stmt->bind_param('ssssi', $name, $city, $country, $code,$id);
    
    try {
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "Airport of id $id got updated", "status" => "success"]);
        } else {
            echo json_encode(["message" => "No changes made or airport not found", "status" => "failure"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error, "status" => "failure"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>