<?php
require "../connection.php";

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Handle GET method for reading a single flight
if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['id'])) {
    $flightId = $conn->real_escape_string($_GET['id']);
    
    // Prepare SQL statement for retrieving the specific flight
    $sql = "SELECT * FROM flights WHERE flight_id = '$flightId'";

    // Execute SQL statement
    $result = $conn->query($sql);

    // Check if query was successful
    if ($result === false) {
        echo json_encode(["error" => "Query execution failed: " . $conn->error]);
        exit;
    }

    // Check if flight was found
    if ($result->num_rows > 0) {
        $flight = $result->fetch_assoc();
        echo json_encode(["flight" => $flight]);
    } else {
        echo json_encode(["error" => "Flight not found"]);
    }

    // Close result set
    $result->close();
    exit;
} else {
    echo json_encode(["error" => "Invalid request"]);
    exit;
}
?>
