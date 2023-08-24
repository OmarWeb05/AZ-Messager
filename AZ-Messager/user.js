function yoxla1() {
  const windowWidth = window.innerWidth;
  const windowHeight = window.innerHeight;
// const img = document.querySelector("#img");
// document.getElementById("txt").innerHTML = windowWidth + " , " + windowHeight;

  const boxinfo = document.querySelector(".boxinfo");
  const cog = document.querySelector(".fa-cog");
  const nav = document.querySelector(".container");
  const s1 = document.querySelector(".s1");
  const s2 = document.querySelector(".s2");
  const OnlStatus = document.querySelector(".onlstatus");
  const boxsetting = document.querySelector(".boxsetting");
  const btn = document.querySelector(".btn-oncl");
  const form = document.getElementById("passwordForm");
  const control1 = document.getElementById("confirmPassword1");
  const control2 = document.getElementById("confirmPassword2");
  const fa_search = document.querySelector(".fa-search"); 
  const openchat = document.querySelector(".openchat");
  const openchat2 = document.querySelector(".openchat2");
  const login = document.querySelector(".login-container");
  const input = document.querySelector(".input-search");
const input_search2 = document.querySelector(".input_search2");
const formChangePass = document.querySelector(".formChangePass");

  
  if (windowWidth < 535) {
    s1.style.marginTop = "5px";
    s2.style.marginTop = "80px";
    nav.style.width = "60px";
    nav.style.height = "86%";
    boxinfo.style.height = "87%";
    boxsetting.style.width = "350px";
    boxsetting.style.height = "540px";
    boxsetting.style.top = "5px";
    boxsetting.style.left = "-155px";
    btn.style.left = "160px";
    boxsetting.style.backgroundColor = "black";
    form.style.marginTop = "125px";
    form.style.marginLeft = "-125px";
    control1.style.width = "170px";
    control2.style.width = "170px";
    control1.style.marginTop = "370px";
    control2.style.marginTop = "370px";
    fa_search.style.display = "none";
    openchat.style.display = "none";
    input.style.display = "none";
    input_search2.style.display = "block";
  } 
  

if (windowWidth > 1024) {
nav.style.height ="89%";
nav.style.width = "15%";
OnlStatus.style.top = "93.9%";
cog.style.marginTop = "10px";
s2.style.display = "block";
s1.style.display = "none";
fa_search.style.display = "block";
login.style.top = "10.19%";
login.style.left = "37%";
login.style.transform = "scale(0.88)";
openchat.style.display = "block";
openchat2.style.display = "none";
input.style.display = "block";
input_search2.style.marginTop = "-200px";
input_search2.style.marginLeft = "0%";
formChangePass.style.left = "210px";
formChangePass.style.top = "30px";
formChangePass.style.width = "360px";
}
}


// Add the event listener to the window object
window.addEventListener("resize", yoxla1);
yoxla1(); // Call the function initially to set the initial window width


function confirmFormSubmission() {
    var confirmed = confirm("Upload new image?");
    if (confirmed) {
        document.getElementById("uploadForm").submit();
    }
}


    function ac(){
        document.querySelector(".showimg").style.transform = "scale(1.0)";
document.querySelector(".showimg").style.transition = "0.9s";
        
        document.querySelector(".container").style.left = "-250px";
document.querySelector(".container").style.transition = "0.4s";

setTimeout(() => {
    document.querySelector(".boxinfo").style.left = "0px";
document.querySelector(".boxinfo").style.transition = "0.4s";
}, 260);
}


    function bagla(){
        document.querySelector(".showimg").style.transform = "scale(0)";
document.querySelector(".showimg").style.transition = "0.9s";

        setTimeout(() => {
            document.querySelector(".container").style.left = "0px";
document.querySelector(".container").style.transition = "0.4s";
          
                 document.querySelector(".container").style.left = "0px";
document.querySelector(".container").style.transition = "0.4s";
        }, 260);

        document.querySelector(".boxinfo").style.left = "-250px";
document.querySelector(".boxinfo").style.transition = "0.4s";
    }


