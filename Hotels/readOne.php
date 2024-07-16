<?php
require "../connection.php";
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
    $hotelId = $conn->real_escape_string($_GET['id']);
    
    $stmt = $conn->prepare('SELECT * FROM hotels WHERE hotel_id = ?');
    $stmt->bind_param('i', $hotelId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $hotel = $result->fetch_assoc();
        echo json_encode(["hotel" => $hotel]);
    } else {
        echo json_encode(["message" => "Hotel not found"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
