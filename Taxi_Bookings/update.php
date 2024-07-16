<?php
require "../connection.php";
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header('Content-type: application/json; charset=utf-8');

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_POST["id"];
    $user_id = $_POST["user_id"];
    $taxi_id = $_POST["taxi_id"];
    $pickup_location = $_POST["pickup_location"];
    $dropoff_location = $_POST["dropoff_location"];
    $booking_date = date('Y-m-d H:i:s', strtotime($_POST['booking_date'])); 
    $pickup_date = date('Y-m-d H:i:s', strtotime($_POST['pickup_date']));
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE taxibookings SET user_id = ?, taxi_id = ?, pickup_location = ?, dropoff_location = ?, booking_date = ?, pickup_date = ?, status = ? WHERE taxi_booking_id = ?");
    $stmt->bind_param('iisssssi', $user_id, $taxi_id, $pickup_location, $dropoff_location, $booking_date, $pickup_date, $status, $id);
    
    try {
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "Taxi booking with ID $id updated successfully", "status" => "success"]);
        } else {
            echo json_encode(["message" => "No changes made or taxi booking not found", "status" => "failure"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error, "status" => "failure"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
