<?php
session_start();
if (isset($_SESSION['email'])) {
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign up / Login Form</title>
  <link rel="stylesheet" href="styleslogin.css">
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-button');
        const signupForm = document.querySelector('.signup');
        const loginForm = document.querySelector('.login');

        // Toggle between Login and Sign Up forms
        toggleButtons.forEach(button => {
            button.addEventListener('click', () => {
                if (button.classList.contains('login-btn')) {
                    loginForm.style.display = 'block';
                    signupForm.style.display = 'none';
                    toggleButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                } else {
                    signupForm.style.display = 'block';
                    loginForm.style.display = 'none';
                    toggleButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                }
            });
        });

        // Validate password confirmation on form submission
        const signupFormElement = document.querySelector('.signup form');
        signupFormElement.addEventListener('submit', function(event) {
            const password = document.querySelector('input[name="pswd"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_pswd"]').value;

            if (password !== confirmPassword) {
                alert('Passwords do not match');
                event.preventDefault();
            }
        });
    });
  </script>
</head>
<body>
    <div class="container">
        </div>
        <div class="form-section">
            <div class="main">  	
                <input type="checkbox" id="chk" aria-hidden="true">
                <div class="signup">
                    <form action="progess.php" method="POST">
                        <label for="chk" aria-hidden="true">Sign up</label>
                        <input type="text" name="txt" placeholder="User name" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="pswd" placeholder="Password" required>
                        <input type="password" name="confirm_pswd" placeholder="Confirm Password" required>
                         <!-- User Type Dropdown -->
        <select name="user_type" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
                        <button type="submit" name="submit" value="Sign up">Sign Up</button>
                    </form>
                </div>
                <div class="login">
                    <form action="progess.php" method="POST">
                        <label for="chk" aria-hidden="true">Login</label>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="pswd" placeholder="Password" required>
                        <p class="forgot-password" ><a href="forgot_password.php" >Forgot Password?</a></p>
                        <button type="submit" name="submit" value="Login">Log In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body