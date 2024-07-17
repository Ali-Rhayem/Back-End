<?php
$servername = "localhost";
$username = "root";
$password = '';
$db_name = 'flightmanagementsystem';
$port = 4306;

$conn = new mysqli($servername, $username, $password, $db_name,$port );

if ($conn->connect_error) {
    die('connection failed' . $conn->connect_error);
}