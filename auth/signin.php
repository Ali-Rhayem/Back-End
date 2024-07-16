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

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

if($_SERVER['REQUEST_METHOD']=="POST"){
    $email = $_POST['email'];
    $password_hash = $_POST['password_hash'];

    // Prepare the statement to check for user credentials
    $stmt = $conn->prepare("SELECT user_id, email, password_hash, username, role FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $email, $hashed_password, $username, $role);
    $stmt->fetch();
    $user_exists = $stmt->num_rows();

    if($user_exists == 0){
        $res['message'] = "User not found";
    } else {
        if (password_verify($password_hash , $hashed_password)){
            $res['status'] = "success";
            $res['user_id'] = $user_id;
            $res['username'] = $username;
            $res['email'] = $email;
            $res['role'] = $role;
        } else {
            $res['status'] = "error";
            $res['message'] = "Incorrect password";
        }
    }
    
    echo json_encode($res);
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