function togglePassword() {
const passwordInput1 = document.getElementById("confirmPassword1");
if (passwordInput1.type === "password") {
    passwordInput1.type = "text";
} else {
    passwordInput1.type = "password";
}
const passwordInput2 = document.getElementById("confirmPassword2");
if (passwordInput2.type === "password") {
    passwordInput2.type = "text";
} else {
    passwordInput2.type = "password";
}
}

    // Update user status using AJAX
    function updateUserStatus() {
        $.ajax({
            url: 'update_user_status.php',
            success: function() {
                getUserStatus();
            }
        });
    }

    // Get and display user status using AJAX
    function getUserStatus() {
        $.ajax({
            url: 'get_user_status.php',
            success: function(result) {
                $('#user_status').html(result);
            }
        });
    }

    // On page load, get user status and start periodically updating it
    $(document).ready(function() {
        getUserStatus();
        setInterval(function() {
            updateUserStatus();
        }, 5000); // Update user status every 5 seconds
    });

        // Обработчик события onbeforeunload (вызывается, когда пользователь покидает страницу)
        window.onbeforeunload = function () {
        // Выполняем действия выхода из системы, когда пользователь покидает страницу
        logout();
    };

    
    function closebox(){    
        document.querySelector(".boxinfo").style.filter = "blur(0px)";
        document.querySelector(".navbar").style.filter = "blur(0px)";
        document.querySelector(".boxinfo").style.transition = "0.4s";
        document.querySelector(".navbar").style.transition = "0.4s";

        document.querySelector(".login-container").style.filter = "blur(0px)";
        document.querySelector(".login-container").style.transition = "0.4s";

        document.querySelector(".boxsetting").style.transition = "0.5s";
    document.querySelector(".boxsetting").style.marginLeft = "15%";
    document.querySelector(".boxsetting").style.opacity = "0";

    document.querySelector(".navbar").style.opacity = "1";
    document.querySelector(".navbar").style.transition = "0.5s";
    
    document.querySelector(".container").style.opacity = "1";
    document.querySelector(".container").style.transition = "0.5s";

    document.querySelector(".boxsetting").style.transform = "scale(0.0)";
    }



        function opensett() {
    document.querySelector(".boxinfo").style.filter = "blur(5px)";
    document.querySelector(".navbar").style.filter = "blur(5px)";
    document.querySelector(".boxinfo").style.transition = "0.4s";
    document.querySelector(".navbar").style.transition = "0.4s";
    document.querySelector(".login-container").style.filter = "blur(5px)";
    document.querySelector(".login-container").style.transition = "0.4s";

    document.querySelector(".container").style.opacity = "0.14";
    document.querySelector(".container").style.transition = "0.5s";

    document.querySelector(".boxsetting").style.transition = "0.5s";
    document.querySelector(".boxsetting").style.marginLeft = "45%";
    document.querySelector(".boxsetting").style.opacity = "5.99";
    document.querySelector(".boxsetting").style.transform = "scale(1)";
        }




    function openGCHAT(){
        const box = document.querySelector(".login-container");
        const navbar = document.querySelector(".navbar");
        const boxinfo = document.querySelector(".container");
        const boxsetting = document.querySelector(".boxsetting");

        box.style.transition = "0.5s";
        box.style.display = "block";

        boxsetting.style.filter = "blur(5px)";
        boxsetting.style.transition = "0.6s";

        navbar.style.filter = "blur(5px)";
        boxinfo.style.filter = "blur(5px)";

        boxinfo.style.transition = "0.6s";
        navbar.style.transition = "0.6s";

        setTimeout(() => {
            box.style.transition = "0.5s";
            box.style.opacity = "1";
        }, 200);
    }



    function closelog(){
        const box = document.querySelector(".login-container");
        const navbar = document.querySelector(".navbar");
        const boxinfo = document.querySelector(".container");
        const boxsetting = document.querySelector(".boxsetting");

        navbar.style.filter = "blur(0px)";
        boxinfo.style.filter = "blur(0px)";

        boxsetting.style.filter = "blur(0px)";
        boxsetting.style.transition = "0.6s";

        boxinfo.style.transition = "0.6s";
        navbar.style.transition = "0.6s";

        box.style.transition = "0.5s";
        box.style.opacity = "0";
    }



        function search(){
            const search = document.querySelector(".fa-search");
            const input_search = document.querySelector(".input-search");
            const input_search2 = document.querySelector(".input_search2");

            search.style.paddingTop = "30px";
            search.style.transition = "0.3s";

            setTimeout(() => {
                search.style.paddingTop = "1px";
                search.style.transition = "0.4s";   
                search.style.opacity = "0";   

                input_search.style.transform = "scale(1)";
                input_search.style.transition = "0.55s";

                input_search2.style.transition = "0.7s";
                input_search2.style.opacity = "1";
                input_search2.style.display = "block";
                input_search2.style.transform = "scale(0.75)";
            }, 520);

            setTimeout(() => {
                input_search.style.transform = "scale(0.95)";
                input_search.style.opacity = "1";
                input_search.transition = "0.6s";
            }, 1200);

            setTimeout(() => {
                input_search.style.transition = "0.5s";
                input_search.style.transform = "scale(0.55)";

                input_search2.style.transition = "0.5s";
                input_search2.style.transform = "scale(0.55)";
            }, 7000);




            setTimeout(() => {
                input_search2.style.display = "none";
                input_search.style.opacity = "0";
                search.style.paddingTop = "15px";
                search.style.transition = "0.3s";
                search.style.opacity = "1";   

            }, 7250);
        }

    
        function openChangePass(){
          const cpass = document.querySelector(".cpass");

          cpass.style.transform = "scale(0.8)";
          cpass.style.transition = "0.7s";
          cpass.style.borderRight = "8px solid green";
          
            document.getElementById("passwordForm").style.display = "block";
            document.getElementById("passwordForm").style.opacity = "1";
            setTimeout(() => {
                document.getElementById("passwordForm").style.opacity = "0";
                document.getElementById("passwordForm").style.transition = "0.7s";
                cpass.style.transform = "scale(1)";
                cpass.style.transition = "0.7s";
                cpass.style.borderRight = "0px solid green";
            }, 15000);
            setTimeout(() => {
                document.getElementById("passwordForm").style.display = "none";
            }, 15200);

        }
        