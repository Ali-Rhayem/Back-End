<?php
require "../connection.php";
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $stmt = $conn->prepare('SELECT * FROM hotels');
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $hotels[] = $row;
        }
        echo json_encode(["hotels" => $hotels]);
    } else {
        echo json_encode(["message" => "no records were found"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
