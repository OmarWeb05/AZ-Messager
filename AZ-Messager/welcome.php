

<?php
session_start();
include("db.php");

if (!isset($_SESSION["username"])) {
    header("location: login.html");
    exit();
}

if (isset($_GET["logout"])) {
    session_destroy();
    header("location: user.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["message"])) {
    $sender = $_SESSION["username"];
    $message = mysqli_real_escape_string($con, $_POST["message"]);
    $timestamp = date("Y-m-d H:i:s"); // Current timestamp

    $sql = "INSERT INTO messages (sender, message, timestamp) VALUES ('$sender', '$message', '$timestamp')";
    mysqli_query($con, $sql);

    // Redirect to a different page after sending a message
    header("location: welcome.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App</title>
    <style>
    body {
    margin: 0;
    padding: 0;
    background-image: linear-gradient(to right, #2c3e50, #4ca1af);    background-size: cover;
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

    .chat-box {
        width: 90%; /* Adjust width for mobile devices */
        max-width: 600px; /* Limit width for larger screens */
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }
    .chat-header {
        background-color: #007bff;
        color: #fff;
        text-align: center;
        padding: 10px;
        font-size: 18px;
        font-weight: bold;
    }
    .chat-messages {
        max-height: 300px;
        overflow-y: auto;
        margin: 10px;
        padding: 16px;
    }
    .message {
        background-color: #f4f4f4;
        padding: 8px;
        border-radius: 5px;
        margin: 10px;
        margin-bottom: 5px;
    }
    .message strong {
        font-weight: bolder;
        color: #007bff;
    }
    .chat-input {
        display: flex;
        padding: 10px;
        background-color: #f0f0f0;
    }
    .input-field {
        flex: 1;
        border: none;
        width: 80%; /* Adjust width for mobile devices */
        max-width: 460px; /* Limit width for larger screens */
        border-radius: 5px;
        padding: 8px;
    }
   .chat-input .send-button {
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        margin-top:10px;
        padding: 8px 12px;
        margin-left: -30px;
        position: absolute;
        cursor: pointer;
    }
    .logout {
        text-align: center;
    }
    .logout a {
        color: #007bff;
        text-decoration: none;
    }

    .emoji-container {
        font-size: 14px;
        margin-top: 10px;
        overflow-y: scroll;
        max-height: 82px; /* Set the maximum height for the container */
        margin: 38px;
    }

    .emoji-button{
        top:-48px;
        position: relative;
        left:210px;
    }
</style>

</head>
<body>

<div class="chat-box">
    <div class="chat-header">AZ Messager - Общий чат</div>
    <div class="chat-messages">
        <?php
        $historySql = "SELECT * FROM messages ORDER BY id DESC";
        $historyResult = mysqli_query($con, $historySql);

        while ($row = mysqli_fetch_assoc($historyResult)) {
            $sender = $row['sender'];
            $message = $row['message'];
            $timestamp = $row['timestamp'];

            echo '<div class="message">';
            echo '<strong>' . $sender . ': </strong>';
            echo $message;
            echo '<div class="timestamp" style="font-size: 12px; color: #888;">' . $timestamp . '</div>';
            echo '</div>';
        }
        ?>
    </div>

 <div class="chat-input">
    <form autocomplete="off" action="" method="post">
        <textarea style="resize:none;" class="input-field" name="message" id="messageInput" placeholder="Type your message..." maxlength="140" required></textarea>
        <button style="padding: 7px 6px; font-size: 20px; border:none;" type="button" class="emoji-button" onclick="openEmojiContainer()">😀</button>
        <button class="send-button" type="submit">Send</button>
    </form>
</div>

<div id="emojiContainer" class="emoji-container" style=" display: none;"></div>
<p onclick="closeemoji()" class="closeemoji" style="background-color:red;color:white;height:27px;width:27px;margin-top:-22px;display: none;margin-left:27px;border-radius:50%;font-family:arial;font-weight:900;text-align:center;">x</p>


    <div class="logout">
        <p style="font-weight: 700;">Hello, <?php echo $_SESSION["username"]; ?> | <a href="user.php">Logout</a></p>
    </div>
    
</div>
<script>


function closeemoji(){
document.getElementById("emojiContainer").style.display = "none";
document.querySelector(".closeemoji").style.display = "none";
}

const emojis = ["😃", "😊", "👍", "❤️", "👌", "😂", "😍", "🙌", "❌", "✅", "🤦‍♂️", "🤣", "😁", "🤔",
"😜","🤤", "😌", "😭", "😞", "😇", "💩", "😻", "🎁", "🚗", "🛴", "🚲", "🌏", "🌙", "💧", "🔥"]; // Replace with your emojis array

    function openEmojiContainer() {
        const emojiContainer = document.getElementById('emojiContainer');
        document.querySelector(".closeemoji").style.display = "block";
        emojiContainer.innerHTML = '';

        emojis.forEach(emoji => {
            const emojiButton = document.createElement('button');
            emojiButton.style.padding = '7px 6px';
            emojiButton.style.fontSize = '27px';
            emojiButton.textContent = emoji;
            emojiButton.onclick = function () {
                insertEmoji(emoji);
            };
            emojiContainer.appendChild(emojiButton);
        });

        emojiContainer.style.display = 'block';
    }

    function insertEmoji(emoji) {
        const messageInput = document.getElementById('messageInput');
        messageInput.value += emoji;
    }
</script>



<script>
    function insertEmoji(emoji) {
        var messageInput = document.getElementById('messageInput');
        messageInput.value += emoji;
    }

    function updateChatMessages() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var newMessages = xhr.responseText;
                document.querySelector('.chat-messages').innerHTML = newMessages;
            }
        };
        xhr.open('GET', 'get_messages.php', true); // Create a PHP file to retrieve messages
        xhr.send();
    }

    // Update chat messages every 5 seconds
    setInterval(updateChatMessages, 5000);
</script>

</body>
</html>
