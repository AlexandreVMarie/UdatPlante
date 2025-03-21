<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $plant_id = $_POST["plant_id"];
    $moisture = $_POST["moisture"];

    $stmt = $conn->prepare("INSERT INTO sensor_data (user_id, plant_id, moisture) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $plant_id, $moisture);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save moisture data"]);
    }
}
?>

