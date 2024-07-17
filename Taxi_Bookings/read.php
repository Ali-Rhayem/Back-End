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

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // Check if user_id parameter is provided
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        
        // SQL query to fetch taxi bookings for a specific user_id
        $sql = "SELECT * FROM TaxiBookings WHERE user_id = $user_id";
    } else {
        // Default SQL query to fetch all taxi bookings
       return 'error ';
    }

    // Attempt to execute the query
    $result = $conn->query($sql);

    if ($result) {
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = [
                'taxi_booking_id' => $row['taxi_booking_id'],
                'user_id' => $row['user_id'],
                'taxi_id' => $row['taxi_id'],
                'pickup_location' => $row['pickup_location'],
                'dropoff_location' => $row['dropoff_location'],
                'booking_date' => $row['booking_date'],
                'pickup_date' => $row['pickup_date'],
                'status' => $row['status']
            ];
        }

        echo json_encode($bookings);
    } else {
        echo json_encode(["error" => "Failed to fetch taxi bookings"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}

$conn->close();
?>
