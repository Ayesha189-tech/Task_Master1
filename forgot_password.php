<?php
session_start();
include 'database.php'; 

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($email) && !empty($new_password) && !empty($confirm_password)) {
        if ($new_password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {        
            $query = "SELECT * FROM data WHERE email = ?";
            $stmt = $con->prepare($query); 
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $query = "UPDATE data SET pswd = ? WHERE email = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param("ss", $hashed_password, $email);
                
                if ($stmt->execute()) {
                    echo "<script>
                        alert('Password updated successfully!');
                        window.location.href = 'index.php'; 
                    </script>";
                    exit;
                } else {
                    $error = "Error updating password!";
                }
            } else {
                $error = "Email not found!";
            }
        }
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h2>Reset Password</h2>
            <?php 
            if (!empty($error)) { 
                echo "<script>alert('$error');</script>"; 
            } 
            ?>
            <form action="forgot_password.php" method="POST">
                <input type="email" name="email" placeholder="Enter your email" required>
                <input type="password" name="new_password" placeholder="Enter new password" required>
                <input type="password" name="confirm_password" placeholder="Confirm new password" required>
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
