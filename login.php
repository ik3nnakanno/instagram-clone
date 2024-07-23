<?php
include 'config.php';

if(isset($_POST['guest'])){
    $username = 'ikenn';
    $password = 123456;

    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
            
            $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])){
                    $session_lifetime = 60 * 60 * 24 * 5;
                    session_set_cookie_params($session_lifetime);
                    session_start();
                    $_SESSION['id'] = $row['users_id'];
                    header("Location: index.php");
                    exit();
                }else{
                    header("Location: login.php?e=Sorry, your password was incorrect. Please double-check your password.");
                    exit();
                }
            }else{
                    header("Location: login.php?e=Sorry, your password was incorrect. Please double-check your password.");
                    exit();
                }
        } 
    


$error = "";

if (isset($_POST['login'])) {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validate($_POST['detail']);
    $password = validate($_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $session_lifetime = 60 * 60 * 24 * 5;
            session_set_cookie_params($session_lifetime);
            session_start();
            $_SESSION['id'] = $row['users_id'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Sorry, your password was incorrect. Please double-check your password.";
        }
    } else {
        $error = "Sorry, your password was incorrect. Please double-check your password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram</title>
    <link rel="icon" href="images/icons8-instagram-480.png">
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="img">
<div class="img1"><img src="images/screenshot3.png" alt=""></div>
    <div class="img2 visible"><img src="images/screenshot1.png" alt=""></div>
    <div class="img2"><img src="images/screenshot4.png" alt=""></div>
    <div class="img2"><img src="images/screenshot2.png" alt=""></div>  
</div>
<div class="content">
<form action="" method="post" id="loginForm">
        <br><h2 align="center">Instagram</h2><br>
        <input type="text" name="detail" id="username" autofocus placeholder="Email or username">
        <div class="form-tag">
            <input type="password" name="password" id="password" placeholder="Password">
            <input type="checkbox" id="show-password" onclick="togglePassword()">
            <label for="show-password"><img class="see" src="images/eye-line.png" alt=""></label>
        </div> 
        <small id="password-error" class="<?= $error ? '' : 'hidden' ?>"><?= htmlspecialchars($error) ?></small><br>
        <button type="submit" disabled name="login" id="login">Login</button>
    </form>
<br>    <form action="" method="post">
        <button type="submit" name="guest">Continue as guest</button></form>
<div class="addon">
    Don't have an account? &nbsp; <a href="signup.php">Sign Up</a>
</div>
</div>
<script>
     const detailField = document.getElementById('username');
        const passwordField = document.getElementById('password');
        const passwordError = document.getElementById('password-error');
        const loginButton = document.getElementById('login');

        function validateForm() {
            const detail = detailField.value.trim();
            const password = passwordField.value;

            let valid = true;

            if (detail === '') {
                valid = false;
            }

            if (password.length <= 5) {
                valid = false;
            }

            loginButton.disabled = !valid;
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }

        detailField.addEventListener('input', validateForm);
        passwordField.addEventListener('input', validateForm);

        document.getElementById('loginForm').addEventListener('submit', function(event) {
            const password = passwordField.value;

            if (password.length <= 5) {
                event.preventDefault();
                passwordError.classList.remove('hidden');
                passwordField.classList.add('error');
            } else {
                passwordError.classList.add('hidden');
                passwordField.classList.remove('error');
            }
        });

        validateForm();



        window.onload = function() {
        var images = document.querySelectorAll('.img2');
        var currentIndex = 0;

        function fadeInNext() {
            images[currentIndex].classList.remove('visible');

            currentIndex = (currentIndex + 1) % images.length;

            images[currentIndex].classList.add('visible');
        }

        setInterval(fadeInNext, 5000); 
    };
</script>
