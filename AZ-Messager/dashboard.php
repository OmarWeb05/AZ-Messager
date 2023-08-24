<?php
session_start();
include('database.inc.php');

// Перенаправляем на страницу входа, если пользователь не авторизован
if (!isset($_SESSION['UID'])) {
    header('location:index.php');
    die();
}

// Check if the user's last login timestamp is already stored in the session
if (isset($_SESSION['last_login'])) {
    $lastLoginTimestamp = $_SESSION['last_login'];
} else {
    // If the last login timestamp is not stored in the session, set it to the current time
    $lastLoginTimestamp = time(); // Current timestamp

    // Store the last login timestamp in the session for future use
    $_SESSION['last_login'] = $lastLoginTimestamp;
}



// Remove data from the database when the button is clicked
if (isset($_POST['remove'])) {
    if (isset($_POST['ID'])) {
        $id = $_POST['ID'];

        // Check if the user being removed is online
        $sql = "SELECT last_login FROM user WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $lastLogin);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $time = time();
        $isOnline = $lastLogin > $time;

        // Delete the user from the database
        $deleteUserSql = "DELETE FROM user WHERE id = ?";
        $deleteUserStmt = mysqli_prepare($con, $deleteUserSql);
        mysqli_stmt_bind_param($deleteUserStmt, 'i', $id);

        $deleteUsersSql = "DELETE FROM users WHERE id = ?";
        $deleteUsersStmt = mysqli_prepare($con, $deleteUsersSql);
        mysqli_stmt_bind_param($deleteUsersStmt, 'i', $id);

        // Perform the deletion from both tables
        $success = true;
        if (!mysqli_stmt_execute($deleteUserStmt)) {
            $success = false;
            echo "<p>Error removing user record: " . mysqli_error($con);
        }
        if (!mysqli_stmt_execute($deleteUsersStmt)) {
            $success = false;
            echo "<p>Error removing users record: " . mysqli_error($con);
        }

        if ($success) {
            echo "<p style='display:none;'>Record removed successfully";

            // Check if the user is online and redirect them to another page
            if ($isOnline) {
                session_unset();
                session_destroy();
                header('Location: load5.html'); // Replace "load5.html" with the actual URL of the page where you want to redirect the user after logout
                exit();
            }
        }
        mysqli_stmt_close($deleteUserStmt);
        mysqli_stmt_close($deleteUsersStmt);
    }
}


?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <title>AZ Messenger</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="shortcut icon" href="az2.jpg" type="image/x-icon">
    <style>
    
         body {
            overflow-x: hidden;
            background-image: linear-gradient(180deg, #0093E9 100%, #80D0C7 100%) /* Change the RGB values to your desired color */
        }
        .container {
            margin-top: 70px;
            border: 1px solid #9C9C9C;
            background-color: #fff;
            overflow: scroll;
            padding: 30px;
            border-radius:8px;
            overflow: hidden;
            transition:0.5s;
        }
        .container:hover {
            transition:0.5s;
            box-shadow:1px 1px 20px gray;
        }
        .container h2 {
            margin-bottom: 25px;
        }
        .text-info {
            color: #000 !important;
        }
        
        .one, .two {
            color: white;
            letter-spacing: 1px;
            font-weight: bolder;
            font-size: 20px;
            text-decoration: none;
            border-bottom: 3px solid white;
            position: absolute;
            top: 1%;
            line-height: 35px;
        }
        .one {
            left: 29px;
        }
        .two {
            left: 196px;
        }
       

        .krug1{
    height:360px;
    width:360px;
    border-radius:50%;
    background-color:blue;
    position: absolute;
    opacity: 0.4;
    z-index: -1;
    top:-19%;
    left:-5%;
}


.krug2{
    height:260px;
    width:260px;
    opacity: 0.8;
    border-radius:50%;
    background-color:blue;
    position: absolute;
    z-index: -1;
    top:83%;
    left:88%;
}

    </style>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  
<!-- Message Dialog -->
<div id="message-dialog" style="display:none;position:absolute;top:70px;left:11.4%;transform:translateX(-50%);background-color:white;padding:20px;border-radius:10px;">
    <h3>Send Message to <br><span id="recipient-username"></span></h3>
    <form method="post" action="send_message.php">
        <textarea style="border-radius:7px;" name="message" placeholder="Write message..." style="width:auto;height:auto;"></textarea>
        <input type="hidden" name="recipient" id="recipient-id">
        <button style="border:none;font-family:arial;border-radius:6px;" type="submit">Send</button>
    </form>
</div>

    <script>  
            // Обработчик события onbeforeunload (вызывается, когда пользователь покидает страницу)
        window.onbeforeunload = function () {
            // Выполняем действия выхода из системы, когда пользователь покидает страницу
            logout();
        };
    </script>
</head>
<body>



<a style="color:white;text-decoration: none;" class="one" href="register.php">Регистрация</a>
    <a style="color:white;text-decoration: none;" class="two" href="index.php">Вход</a>

<div class="krug1"></div>
<div class="krug2"></div>

    <div style="margin-left:350px;" class="container">
        <h2 class="text-center text-info">Статус пользователей</h2>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="5%" style="letter-spacing: 1px;">ID</th>
                    <th width="12%" style="letter-spacing: 1px;">Name</th>
                    <th width="30%" style="letter-spacing: 1px;">Notification</th>
                    <th width="12%" style="letter-spacing: 1px;">Message</th>
                    <th width="15%" style="letter-spacing: 1px;">Remove</th>
                    <th width="3%" style="letter-spacing: 1px;"><img style="height: 32px; width: 70px; border-radius: 7px;" src="A.png" alt="" srcset=""></th>
                </tr>
            </thead>
            <tbody id="user_grid">
            </tbody>
        </table>
    </div>
    
    <script>
        function updateUserStatusAndList() {
            $.ajax({
                url: 'update_user_status.php',
                success: function() {
                    getUserStatus();
                }
            });
        }
        function getUserStatus() {
            $.ajax({
                url: 'get_user_status.php',
                success: function(result) {
                    $('#user_grid').html(result);
                }
            });
        }
        // При загрузке страницы получаем статус пользователей и начинаем периодически обновлять статус и список пользователей
        $(document).ready(function() {
            getUserStatus();
            setInterval(function() {
                updateUserStatusAndList();
            }, 4800); // Обновление статуса и списка пользователей каждые 3 секунды
        });
    </script>

<script>
    // JavaScript function to open the message dialog and populate the recipient username
    function openMessageDialog(username, recipientId) {
        document.getElementById('recipient-username').textContent = username;
        document.getElementById('recipient-id').value = recipientId;
        document.getElementById('message-dialog').style.display = 'block';
    }

    // Submit message form using AJAX
    document.getElementById('message-form').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const messageContent = document.getElementById('message-content').value;
        const recipientId = document.getElementById('recipient-id').value;
        
        // Use AJAX to send the message to the server
        // Example AJAX code using jQuery:
      
        $.post('send_message.php', { message: messageContent, recipient: recipientId }, function(response) {
            // Handle response from the server (e.g., success, error)
        });
        
    });
</script>
</body>
</html>
