<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_POST["id"];
    $check_in = $_POST["check_in"];
    $check_out = $_POST["check_out"];
    $booking_date = $_POST['booking_date'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE hotelbookings SET check_in_date= ?, check_out_date = ?, booking_date = ?, status = ? WHERE hotel_booking_id=?");
    $stmt->bind_param('ssssi',  $check_in, $check_out,  $booking_date, $status, $id);
    
    try {
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "Hotel Booking of id $id got updated", "status" => "success"]);
        } else {
            echo json_encode(["message" => "No changes made or booking not found", "status" => "failure"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error, "status" => "failure"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
