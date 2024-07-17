<?php 
require "../connection.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $id = $_GET['id'];
    $stmt = $conn->prepare('select * from airports where airport_id = ?;');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $airport = $result->fetch_assoc();
        echo json_encode(["airports" => $airport]);
    } else {
        echo json_encode(["message" => "no records were found"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}