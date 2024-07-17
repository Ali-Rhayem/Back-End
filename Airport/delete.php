<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

require '../connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   
    $data = json_decode(file_get_contents('php://input'), true);

   
    if (!isset($data['id'])) {
        echo json_encode(["error" => "Missing airport id"]);
        exit;
    }

    $id = $data['id']; 
    $stmt = $conn->prepare('DELETE FROM airports WHERE airport_id = ?');
    $stmt->bind_param('i', $id);
    try {
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "Airport successfully deleted"]);
        } else {
            echo json_encode(["error" => "Airport not found or already deleted"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Wrong method"]);
}
?>
