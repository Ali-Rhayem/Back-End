<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_POST["user_id"];
    $taxi_id = $_POST["taxi_id"];
    $pickup_location = $_POST["pickup_location"];
    $dropoff_location = $_POST["dropoff_location"];
    $pickup_date = date('Y-m-d H:i:s', strtotime($_POST['pickup_date']));
    $booking_date = date('Y-m-d H:i:s'); // Use current timestamp
    $status = 'Pending'; // Default status

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare('INSERT INTO TaxiBookings (user_id, taxi_id, pickup_location, dropoff_location, booking_date, pickup_date, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('iisssss', $user_id, $taxi_id, $pickup_location, $dropoff_location, $booking_date, $pickup_date, $status);
    
    try {
        $stmt->execute();
        echo json_encode(["message" => "New taxi booking is created", "status" => "success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
