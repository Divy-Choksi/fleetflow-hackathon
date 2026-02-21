<?php
session_start();
require "../dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (!$fullName || !$email || !$phone || !$role || !$password || !$confirmPassword) {
        $_SESSION['reg_error'] = "All fields are required.";
        header("Location: manager_registration.php");
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['reg_error'] = "Passwords do not match.";
        header("Location: manager_registration.php");
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['reg_error'] = "Password must be at least 8 characters.";
        header("Location: manager_registration.php");
        exit();
    }

    $check = $conn->prepare("SELECT user_id FROM users WHERE email = :email");
    $check->bindParam(':email', $email);
    $check->execute();

    if ($check->rowCount() > 0) {
        $_SESSION['reg_error'] = "Email already registered.";
        header("Location: manager_registration.php");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO users (email, password, role)
        VALUES (:email, :password, :role)
    ");

    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':role', $role);
    $stmt->execute();

    $userId = $conn->lastInsertId();

    if ($role == 'dispatcher') {
        $stmt2 = $conn->prepare("INSERT INTO dispatchers (user_id, full_name, phone) VALUES (:user_id, :full_name, :phone)");
    } elseif ($role == 'safety_officer') {
        $stmt2 = $conn->prepare("INSERT INTO safety_officers (user_id, full_name, phone) VALUES (:user_id, :full_name, :phone)");
    } elseif ($role == 'finance_officer') {
        $stmt2 = $conn->prepare("INSERT INTO finance_officers (user_id, full_name, phone) VALUES (:user_id, :full_name, :phone)");
    }

    $stmt2->bindParam(':user_id', $userId);
    $stmt2->bindParam(':full_name', $fullName);
    $stmt2->bindParam(':phone', $phone);
    $stmt2->execute();

    $_SESSION['reg_success'] = "Account created successfully!";
    header("Location: manager_registration.php");
    exit();
}
?>