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

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // Check if the connection is established
    if ($conn) {
        // Retrieve user_id from query parameters
        $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;

        if ($user_id === null) {
            echo json_encode(["error" => "Missing user_id parameter"]);
            exit;
        }

        // Prepare and execute the SQL statement with user_id filter
        $stmt = $conn->prepare('SELECT * FROM hotelbookings WHERE user_id = ?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch result rows as an associative array
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = [
                'hotel_booking_id' => $row['hotel_booking_id'],
                'user_id' => $row['user_id'],
                'hotel_id' => $row['hotel_id'],
                'check_in_date' => $row['check_in_date'],
                'check_out_date' => $row['check_out_date'],
                'booking_date' => $row['booking_date'],
                'status' => $row['status']
            ];
        }

        echo json_encode($bookings);
    } else {
        error_log("Database connection error: " . mysqli_connect_error());
        echo json_encode(["error" => "Database connection failed"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}

$conn->close();
?>
