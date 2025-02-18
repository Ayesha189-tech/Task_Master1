<?php
session_start();
include 'database.php';

$error = "";
$success = "";
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($password) && $password === $confirm_password) {
       
        $query = "SELECT * FROM data WHERE reset_token = ? AND reset_expires > NOW()";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "UPDATE data SET password=?, reset_token=NULL, reset_expires=NULL WHERE reset_token=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $hashed_password, $token);
            $stmt->execute();

            $success = "Your password has been reset successfully! <a href='index.php'>Login here</a>";
        } else {
            $error = "Invalid or expired token!";
        }
    } else {
        $error = "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h2>Reset Password</h2>
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
            <form action="reset_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="password" name="password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
