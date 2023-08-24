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
    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Logout the user and redirect to a confirmation page
        session_destroy();
        header("Location: load1.html");
        exit();
    } else {
        echo "Failed to remove account: " . mysqli_error($con);
    }
    mysqli_stmt_close($stmt);
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

        // Update the password in the database
        $sql_update = "UPDATE user SET password = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($con, $sql_update);
        mysqli_stmt_bind_param($stmt_update, 'si', $hashedNewPassword, $id);

        if (mysqli_stmt_execute($stmt_update)) {
            mysqli_stmt_close($stmt_update);

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
<button style="position:absolute;top:20px;left:20px;padding:7px;box-shadow: 1px 1px 12px black; background-color:red;color:white;border-radius:6px;border:none;"  type="submit" name="confirmRemove">Delete Account</button>
</form>
    <form id="passwordForm" autocomplete="off"  method="post" action="user.php">
            <div class="mb-3">
                <br>
                <br>
                <input id="confirmPassword1" class="form-control" placeholder="New Password" type="password"  name="newPassword" required> <br>
                <input id="confirmPassword2" class="form-control" placeholder="Confirm Password" name="newPassword"  type="password">
                <input style="margin-top:10px;" id="input" type="checkbox" onclick="togglePassword()"><span style="color:white;font-family:Arial;margin-left:5px;">Show Password</span>
            </div>
            <button style="margin-top:-7px;padding:-10px; background-color:green;" type="submit" name="ChangePass" class="btn btn-success">Save</button>
            <br>
            <br>
            <p class="s2" style="color:white; font-size:13px; padding:3px;text-align:center;font-weight:900;font-family:arial;border-radius:10px;"><?php echo $userData['email']; ?></p>
           <p class="s1" style="color:white; display:none; opacity:0; font-size:13px; padding:3px;text-align:center;font-weight:900;font-family:arial;border-radius:10px;">Password: <?php echo $userData['password']; ?> 

        </form>
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
      <p style="cursor:grab; position:absolute;top:90%;left:40px;font-family:arial;font-weight:900;background-color:black;color:white;padding:8px;border-radius:6px;font-size:13px;text-align:center;" onclick="openGCHAT()" >Глобальный Чат</p>
      <form autocomplete="off" style="position:absolute;left:70px;top:-10px;" method="post">
        <input style="box-shadow:inset 1px 1px 4px black;margin-left:-65px;margin-top:10px;border-radius:6px;opacity:0;transition:0.6s;transform:scale(0.7);text-align:center;" type="text" placeholder="Search User" name="fname" onkeyup="showHint(this.value)" id="search-text fname" class="input-search">
    </form>
        <p style="color:white;margin-top:80px;margin-left:50px;"><span id="txtHint"></span></p>
    </div>

<!-- Message Dialog -->
<div id="message-dialog" style="display:none;opacity:0.75;position:absolute;top:110px;height:80.5%;left:46%;width:58%;transform:translateX(-50%);background-color:white;padding:12px;border-radius:10px;">

<p onclick="close2()" style="
    background-color:red;
    color:white;
    margin-top: 0px;
    margin-left:96%;
    height:30px;
    width:30px;
    font-family:Arial;
    font-weight:750;
    cursor:grab;
    border-radius:50%;
    text-align:center;
    align-items:center;">x</p>

<div style="position:absolute;width:90%;top:0px;border-bottom:1px solid black;" class="message-history">
<h3 style="color:;border-bottom:1px solid white;line-height:40px;"> Send Message to <span id="recipient-username"></span></h3>
    <div class="message-list">

    </div>
</div>

<form style="position:relative; left:0px; top:79%;" id="message-form" method="post" action="send_message.php">
    <input style="padding:2px;border-radius:7px;font-family:arial;font-size:20px; margin-left:-210px;height:60px;margin-bottom:2px;width:600px;color:;"  name="message" id="message-content" placeholder="  Write message..."></input>
    <input type="hidden" name="recipient" id="recipient-id"><br>
    <button style="position:absolute; border:none;font-family:arial;border-radius:6px; margin-top:-50px; height:40px; margin-left:400px; text-align:center;align-items:center; width:70px;  padding:2px;font-weight:700; color:white;background-color:green;" name="sendsms" type="submit">Send</button>
</form>


<button style="  
position:absolute;
top:87%;
left:82%;
height: 50px;
width: 50px;
background-color: rgb(33, 192, 89);
text-align: center;
align-items: center;
transition: 0.5s; border:none;border-radius:50%;color:white;" 
class="fa fa-microphone" onclick="show1()" id="startRecording"></button>

    <button style="    
position:absolute;
top:87%;
left:90%;
height: 50px;
width: 50px;
background-color: rgb(33, 192, 89);
text-align: center;
align-items: center; 
transition: 0.5s; border:none;border-radius:50%;color:white;" 
class="fa fa-stop" id="stopRecording" onclick="show2()" disabled></button>

    <div style="position:absolute;top:100px;left:60%;" id="output"></div>
    <div style="display:none;" id="serverResponse"></div>

</div>


    <div class="navbar">
    <div onclick="ac()"><img class="info" src="image/<?php echo $filename; ?>" alt="<?php echo $filename; ?>"></div>
    <h2 style="position:absolute; top:10px;font-size:18px;color:white;font-weight:bolder;left:110px;" class="mt-3"><?php echo $userData['username']; ?></h2> <br>
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




 <!-- open close box dialog -->
    <script>
 // JavaScript function to open the message dialog and populate the recipient username
function close2() {
    document.getElementById('message-dialog').style.display = 'none';
}

// JavaScript function to open the message dialog and populate the recipient username
function openMessageDialog(username, recipientId) {
    document.getElementById('recipient-username').textContent = username;
    document.getElementById('recipient-id').value = recipientId;
    document.getElementById('message-dialog').style.display = 'block';
}
//   ============================== // 




// while click insert message db //
// Submit message form using AJAX //
document.getElementById('message-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const messageContent = document.getElementById('message-content').value;
    const recipientId = document.getElementById('recipient-id').value;

    // Create a new XMLHttpRequest object
    const xhr = new XMLHttpRequest();

    // Configure the request
    xhr.open('POST', 'send_message.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Handle the response
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log(xhr.responseText); // Log the response for debugging
            // You can update the UI or show a success message here
        } else {
            console.error('Request failed:', xhr.statusText);
        }
    };

    // Send the request with the form data
    xhr.send(`message=${encodeURIComponent(messageContent)}&recipient=${encodeURIComponent(recipientId)}`);
});
//   ============================== // 




