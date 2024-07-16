<?php
require "../connection.php";
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flightId = $_POST['id'];
    $flightNumber = $_POST['flight_number'];
    $departureAirportId = $_POST['departure_airport_id'];
    $arrivalAirportId = $_POST['arrival_airport_id'];
    $departureTime = $_POST['departure_time'];
    $arrivalTime = $_POST['arrival_time'];
    $availableSeats = $_POST['available_seats'];
    $price = $_POST['price'];

    // Ensure all parameters are received
    if (isset($flightId, $flightNumber, $departureAirportId, $arrivalAirportId, $departureTime, $arrivalTime, $availableSeats, $price)) {
        $stmt = $conn->prepare('UPDATE flights SET flight_number = ?, departure_airport_id = ?, arrival_airport_id = ?, departure_time = ?, arrival_time = ?, available_seats = ?, price = ? WHERE flight_id = ?');
        $stmt->bind_param('siissisi', $flightNumber, $departureAirportId, $arrivalAirportId, $departureTime, $arrivalTime, $availableSeats, $price, $flightId);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Flight updated successfully"]);
        } else {
            echo json_encode(["error" => "Failed to update flight: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Invalid input parameters"]);
    }

    $conn->close();
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
