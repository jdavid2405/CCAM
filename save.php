<?php
// save.php

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";  // default for XAMPP, change if you use something else
$dbname = "gcam";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Get POST data and sanitize
$name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
$date = isset($_POST['date']) ? $conn->real_escape_string($_POST['date']) : '';
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

if (empty($name) || empty($date) || $amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO expenses (name, date, amount) VALUES (?, ?, ?)");
$stmt->bind_param("ssd", $name, $date, $amount);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Expense saved']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save expense']);
}

$stmt->close();
$conn->close();
?>
