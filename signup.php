<?php
include 'config.php';

if (isset($_POST['signup'])) {
    function validate($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $username = validate($_POST['username']);
    $password = validate($_POST['password']);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $email = validate($_POST['email']);
    $name = validate($_POST['name']);
    $pic = '1.jpg';

    $stmt = $conn->prepare("INSERT INTO users (name, email, username, password, pic) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $username, $hash, $pic);

    if ($stmt->execute()) {
        header('Location: login.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up &bull; Instagram</title>
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
<form action="" method="post" id="signupForm">
        <h2 align="center">Instagram</h2>
        <input type="email" name="email" id="emailField" autofocus placeholder="Email">
        <input type="text" name="name" id="name" placeholder="Fullname">
        <input type="text" name="username" id="username" placeholder="Username">
        <small id="error-message" class="hidden"></small>
        <div class="form-tag">
            <input type="password" name="password" id="password" placeholder="Password">
            <input type="checkbox" id="show-password" onclick="togglePassword()">
            <label for="show-password"><img class="see" src="images/eye-line.png" alt=""></label>
        </div>
        <small id="password-error" class="hidden"></small>
        <div class="form-tag">
            <input type="password" name="password2" id="password2" placeholder="Confirm Password">
        </div>
        <small id="password-match-error" class="hidden">Passwords do not match</small>
        <h5>People who use our service may have uploaded your contact information to instagram.
            <a href="">Learn more</a>
        </h5>
        <h5>By signing up, you agree to our Terms , Privacy Policy and Cookies Policy .
            <a href=""></a>
        </h5>
        <button type="submit"  name="signup" id="signup">Sign Up</button>
    </form>
<br> 
<div class="addon">
Have an account?&nbsp; <a href="login.php"> Log in</a>
</div>
</div>
<script>
const emailField = document.getElementById('emailField');
        const nameField = document.getElementById('name');
        const usernameField = document.getElementById('username');
        const passwordField = document.getElementById('password');
        const password2Field = document.getElementById('password2');
        const errorMessage = document.getElementById('error-message');
        const passwordError = document.getElementById('password-error');
        const passwordMatchError = document.getElementById('password-match-error');
        const signupButton = document.getElementById('signup');

        function validateEmail(email) {
            const re = /\S+@\S+\.\S+/;
            return re.test(email);
        }

        function validateName(name) {
            return name.trim() !== '';
        }

        function validateUsername(username) {
            return username.trim() !== '' && username.length >= 3;
        }

        function validatePassword(password) {
            return password.length >= 6;
        }

        function validatePasswordMatch(password1, password2) {
            return password1 === password2;
        }

        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.classList.remove('hidden');
        }

        function hideError() {
            errorMessage.classList.add('hidden');
        }

        function showPasswordError(message) {
            passwordError.textContent = message;
            passwordError.classList.remove('hidden');
        }

        function hidePasswordError() {
            passwordError.classList.add('hidden');
        }

        function showPasswordMatchError() {
            passwordMatchError.classList.remove('hidden');
        }

        function hidePasswordMatchError() {
            passwordMatchError.classList.add('hidden');
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }

        function validateForm() {
            const email = emailField.value.trim();
            const name = nameField.value.trim();
            const username = usernameField.value.trim();
            const password = passwordField.value;
            const password2 = password2Field.value;

            let valid = true;

            if (!validateEmail(email)) {
                valid = false;
            }
            if (!validateName(name)) {
                valid = false;
            }
            if (!validateUsername(username)) {
                valid = false;
            }
            if (!validatePassword(password)) {
                valid = false;
            }
            if (!validatePasswordMatch(password, password2)) {
                valid = false;
            }

            signupButton.disabled = !valid;
            return valid;
        }

        emailField.addEventListener('input', validateForm);
        nameField.addEventListener('input', validateForm);
        usernameField.addEventListener('input', function(event) {
            const username = this.value.trim();

            if (username === '') {
                hideError();
                return;
            }
            if (username.length < 3) {
                showError('Too short');
            } else {
                checkUsername(username);
            }
            validateForm();
        });
        passwordField.addEventListener('input', validateForm);
        password2Field.addEventListener('input', validateForm);

        emailField.addEventListener('blur', function() {
            if (!validateEmail(emailField.value.trim())) {
                showError('Invalid email address');
            } else {
                hideError();
            }
        });

        nameField.addEventListener('blur', function() {
            if (!validateName(nameField.value.trim())) {
                showError('Name cannot be empty');
            } else {
                hideError();
            }
        });

        usernameField.addEventListener('blur', function() {
            const username = usernameField.value.trim();
            if (username === '') {
                showError('Username cannot be empty');
                return;
            }
            if (username.length < 3) {
                showError('Too short');
            } else {
                checkUsername(username);
            }
        });

        passwordField.addEventListener('blur', function() {
            if (!validatePassword(passwordField.value)) {
                showPasswordError('Password must be at least 6 characters');
            } else {
                hidePasswordError();
            }
        });

        password2Field.addEventListener('blur', function() {
            if (!validatePasswordMatch(passwordField.value, password2Field.value)) {
                showPasswordMatchError();
            } else {
                hidePasswordMatchError();
            }
        });

        password2Field.addEventListener('input', function() {
            if (validatePasswordMatch(passwordField.value, password2Field.value)) {
                hidePasswordMatchError();
            }
        });

        usernameField.addEventListener('keydown', function(event) {
            if (event.key === ' ') {
                event.preventDefault();
            }
        });

        signupButton.addEventListener('click', function(event) {
            if (!validateForm()) {
                event.preventDefault();
            }
        });

        function checkUsername(username) {
            fetch('check_user.php?username=' + encodeURIComponent(username))
                .then(response => response.text())
                .then(data => {
                    if (data === 'exists') {
                        showError('This Username is taken');
                    } else {
                        hideError();
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
        }
</script>