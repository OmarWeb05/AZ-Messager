<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body {
            background-color: #222;
        }
        
        .box {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 180px;
            height: 120px;
            perspective: 800px;
            transform: translate(-50%, -50%);
        }
        
        .line {
            position: absolute;
            width: 100%;
            height: 25px;
            background-color: transparent;
            border-radius: 10px;
            transform-origin: center;
            animation: rotate 5s cubic-bezier(0.175, 0.885, 0.32, 1.275) 1 forwards, colorShift 10s ease-in-out infinite;
            opacity: 0;
            transition: background-color 0.4s ease-in-out;
        }
        
        @keyframes rotate {
            0% {
                transform: translateX(-50%) rotate(0deg);
                opacity: 0;
            }
            50% {
                transform: translateX(50%) rotate(720deg);
                opacity: 1;
            }
            100% {
                transform: translateX(0) rotate(720deg);
                opacity: 0;
            }
        }
        
        @keyframes colorShift {
            0% {
                background-color: red;
            }
            25% {
                background-color: white;
            }
            66% {
                background-color: white;
            }
            90% {
                background-color: yellow;
            }
        }
        
        .line:nth-child(1) {
            top: 0;
            animation-delay: 0.2s;
        }
        
        .line:nth-child(2) {
            top: 30px;
            animation-delay: 0.4s;
        }
        
        .line:nth-child(3) {
            top: 60px;
            animation-delay: 0.6s;
        }
        
        .box:hover .line {
            opacity: 1;
        }
        
        #loadingText {
            position: absolute;
            top: 44%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            width:auto;
            font-size: 0px;
            color: white;
            font-family:Arial;
            font-weight: 900;
            animation: fadeInUp 1s ease-in-out 4.5s forwards, showText 1s ease-in-out 10.5s forwards;
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                font-size: 17px;
                background-color:white;
                padding:20px;
                color:black;
                border-radius:15px;
            }
        }
        
        @keyframes showText {
            to {
                opacity: 1;
                transform: scale(1.7);
            }
        }
    </style>
</head>
<body>
    <div class="box">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>

    <h4 id="loadingText"> Loading... </h4>

    <script>
    // Function to fetch the username from the server
    function fetchUsername() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_username.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                var username = xhr.responseText;
                var text = document.getElementById("loadingText");
                text.textContent = "Welcome, " + username;
                text.style.color = "white";

                // Generate speech with SSML markup for intonation
                // var speechMessage = `Welcome, <speak>${username}. Download completed.</speak>`;
                var speechMessage = `Добро пожаловать, <speak>${username}. Загрузка завершена.</speak>`;
                // var speechMessage = `Xoş gəldiniz, <speak>${username}.Yükləmə tamamlandı.</speak>`;
                speakWithSSML(speechMessage);

                // Redirect to the user page after a delay
                setTimeout(function () {
                    window.location.href = "user.php";
                }, 5999); // Adjust delay for faster redirect
            }
        };
        xhr.send();
    }

    // Function to speak using SSML
    function speakWithSSML(message) {
        const speechSynthesis = window.speechSynthesis;
        const utterance = new SpeechSynthesisUtterance();
        utterance.lang = "ru-RU"; // Set the desired language
        utterance.text = message;
        speechSynthesis.speak(utterance);
    }

    // Call the fetchUsername function when the page loads
    fetchUsername();
</script>

</body>
</html>

<!-- «Ваше желаемое имя голоса здесь» -->