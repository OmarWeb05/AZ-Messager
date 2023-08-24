<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "youtube";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the form
$recipientId = $_POST['recipient'];
$messageContent = $_POST['message'];

// Get sender's ID from your authentication system
$senderId = 1; // Replace with actual sender ID

// Insert message into the database
$sql = "INSERT INTO messages (sender_id, recipient_id, message) VALUES ('$senderId', '$recipientId', '$messageContent')";

if ($conn->query($sql) === TRUE) {
    echo "Message sent successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
