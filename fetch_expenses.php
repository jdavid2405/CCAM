<?php
require 'db.php';

$result = $conn->query("SELECT * FROM expenses ORDER BY date DESC");

$expenses = [];

while ($row = $result->fetch_assoc()) {
    $expenses[] = $row;
}

echo json_encode($expenses);

$conn->close();
?>
