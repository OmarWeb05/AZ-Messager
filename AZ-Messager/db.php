<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "youtube";

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>