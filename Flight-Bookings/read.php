<?php
require "../connection.php"; // Ensure this file correctly sets up $conn

header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header('Content-type: application/json; charset=utf-8');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if the connection is established
    if ($conn) {
        // Check if user_id is provided in the query string
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
            
            // Prepare and execute the SQL statement with user_id filter
            $stmt = $conn->prepare('SELECT * FROM bookings WHERE user_id = ?');
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Fetch result rows as an associative array
            $bookings = [];
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
            
            echo json_encode($bookings);
        } else {
            echo json_encode(["error" => "user_id parameter is missing"]);
        }
    } else {
        echo json_encode(["error" => "Database connection failed"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}

$conn->close();
?>
