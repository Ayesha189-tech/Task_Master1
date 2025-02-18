<?php
session_start();
include 'database.php';  // Ensure this file connects to your database

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    
    // Handle Sign-up
    if ($_POST["submit"] == "Sign up") {
        // Retrieve input values and sanitize them
        $firstname = mysqli_real_escape_string($con, trim($_POST['txt']));
        $email = mysqli_real_escape_string($con, strtolower(trim($_POST['email'])));
        $password = trim($_POST['pswd']);
        $confirm_password = trim($_POST['confirm_pswd']);
        $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : 'user';  // Default to 'user' if not provided

        // Validate inputs
        if (empty($firstname) || empty($email) || empty($password) || empty($confirm_password)) {
            echo "<script>alert('All fields are required!'); window.location='index.php';</script>";
            exit;
        }

        if ($password !== $confirm_password) {
            echo "<script>alert('Passwords do not match!'); window.location='index.php';</script>";
            exit;
        }

        // Check if email already exists
        $query = "SELECT * FROM data WHERE email=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result_email = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result_email) > 0) {
            echo "<script>alert('Email already registered! Please log in.'); window.location='index.php';</script>";
            exit;
        }

        // Hash Password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user details into the database, including user_type
        $sql = "INSERT INTO data (txt, email, pswd, user_type) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $firstname, $email, $hashed_password, $user_type);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Signup successful! Please log in.'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "'); window.location='index.php';</script>";
        }
    }

    // Handle Login
    elseif ($_POST["submit"] == "Login") {
        // Retrieve login inputs
        $email = mysqli_real_escape_string($con, strtolower(trim($_POST['email'])));
        $password = trim($_POST['pswd']);

        // Validate inputs
        if (empty($email) || empty($password)) {
            echo "<script>alert('Email and Password are required!'); window.location='index.php';</script>";
            exit;
        }

        // Check if the email exists in the database
        $query = "SELECT * FROM data WHERE email=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Verify password and set session
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['pswd'])) {
                $_SESSION['txt'] = $row['txt'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_type'] = $row['user_type'];  // Store user_type in session

                // Redirect based on user type
                if ($_SESSION['user_type'] == 'admin') {
                    echo "<script>alert('Admin login successful!'); window.location='admin_dashboard.php';</script>";
                } else {
                    echo "<script>alert('Login successful!'); window.location='home.php';</script>";
                }
                exit;
            } else {
                echo "<script>alert('Invalid email or password'); window.location='index.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid email or password'); window.location='index.php';</script>";
        }
    }
}

mysqli_close($con);  // Close the database connection
?>
