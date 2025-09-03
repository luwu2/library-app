<?php

$host = "localhost";
$user = "root";
$pass = "F0or1:z22dU4y";
$dbname = "library_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>