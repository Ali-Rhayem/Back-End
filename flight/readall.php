<?php
require "connection.php";

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

// Handle GET method for reading all flights
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    // Prepare SQL statement for retrieving all flights
    $sql = "SELECT * FROM flights";

    // Execute SQL statement
    $result = $conn->query($sql);

    // Check if query was successful
    if ($result === false) {
        echo json_encode(["error" => "Query execution failed: " . $conn->error]);
        exit;
    }

    // Fetch all rows from the result set
    $flights = [];
    while ($row = $result->fetch_assoc()) {
        $flights[] = $row;
    }

    // Close result set
    $result->close();

    // Return flights data as JSON
    echo json_encode($flights);
    exit;
}