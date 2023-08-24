<?php
session_start();
include('database.inc.php');

if (isset($_SESSION['UID'])) {
    $uid = $_SESSION['UID'];
    $sql = "SELECT username FROM user WHERE id = '$uid'";
    $res = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($res);
    echo $row['username'];
} else {
    echo "User not found";
}
?>
