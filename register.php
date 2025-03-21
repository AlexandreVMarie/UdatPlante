<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";
    $email = $_POST["email"] ?? "";
    $phone = $_POST["phone"] ?? "";

    if (empty($username) || empty($password) || empty($email) || empty($phone)) {
        die(json_encode(["status" => "error", "message" => "Missing required fields."]));
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die(json_encode(["status" => "error", "message" => "SQL error: " . $conn->error]));
    }

    $stmt->bind_param("ssss", $username, $hashed_password, $email, $phone);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        die(json_encode(["status" => "error", "message" => "Database Insert Error: " . $stmt->error]));
    }
} else {
    // If accessed via a browser (GET request), show this message
    echo "🚀 This is the registration API. Please send a POST request with 'username', 'password', 'email', and 'phone' fields.";
}
?>

