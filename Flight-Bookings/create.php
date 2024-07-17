<?php
require "../connection.php";

header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header('Content-type: application/json; charset=utf-8');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400'); 
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    error_log("Received data: " . print_r($data, true));

    $user_id = $data["user_id"];
    $flight_id = $data["flight_id"];
    $booking_date = date('Y-m-d H:i:s');
    $status = 'Pending';


    if ($conn) {
        $stmt = $conn->prepare('INSERT INTO bookings (user_id, flight_id, booking_date, status) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('iiss', $user_id, $flight_id, $booking_date, $status);

        try {
            $stmt->execute();
            echo json_encode(["message" => "New booking is created", "status" => "success"]);
        } catch (Exception $e) {
            error_log("Error executing statement: " . $stmt->error);
            echo json_encode(["error" => $stmt->error]);
        }
    } else {
        error_log("Database connection error: " . mysqli_connect_error());
        echo json_encode(["error" => "Database connection failed"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
