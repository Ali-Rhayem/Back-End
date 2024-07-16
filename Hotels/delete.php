<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

require '../connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Decode the JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if 'id' exists in the decoded data
    if (!isset($data['id'])) {
        echo json_encode(["error" => "Missing hotel id"]);
        exit;
    }

    $id = $data['id']; 
    $stmt = $conn->prepare('DELETE FROM hotels WHERE hotel_id = ?');
    $stmt->bind_param('i', $id);
    try {
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "Hotel successfully deleted"]);
        } else {
            echo json_encode(["error" => "Hotel not found or already deleted"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Wrong method"]);
}
?>
