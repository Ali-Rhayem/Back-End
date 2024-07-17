<?php
require "../connection.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get raw JSON data from the request body
    $inputJSON = file_get_contents('php://input');

    // Decode JSON data
    $inputData = json_decode($inputJSON, true);

    // Ensure JSON data was decoded correctly
    if ($inputData === null || json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["error" => "Invalid JSON data"]);
        exit;
    }

    // Extract data from JSON
    $flightId = $inputData['id'] ?? null;
    $flightNumber = $inputData['flight_number'] ?? null;
    $departureAirportId = $inputData['departure_airport_id'] ?? null;
    $arrivalAirportId = $inputData['arrival_airport_id'] ?? null;
    $departureTime = $inputData['departure_time'] ?? null;
    $arrivalTime = $inputData['arrival_time'] ?? null;
    $availableSeats = $inputData['available_seats'] ?? null;
    $price = $inputData['price'] ?? null;

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
