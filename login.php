<?php
header("Content-Type: application/json"); // Set response type to JSON
header("Access-Control-Allow-Origin: *"); // Allow external access

error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    if (empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Missing username or password"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "SQL error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            echo json_encode(["status" => "success", "user_id" => $id]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Use POST method for login"]);
}
?>
