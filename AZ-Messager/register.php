<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "youtube";

// Establish the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate the custom alert
function generateCustomAlert($message) {
    echo "<div class='custom-alert show'>";
    // echo "<span class='close-btn' onclick='this.parentElement.style.display=\"none\";'>×</span>";
    echo "<p>$message</p>";
    echo "</div>";
}



// Registration logic
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $nickname = $_POST['nickname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $number = $_POST['number'];

    // Password length requirements
    $min_password_length = 6; // Change this to your desired minimum length
    $max_password_length = 30; // Change this to your desired maximum length

    // Check password length
    if (strlen($password) < $min_password_length || strlen($password) > $max_password_length) {
        $message = "<p style='width:280px;'>Password must be between $min_password_length and $max_password_length characters.</p>";
        generateCustomAlert($message);
    } else {
        // Check if the username already exists in the database
        $check_username_sql = "SELECT * FROM user WHERE username='$username'";
        $check_result = $conn->query($check_username_sql);

        if ($check_result->num_rows > 0) {
            // Username already exists, show alert
            $message = "<p style='width:280px;'>Username already exists. Choose a different Name.</p>";
            generateCustomAlert($message);
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Generate a random time between 9 AM and 5 PM for allowed login
            $start_time = strtotime("09:00:00");
            $end_time = strtotime("17:00:00");
            $random_login_time = rand($start_time, $end_time);
            $allowed_login_time = date("H:i:s", $random_login_time);

            // Insert the new user with online status (status = 1)
            $insert_user_sql = "INSERT INTO user (username, nickname, email, password, number, allowed_login_time, status) VALUES ('$username', '$nickname', '$email', '$hashedPassword', '$number', '$allowed_login_time', 1)";
            $insert_users_sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";

            if ($conn->query($insert_user_sql) === TRUE && $conn->query($insert_users_sql) === TRUE) {
                echo "<p style='position:absolute; top:90%; left:10px; color:white;font-weight:bold;font-family:Arial;'>Registration successful!</p>";
                header("location: load1.html");
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
}



// Implement offline status update when the user leaves the page (assuming they logged in successfully)
if (isset($_POST['logout'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Update the user's status to offline (status = 0)
    $sql_update_status = "UPDATE user SET status = 0 WHERE username='$username' AND password='$password'";
    $conn->query($sql_update_status);

    echo "<p  style='position:absolute; top:90%; left:10px; color:white;font-weight:bold;font-family:Arial;'>Logged out successfully!</p>";
}


// Retrieve the user data for the admin panel
$sql = "SELECT * FROM user";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="az2.jpg" type="image/x-icon">
    <link rel="stylesheet" href="register.css">
    <title>AZ Messenger</title>
</head>
<body>


<div style="margin-top:70px;" class="wrapper">
<form autocomplete="off" method="post" action="">
        <div class="logo">
            <br>
           <img style="margin-top:-26px;margin-left:-95px;height:40px;width:40px;" src="az2.jpg" alt=""> 
        </div>
        <div style="letter-spacing: 2.5px;" class="text-center mt-4 name">
        REGISTRATION
        </div><br>
        <br>
        <form  class="p-3 mt-3">
     <!-- <audio controls src="mp3indirdur-Evdeki-Saat-Sustum.mp3"></audio> -->

            <div class="form-field d-flex align-items-center">
                <input type="text" name="username" id="userName" placeholder="Username" required>
                </div>

                <div class="form-field d-flex align-items-center">
                <input type="text" name="nickname" id="Nickname" placeholder="Nickname" required>
                </div>

                <div class="form-field d-flex align-items-center">
                <input type="password" name="password" id="reg_password" placeholder="Password" required>
                </div>

                <div class="form-field d-flex align-items-center">
                <input type="text" id="number" name="number" placeholder="Number" required/>
                </div>

                <div class="form-field d-flex align-items-center">
                <input type="email" name="email" id="reg_password" placeholder="Email" required>
                </div>


            <input class="checkbox" style="margin-left:20px;" type="checkbox" onclick="togglePassword('reg_password')"><span style="margin-left:6px;font-weight:800;">Show Password</span> <br>
            <br>
            <input style="width:120px; font-weight:900; letter-spacing:1px;margin-left:14px;" type="submit" name="register" value="REGISTER" class="btn mt-3"></input>
            <a style="font-size:16px; margin-left:9%;" href="index.php">Login Page</a>
            <br>  
        </form>
        <div class="text-center fs-6">    
            <!-- <input type="checkbox" name="admin" value="1"> Admin Privileges -->
        </div>
          </form>
    </div>
    
    
    <script>

        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordInput.style.fontWeight = "bolder";
                passwordInput.style.letterSpacing = "1.5px";
            } else {
                passwordInput.type = "password";
                passwordInput.style.fontWeight = "normal";
                passwordInput.style.letterSpacing = "0px";
            }
        }
    </script>
    
</body>
</html>
