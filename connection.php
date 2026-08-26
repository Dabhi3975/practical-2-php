<?php

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "registration_db";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {

    echo "Something went wrong. Please try again later.";
    exit;

}

?>