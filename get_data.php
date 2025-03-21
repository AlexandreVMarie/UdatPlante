<?php
include 'db_config.php';

$user_id = $_GET["user_id"]; // Get user_id from request
$result = $conn->query("SELECT p.plant_name, s.moisture, s.timestamp 
                        FROM sensor_data s
                        JOIN plants p ON s.plant_id = p.id
                        WHERE s.user_id = $user_id
                        ORDER BY s.timestamp DESC LIMIT 10");

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>

