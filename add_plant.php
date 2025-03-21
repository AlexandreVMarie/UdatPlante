<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $plant_name = $_POST["plant_name"];
    $moisture_threshold = $_POST["moisture_threshold"];

    $stmt = $conn->prepare("INSERT INTO plants (user_id, plant_name, moisture_threshold) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $user_id, $plant_name, $moisture_threshold);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add plant"]);
    }
}
?>
