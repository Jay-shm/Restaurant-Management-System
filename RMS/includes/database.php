<?php
//database.php

$host = "localhost";
$user = "root";
$pass = "";
$db = "RMS";

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
    die("connection_failed: " . $conn->connect_error );
}

?>