<?php
session_start();
include('database.inc.php');


if (!isset($_SESSION['UID'])) {
    header('Location: index.php');
    exit();
}


// Retrieve user data from the database
$user_id = $_SESSION['UID'];

$sql = "SELECT * FROM user WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result->num_rows === 1) {
    $userData = mysqli_fetch_assoc($result);
    $lastLoginTimestamp = $userData['last_login'];

    $onlineThreshold = 5 * 60; // 5 minutes in seconds
    $currentTime = time();

    if (($currentTime - $lastLoginTimestamp) <= $onlineThreshold) {
        $status = 'Online';
    } else {
        $status = 'Offline';
    }
} else {
    // Handle no user found error
}


if (isset($_POST['confirmRemove'])) {
    // Display a confirmation message and allow the user to confirm or cancel
    echo "<div style='background-color: rgba(0, 0, 0, 0.8); color: white; position: fixed; top: 0; left: 0; height: 100%; width: 100%; display: flex; align-items: center; justify-content: center;' class='div'>";
    echo "<div style='background-color: ; border-radius: 10px; padding: 20px;transform:scale(1.90); box-shadow: 0px 2px 5px black;'>";
    echo "<p style='font-size: 18px;color:white;font-weight:600; text-align:center;font-family:arial; margin-bottom:25px;'>You want to remove your account?</p>";
    echo "<form method='post'>";
    echo "<button style='background-color: #e74c3c; cursor:grab; color: white; padding: 14px 20px; border: none; border-radius: 5px; margin-right: 10px;' type='submit' name='removeConfirmed'>Yes, Delete</button>";
    echo "<button style='background-color: #3498db;  cursor:grab; color: white; padding: 14px 30px; border: none; border-radius: 5px;' type='submit' name='cancelRemove'>Cancel</button>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
    exit(); // Exit here, don't proceed to header() immediately
}


if (isset($_POST['removeConfirmed'])) {
    $user_id = $_SESSION['UID'];

    // Perform the account removal process, including deleting records and performing any cleanup
    $sql_user = "DELETE FROM user WHERE id = ?";
    $stmt_user = mysqli_prepare($con, $sql_user);
    mysqli_stmt_bind_param($stmt_user, 'i', $user_id);

    $sql_users = "DELETE FROM users WHERE id = ?";
    $stmt_users = mysqli_prepare($con, $sql_users);
    mysqli_stmt_bind_param($stmt_users, 'i', $user_id);

    if (mysqli_stmt_execute($stmt_user) && mysqli_stmt_execute($stmt_users)) {
        mysqli_stmt_close($stmt_user);
        mysqli_stmt_close($stmt_users);

        // Logout the user and redirect to a confirmation page
        session_destroy();
        header("Location: load1.html");
        exit();
    } else {
        echo "Failed to remove account: " . mysqli_error($con);
    }
}


if (isset($_POST['cancelRemove'])) {
    // Redirect the user back to their profile or another appropriate page
    header("Location: load2.php");
    exit();
}


// Handle image upload
if (isset($_POST['upload'])) {
    $file = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "image/" . $file;

    if (move_uploaded_file($tempname, $folder)) {
        $sql = "UPDATE user SET filename = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $file, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<h3 style='display:none;'>Image uploaded and record updated successfully!</h3>";
        } else {
            echo "<h3 style='display:none;'>Failed to update record with image upload!</h3>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<h3 style='display:none;'>Failed to move the uploaded image!</h3>";
    }
}


// Function to generate the custom alert
function generateCustomAlert($message) {
    echo "<div style='margin-top:56px;width:420px;height:50px;' class='custom-alert show'>";
    echo "<p>$message</p>";
    echo "</div>";
}




if (isset($_POST['ChangePass'])) {
    // Validate user input
    $newPassword = mysqli_real_escape_string($con, $_POST['newPassword']);
    $minPasswordLength = 6;
    $maxPasswordLength = 20;

    if (strlen($newPassword) < $minPasswordLength || strlen($newPassword) > $maxPasswordLength) {
        $message = "Password length should be between $minPasswordLength and $maxPasswordLength characters.";
        generateCustomAlert($message);
    } else {
        $id = $_SESSION['UID']; // Use the session ID instead of POST

        // Hash the new password
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the 'user' table
        $sql_update_user = "UPDATE user SET password = ? WHERE id = ?";
        $stmt_update_user = mysqli_prepare($con, $sql_update_user);
        mysqli_stmt_bind_param($stmt_update_user, 'si', $hashedNewPassword, $id);

        // Update the password in the 'users' table
        $sql_update_users = "UPDATE users SET password = ? WHERE id = ?";
        $stmt_update_users = mysqli_prepare($con, $sql_update_users);
        mysqli_stmt_bind_param($stmt_update_users, 'si', $hashedNewPassword, $id);

        if (mysqli_stmt_execute($stmt_update_user) && mysqli_stmt_execute($stmt_update_users)) {
            mysqli_stmt_close($stmt_update_user);
            mysqli_stmt_close($stmt_update_users);

            // Password updated successfully
            echo "<p>Password updated successfully</p>"; // Debug output

            // Redirect to the desired page after update
            header("Location: load6.html");
            exit();
        } else {
            // Error updating password
            echo "Error updating password: " . mysqli_error($con); // Debug output
        }
    }
}




