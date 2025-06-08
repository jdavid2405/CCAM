<?php
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

if (is_numeric($id)) {
    $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid ID."]);
}

$conn->close();
?>
