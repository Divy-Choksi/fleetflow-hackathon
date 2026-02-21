<?php
session_start();
require "dbconnect.php";

if(isset($_POST['reset_request'])) {

    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if($stmt->rowCount() > 0) {

        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $update = $conn->prepare("UPDATE users 
                                  SET reset_token = :token, 
                                      token_expiry = :expiry 
                                  WHERE email = :email");

        $update->bindParam(':token', $token);
        $update->bindParam(':expiry', $expiry);
        $update->bindParam(':email', $email);
        $update->execute();

        $verify = $conn->prepare("SELECT reset_token, token_expiry FROM users WHERE email = :email");
        $verify->bindParam(':email', $email);
        $verify->execute();
        $result = $verify->fetch(PDO::FETCH_ASSOC);

        $reset_link = "http://localhost/fleet/reset_password.php?token=" . $token;

        echo "<div style='text-align:center;margin-top:20px;'>";
        echo "Reset link generated successfully:<br>";
        echo "<a href='$reset_link'>$reset_link</a><br><br>";
        echo "Debug Info:<br>";
        echo "Token in DB: " . $result['reset_token'] . "<br>";
        echo "Expiry in DB: " . $result['token_expiry'] . "<br>";
        echo "Current Time: " . date('Y-m-d H:i:s') . "<br><br>";
        echo "<a href='login.php'>Back to Login</a>";
        echo "</div>";

        exit();

    } else {
        echo "<div style='text-align:center;margin-top:20px;'>";
        echo "Email not found.<br>";
        echo "<a href='forgot_password.php'>Try Again</a>";
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
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if(!isset($_POST['reset_request'])): ?>
    <div class="container">
        <div class="form-container">
            <form action="" method="POST">
                <h1>Reset Password</h1>
                <span>Enter your email address to reset password</span>
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit" name="reset_request">Send Reset Link</button>
                <a href="login.php">Back to Login</a>
            </form>
        </div>
    </div>
    <?php endif; ?>
</body>
</html>