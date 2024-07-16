<?php
require '../connection.php';

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }

    exit(0);
}

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Get the JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Debugging statement
    error_log(print_r($input, true));
    
    if (isset($input['id']) && !empty($input['id'])) {
        $id = $input['id'];

        $stmt = $conn->prepare('DELETE FROM users WHERE user_id = ?');
        $stmt->bind_param('i', $id);
        
        try {
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo json_encode(["message" => "User successfully deleted"]);
            } else {
                echo json_encode(["error" => "User not found or already deleted"]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => $stmt->error]);
        }
    } else {
        echo json_encode(["error" => "Invalid or missing user ID"]);
    }
} else {
    echo json_encode(["error" => "Wrong method"]);
}
?>
