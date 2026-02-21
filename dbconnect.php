<?php
$host = "localhost";
$dbname = "fleetflow";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed",
        "error" => $e->getMessage()
    ]));
}
?>
