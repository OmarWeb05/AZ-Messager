<?php
session_start();
include('database.inc.php');

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $sql = "SELECT id, password FROM user WHERE BINARY username='$username'";
    $res = mysqli_query($con, $sql);
    $count = mysqli_num_rows($res);

    if ($count == 1) {
        $row = mysqli_fetch_assoc($res);
        $storedHashedPassword = $row['password'];

        if (password_verify($password, $storedHashedPassword)) {
            $_SESSION['UID'] = $row['id'];
            $time = time() + 10;
            mysqli_query($con, "UPDATE user SET last_login=$time WHERE id=" . $_SESSION['UID']);
    
            header('location: load2.php');
            die();
        } else {
            $msg = "Please enter correct login";
        }
    } else {
        $msg = "Please enter correct login";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <title>AZ Messenger</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="index.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="az2.jpg" type="image/x-icon">
</head>
<body>


<div class="wrapper">
<form autocomplete="off" method="post" action="">
        <div class="logo">
            <br>
            <img style="margin-top:-20px;margin-left:-80px;" src="az2.jpg" alt=""> 
        </div>
        <div style="margin-top:-70px;margin-left:94px; letter-spacing: 2px;" class="text-center mt-4 name">
        LOGIN
        </div><br>
        <br>
        <form  class="p-3 mt-3">

            <div class="form-field d-flex align-items-center">
                <input type="text" name="username" id="userName" placeholder="Username" required>
                </div>

                <div class="form-field d-flex align-items-center">
                <input type="password" name="password" id="login_password" placeholder="Password" required>
                </div>
  <br>
                <input style="margin-left:20px;" type="checkbox" onclick="togglePassword('login_password')"><span style="margin-left:6px;font-weight:800;">Show Password</span> <br>    
            <input style="width:120px; font-weight:900; letter-spacing:1px;margin-left:12px;" type="submit" name="submit" value="LOGIN" class="btn mt-3"></input>
            <a style="font-size:13px; margin-left:9%;" href="register.php">Register Page</a>
            <br><br>         
        </form>
        <div class="text-center fs-6">    
            <!-- <input type="checkbox" name="admin" value="1"> Admin Privileges -->
        </div>
          </form>
    </div>


    <div class="krug1"></div>
    <div class="krug2"></div>

    
    <script>
        
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordInput.style.fontWeight = "bold";
                passwordInput.style.letterSpacing = "1px";
            } else {
                passwordInput.type = "password";
                passwordInput.style.fontWeight = "lighter";
                passwordInput.style.letterSpacing = "0px";
            }
        }



        function customAlert(message, duration = 1000) {
        const alertContainer = document.createElement("div");
        alertContainer.classList.add("custom-alert");
        alertContainer.style.animationDuration = (duration / 1000) + "s";
        alertContainer.style.backgroundColor = "red";


        const alertMessage = document.createElement("p");
        alertMessage.textContent = message;

        alertContainer.appendChild(alertMessage);
        document.body.appendChild(alertContainer);

        setTimeout(function () {
            alertContainer.style.animation = "fadeOut 1s ease-in-out forwards";
            setTimeout(function () {
                alertContainer.remove();
            }, 1000);
        }, duration);
    }

    // Example usage of the customAlert function
    <?php
    if (isset($msg)) {
        echo "customAlert('$msg', 3500);"; // Display the alert for 5 seconds (5000ms)
    }
    ?>

    </script>
</body>
</html>