if (isset($_POST["submit2"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Basic validation
    $username = stripslashes($username);

    // Using prepared statement to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $userRow = $result->fetch_assoc();

        // Verify the entered password against the hashed password stored in the database
        if (password_verify($password, $userRow["password"])) {
            // Successful login
            $_SESSION["username"] = $username;
            header("location: load7.html");
        } else {
            // Invalid password
            echo "<p style='display:none;'>Invalid password.</p>";
        }
    } else {
        // Invalid username
        echo "<p style='display:none;'>Invalid username.</p>";
    }

    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <title>AZ Messenger</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link style="height:100px;width:100px;" rel="shortcut icon" href="az2.jpg" type="image/x-icon">
    <link rel="stylesheet" href="user.css">
    <style>

          .login-container {
            position: absolute;
            background-color: lightslategrey;
            display:none;
            transform:scale(0.75);
            opacity:0;
            width: 375px;
            margin-top:99px;
            padding: 14px;
            transition:0.6s;
            border-radius: 12px;
            text-align: center;
        }

        .login-title {
            font-size: 20px;
            margin-bottom: 10px;
            color: white;
        }

        .login-form {
            margin-top: 10px;
        }

        .login-form label {
            display: block;
            margin-bottom: 8px;
            text-align: left;
            font-weight: bold;
            color: #555;
        }

        .login-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f5f5f5;
            color: #333;
            font-size: 14px;
            outline:none;
        }

        .login-form input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 0;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .login-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .login-form input::placeholder {
            color: #aaa;
        }

    </style>
</head>
<body>

	<div style="display:none;" id="display-image">
		<?php

		$query = "SELECT * FROM user WHERE id='$user_id'";
		$result = mysqli_query($con, $query);

		while ($data = mysqli_fetch_assoc($result)) {
			$filename = $data['filename'];
			$id = $data['id'];
			?>
		<?php
		}
		?>
	</div> 


    <p id="txt"></p>    
    <div class="boxsetting">
    <div style="color:white; font-weight:bolder; margin-top:1px;" onclick="closebox()" class="close">x</div>
    
<form method="post" action="user.php">
<button style="position:absolute;top:20px;left:20px;padding:5px;font-size:14.5px;box-shadow: 1px 1px 12px black; background-color:red;color:white;border-radius:6px;border:none;"  type="submit" name="confirmRemove">Delete Account</button>
</form>

<p style="position:absolute;top:70px;left:20px;cursor: pointer;padding:5px;font-size:13px;box-shadow: 1px 1px 12px black; background-color:white;;color:black;border-radius:6px;font-weight:600;font-family:arial;border:none;" class="cpass" onclick="openChangePass()">Change Password</p>

<div style="height:250px;width:260px; position:absolute; border-radius:10px; left:50px;top:140px;" class="formChangePass">
  <form id="passwordForm" autocomplete="off" style="display:none;" method="post" action="user.php">
           <div class="mb-3">
                <br>
                <br>
                <input style="width:170px;margin-left:-25px;" id="confirmPassword1" class="form-control" placeholder="New Password" type="password" name="newPassword" required> <br>
                <input style="width:170px;margin-left:-25px;" id="confirmPassword2" class="form-control" placeholder="Confirm Password" name="newPassword"  type="password">
                <input style="margin-top:15px;margin-left:-20px;" id="input" type="checkbox" onclick="togglePassword()"><span style="color:white;font-family:Arial;margin-left:5px;width:200px;">Show Password</span>
            </div> 
            <button style="margin-top:7px;padding:-10px; background-color:green;" type="submit" name="ChangePass" class="btn btn-success">Save</button>
            <br>
            <br>
            <p class="s2" style="color:white; font-size:13px; padding:3px;text-align:center;font-weight:900;font-family:arial;"><?php echo $userData['email']; ?></p>
           <p class="s1" style="color:white; display:none; opacity:0; font-size:13px; padding:3px;text-align:center;font-weight:900;font-family:arial;">Password: <?php echo $userData['password']; ?> 
        </form>
</div>
    </div>



<div class="boxinfo">
<div onclick="bagla()" class="close">
    <p>x</p>
    </div>
        <div id="content">
		<form method="POST" action="" enctype="multipart/form-data">
			<div class="form-group">
                <img class="showimg" src="image/<?php echo $filename; ?>" alt="<?php echo $filename; ?>">
                <br>
             <br>
                <input style="margin-left:-45px;width:140px;margin-bottom:5px; text-align:center;align-items:center;" class="form-control" type="file" name="uploadfile">
			</div>
			<div class="form-group">
				<button style="margin-left:-45px;width:140px;margin-bottom:5px; background-color:green; border:none;"  class="btn btn-primary" type="submit2" onclick="confirmFormSubmission()" name="upload">UPLOAD</button>
			</div>
		</form>
        <p class="onlstatus" style="color:white; top:92%; left:15px; width:auto; font-size:13px; font-weight:700;letter-spacing:2px; position:absolute; background-color:green;padding:4px;font-family:Arial;border-radius:9px; text-align:center; align-items:center;"><span></span><?php  echo $status; ?></p>
        <div style="transition:1s;" onclick="opensett()" class="fa fa-cog cog2"></div>
        <h2 style="position:absolute; top:-5px;font-size:10px;left:10px;color:white;letter-spacing:1px;" class="mt-3">ID: <?php echo $userData['id']; ?> </h2>
	</div>
</div>


      <div class="container">
      <div style="color:white; position:absolute; cursor:grab; border-radius:7px; padding:10px; padding-top:15px; box-shadow:0px 1px 3px gray,inset 1px 1px 18px black; align-items:center; text-align:center;" onclick="search()" name="" class="fa fa-search"></div>
      <p style="cursor:grab; position:absolute;top:90%;left:15px;display:none;" class="openchat" onclick="openGCHAT()"><img style="height:50px;width:50px;" src="world copy.webp" alt="" srcset=""></p>
      <form autocomplete="off" style="position:absolute;left:70px;top:-10px;" method="post">
        <input style="box-shadow:inset 1px 1px 4px black;margin-left:-55px;margin-top:10px;border-radius:6px;opacity:0;transition:0.6s;transform:scale(0.7);text-align:center;display:none;" type="text" placeholder="Search User" name="fname" onkeyup="showHint(this.value)" id="search-text fname" class="input-search">
    </form>
        <p style="color:white;margin-top:80px;margin-left:50px;"><span id="txtHint"></span></p>
    </div>

    <input style="box-shadow:inset 1px 1px 4px black; opacity:0; display:none; top:82px;left:66%;border-radius:6px;width:130px;transition:0.44s;transform:scale(0.50);text-align:center;position:absolute;" type="text" placeholder="Search User" autocomplete="off" name="fname" onkeyup="showHint(this.value)" id="search-text fname" class="input_search2">


    <div class="navbar">
    <img style="height:35px;margin-left:80%;width:35px;position:absolute; top:16%; "  class="openchat openchat2" onclick="openGCHAT()" src="world.webp" alt="" srcset="">
    <div onclick="ac()"><img class="info" src="image/<?php echo $filename; ?>" alt="<?php echo $filename; ?>"></div>
    <h2 style="position:absolute; top:10px;font-size:18px;color:white;font-weight:bolder;left:110px;" class="mt-3"><?php echo $userData['username']; ?></h2> <br>
        </div>


    <div class="login-container">
    <p style="
    color:white;
    height:34px;
    width:34px;
    font-family:Arial;
    background-color:red;
    position:relative;
    font-weight:800;
    left:83%;
    top:30px;
    cursor:grab;
    text-align:center;
    font-size:19px;
    border-radius:50%;"
     onclick="closelog()">x</p>
    

    
     <h2 style="font-weight:900;letter-spacing:2px;" class="login-title">SiGN IN</h2>
        <p style="color:;font-family:arial;font-weight:600;">For join to Global Chat</p>
        <form class="login-form" action="" autocomplete="off" method="post">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="submit" name="submit2" value="Login">
        </form>
    </div>
        


    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="user.js"></script>

    
    
    <!-- Search -->
    <script>
function showHint(str) {
  if (str.length == 0) {
    document.getElementById("txtHint").innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("txtHint").innerHTML = this.responseText;
      }
    }
    xmlhttp.open("GET", "gethint.php?q="+str, true);
    xmlhttp.send();
  }
}
</script>
<!-- ============================== -->



</body>
</html>