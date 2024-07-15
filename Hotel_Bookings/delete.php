<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id =$_POST['id'];
    $stmt = $conn -> prepare('delete from hotelbookings where hotel_booking_id=?');
    $stmt->bind_param('i',$id);
    try{
        $stmt-> execute();
        echo json_encode(["message" => "hotel booking is successfuly deleted"]);
    }
    catch(Exception $e){
        echo json_encode(["error" => $stmt->error]);
    }
}else{
    echo json_encode(["error" => "wrong method"]);
}