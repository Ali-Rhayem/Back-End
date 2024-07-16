require "../connection.php";

header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header('Content-type: application/json; charset=utf-8');
<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    // Log incoming data
    error_log("Received data: " . print_r($data, true));

    $user_id = $data["user_id"];
    $taxi_id = $data["taxi_id"];
    $pickup_location = $data["pickup_location"];
    $dropoff_location = $data["dropoff_location"];
    $pickup_date = date('Y-m-d H:i:s', strtotime($data['pickup_date']));
    $booking_date = date('Y-m-d H:i:s');
    $status = 'Pending';

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare('INSERT INTO TaxiBookings (user_id, taxi_id, pickup_location, dropoff_location, booking_date, pickup_date, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('iisssss', $user_id, $taxi_id, $pickup_location, $dropoff_location, $booking_date, $pickup_date, $status);
    
    try {
        $stmt->execute();
        echo json_encode(["message" => "New taxi booking is created", "status" => "success"]);
    } catch (Exception $e) {
        error_log("Error executing statement: " . $stmt->error);
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}