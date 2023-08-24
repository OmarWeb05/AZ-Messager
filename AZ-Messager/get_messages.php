<?php
include("database.inc.php");

$historySql = "SELECT * FROM messages ORDER BY id DESC";
$historyResult = mysqli_query($con, $historySql);

while ($row = mysqli_fetch_assoc($historyResult)) {
    echo '<div class="message"><strong>' . $row['sender'] . ': </strong>' . $row['message'] . '</div>';
}
?>
