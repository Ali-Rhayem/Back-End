<?php
require "../connection.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_POST["user_id"];
    $flight_id= $_POST["check_in"];
    $booking_date = $_POST['booking_date'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare('insert into bookings (user_id, flight_id,booking_date,status) values (?,?,?,?)');
    $stmt->bind_param('iiss', $user_id,$flight_id, $booking_date,$status);
    try {
        $stmt->execute();
        echo json_encode(["message" => "new booking is created","status"=>"success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
