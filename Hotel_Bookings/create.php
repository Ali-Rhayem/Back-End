<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_POST["user_id"];
    $hotel_id = $_POST["hotel_id"];
    $check_in = $_POST["check_in"];
    $check_out = $_POST["check_out"];
    $booking_date = $_POST['booking_date'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare('insert into hotelbookings (user_id, hotel_id,check_in_date,check_out_date,booking_date,status) values (?,?,?,?,?,?)');
    $stmt->bind_param('iissss', $user_id,$hotel_id,$check_in, $check_out, $booking_date,$status);
    try {
        $stmt->execute();
        echo json_encode(["message" => "new booking is created","status"=>"success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
