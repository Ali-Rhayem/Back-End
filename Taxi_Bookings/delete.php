<?php
require '../connection.php';
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
    $id =$_POST['id'];
    $stmt = $conn -> prepare('delete from taxibookings where taxi_booking_id=?');
    $stmt->bind_param('i',$id);
    try{
        $stmt-> execute();
        echo json_encode(["message" => "taxi booking is successfuly deleted"]);
    }
    catch(Exception $e){
        echo json_encode(["error" => $stmt->error]);
    }
}else{
    echo json_encode(["error" => "wrong method"]);
}