<?php
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'];
$date = $data['date'];
$amount = $data['amount'];

if ($name && $date && is_numeric($amount)) {
    $stmt = $conn->prepare("INSERT INTO expenses (name, date, amount) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $name, $date, $amount);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid input."]);
}

$conn->close();
?>