// message history in box = NOT WORKING //
function loadMessageHistory() {
    const messageListDiv = document.querySelector('.message-list');

    const xhr = new XMLHttpRequest();

    // Configure the request
    xhr.open('GET', 'get_messages.php', true);

    // Handle the response
    xhr.onload = function() {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            messageListDiv.innerHTML = ''; 

            data.forEach(message => {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message');
                messageDiv.innerHTML = `<strong>${message.sender}:</strong> ${message.message}`;
                messageListDiv.appendChild(messageDiv);
            });
        } else {
            console.error('Request failed:', xhr.statusText);
        }
    };

    // Send the request
    xhr.send();
}
//   ============================== // 





// voice send history in box //
// Call the function to load message history when the page loads
window.addEventListener('load', loadMessageHistory);

const startButton = document.getElementById('startRecording');
const stopButton = document.getElementById('stopRecording');
const outputDiv = document.getElementById('output');

let recognition = new webkitSpeechRecognition();
recognition.continuous = true;
recognition.interimResults = true;

let recorder, stream;
let chunks = [];

startButton.addEventListener('click', async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        recorder = new MediaRecorder(stream);

        chunks = [];
        recorder.ondataavailable = event => chunks.push(event.data);
        recorder.onstop = async () => {
            const blob = new Blob(chunks, { type: 'audio/webm' });
            const audioURL = URL.createObjectURL(blob);

            const audioElement = document.createElement('audio'); // Create a new audio element
            audioElement.controls = true;
            audioElement.src = audioURL;

            outputDiv.appendChild(audioElement); // Append the audio element to the output

            // Upload the recorded audio to the server
            try {
                const formData = new FormData();
                formData.append('audio', blob);

                const response = await fetch('convert_audio.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.text();
                outputDiv.innerHTML += '<p>' + result + '</p>';

                // Display server response
                const serverResponseDiv = document.getElementById('serverResponse');
                serverResponseDiv.innerHTML = 'Server Response: ' + result;
            } catch (err) {
                console.error('Error uploading audio:', err);
            }
        };

        recorder.start();
        startButton.disabled = true;
        stopButton.disabled = false;
    } catch (err) {
        console.error('Error accessing microphone:', err);
    }
});

stopButton.addEventListener('click', () => {
    if (recorder && recorder.state === 'recording') {
        recorder.stop();
        stream.getTracks().forEach(track => track.stop());
        startButton.disabled = false;
        stopButton.disabled = true;
    }
});
//   ============================== // 

</script>
</body>
</html>