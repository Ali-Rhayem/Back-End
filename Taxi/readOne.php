<?php
require "../connection.php";
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
    $taxiId = $conn->real_escape_string($_GET['id']);
    
    $stmt = $conn->prepare('SELECT * FROM taxis WHERE taxi_id = ?');
    $stmt->bind_param('i', $taxiId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $taxi = $result->fetch_assoc();
        echo json_encode(["taxi" => $taxi]);
    } else {
        echo json_encode(["message" => "Taxi not found"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
