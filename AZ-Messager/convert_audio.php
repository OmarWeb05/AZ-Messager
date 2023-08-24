<?php
if (isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
    $tempPath = $_FILES['audio']['tmp_name'];
    
    // Save the audio file
    $audioFolder = '/';
    $audioFilename = uniqid() . '.webm';
    $audioPath = $audioFolder . $audioFilename;
    move_uploaded_file($tempPath, $audioPath);
    
    // Perform the necessary audio-to-text conversion here
    // Replace this with your actual conversion process
    $convertedText = "Voice";
    
    // Insert into database
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'youtube';

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $sql = "INSERT INTO messages (filename, converted_text) VALUES ('$audioFilename', '$convertedText')";

    if ($conn->query($sql) === TRUE) {
        echo '<p style="display: none;" id="convertedText">' . $convertedText . '</p>'; // Hide by default
    } else {
        echo "Error inserting into database: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Error uploading audio.";
}
?>
