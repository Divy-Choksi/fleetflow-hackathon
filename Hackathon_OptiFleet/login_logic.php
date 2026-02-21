<?php
session_start();
require "dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            $update = $conn->prepare("UPDATE users SET last_login = NOW(), last_activity = NOW() WHERE user_id = :id");
            $update->bindParam(':id', $user['user_id']);
            $update->execute();

            header("Location: " . $user['role'] . "_dashboard.php");
            exit();

        } else {
            $_SESSION['message'] = "Invalid password.";
            $_SESSION['messageType'] = "error";
            header("Location: login.php");
            exit();
        }

    } else {
        $_SESSION['message'] = "User not found.";
        $_SESSION['messageType'] = "error";
        header("Location: login.php");
        exit();
    }
}
?>
