<?php
require "connection.php";

// Allow from any origin (CORS headers)
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

// Check database connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Handle POST request for updating user
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["user_id"], $_POST["username"], $_POST["email"], $_POST["created_at"], $_POST["password_hash"])) {
    // Retrieve POST data for updating user
    $user_id = $_POST["user_id"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $created_at = $_POST["created_at"];
    $password_hash = $_POST["password_hash"];

    // Prepare SQL statement for update
    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, created_at=?, password_hash=? WHERE user_id=?");

    // Check if prepare() succeeded
    if ($stmt === false) {
        echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
        exit;
    }

    // Bind parameters to the prepared statement
    $stmt->bind_param('ssssi', $username, $email, $created_at, $password_hash, $user_id);

    // Execute SQL statement
    try {
        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($stmt->affected_rows > 0) {
                echo json_encode(["message" => "User with ID $user_id updated successfully", "status" => "success"]);
            } else {
                echo json_encode(["message" => "No changes made or user not found", "status" => "failure"]);
            }
        } else {
            echo json_encode(["error" => "Execute statement failed: " . $stmt->error]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Exception caught: " . $e->getMessage()]);
    }

    // Close statement
    $stmt->close();
} 