<?php
session_start();

if (!isset($_SESSION['UID'])) {
    // User is not logged in, redirect to login page
    header('location: login.php');
    die();
}
?>
