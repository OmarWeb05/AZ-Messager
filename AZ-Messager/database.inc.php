<?php
$host = "localhost"; 
$username = "root";  
$password = ""; 
$database = "youtube"; 

// Create connection
$con = new mysqli($host, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

echo "";
?>
