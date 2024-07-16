<?php
require "../connection.php";

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Handle POST method for deleting flight
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Get raw JSON data from the request body
    $inputJSON = file_get_contents('php://input');

    // Attempt to decode the JSON data
    $inputData = json_decode($inputJSON, true);

    // Check if JSON was successfully decoded
    if ($inputData === null || json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["error" => "Invalid JSON data"]);
        exit;
    }

    // Extract flight ID from JSON
    $flight_id = $inputData["id"] ?? '';

    // Prepare SQL statement for deletion
    $stmt = $conn->prepare('DELETE FROM flights WHERE flight_id=?');

    // Check if prepare() succeeded
    if ($stmt === false) {
        echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
        exit;
    }

    // Bind parameter
    $stmt->bind_param('i', $flight_id);

    // Execute SQL statement
    try {
        if ($stmt->execute()) {
            echo json_encode(["message" => "Flight deleted", "status" => "success"]);
        } else {
            echo json_encode(["error" => "Execute statement failed: " . $stmt->error]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Exception caught: " . $e->getMessage()]);
    }

    // Close statement
    $stmt->close();
    exit;
}

// Default response for unsupported methods
echo json_encode(["error" => "Wrong request method"]);
?>
