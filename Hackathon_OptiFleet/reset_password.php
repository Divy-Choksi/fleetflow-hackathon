<?php
session_start();
require "dbconnect.php";

if(isset($_GET['token'])) {

    $token = $_GET['token'];

    $checkToken = $conn->prepare("SELECT * FROM users WHERE reset_token = :token");
    $checkToken->bindParam(':token', $token);
    $checkToken->execute();
    $result = $checkToken->fetch(PDO::FETCH_ASSOC);

    if(!$result) {
        die("Invalid reset link - token not found. <a href='forgot_password.php'>Request new reset link</a>");
    }

    if($result['token_expiry'] < date('Y-m-d H:i:s')) {
        die("Reset link has expired. <a href='forgot_password.php'>Request new reset link</a>");
    }

} else {
    die("No reset token provided. <a href='forgot_password.php'>Request new reset link</a>");
}

if(isset($_POST['update_password'])) {

    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $token = $_POST['token'];

    $stmt = $conn->prepare("UPDATE users 
                            SET password = :password,
                                reset_token = NULL,
                                token_expiry = NULL
                            WHERE reset_token = :token");

    $stmt->bindParam(':password', $new_password);
    $stmt->bindParam(':token', $token);

    if($stmt->execute()) {

        echo "<div style='text-align:center;margin-top:20px;'>";
        echo "Password updated successfully.<br>";
        echo "<a href='login.php'>Login here</a>";
        echo "</div>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form action="" method="POST">
                <h1>New Password</h1>
                <span>Enter your new password</span>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">
                <button type="submit" name="update_password">Update Password</button>
                <a href="login.php">Back to Login</a>
            </form>
        </div>
    </div>
</body>
</html>