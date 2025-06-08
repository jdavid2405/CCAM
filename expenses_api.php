<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";     // Change if you use a different username
$password = "";         // Change if you have a password
$dbname = "gcam";       // Your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT * FROM expenses ORDER BY date DESC");
        $expenses = [];
        while ($row = $result->fetch_assoc()) {
            $expenses[] = $row;
        }
        echo json_encode($expenses);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $conn->real_escape_string($data['name']);
        $date = $conn->real_escape_string($data['date']);
        $amount = floatval($data['amount']);

        $stmt = $conn->prepare("INSERT INTO expenses (name, date, amount) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $name, $date, $amount);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "id" => $stmt->insert_id]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }
        $stmt->close();
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = intval($data['id']);
        $name = $conn->real_escape_string($data['name']);
        $date = $conn->real_escape_string($data['date']);
        $amount = floatval($data['amount']);

        $stmt = $conn->prepare("UPDATE expenses SET name=?, date=?, amount=? WHERE id=?");
        $stmt->bind_param("ssdi", $name, $date, $amount, $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $id = intval($data['id']);

        $stmt = $conn->prepare("DELETE FROM expenses WHERE id=?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }
        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}

$conn->close();
?>
