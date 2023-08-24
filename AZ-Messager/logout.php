<?php
// logout.php

session_start();
session_destroy();

// Redirect the user back to the login page or any other desired page after logout
header('location:index.php');
exit();
?>
