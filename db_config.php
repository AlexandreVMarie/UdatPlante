<?php
$servername = "sql204.infinityfree.com"; // Replace with your InfinityFree hostname
$username = "if0_38571533"; // Your database username
$password = "47Jj9hAIJe"; // Your database password
$database = "if0_38571533_plant_monitoring"; // Your full database name

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
