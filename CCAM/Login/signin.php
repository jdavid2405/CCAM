<?php
session_start();
include '../Database/connect.php'; // Make sure this file opens and doesn't close $conn

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Fetch user (fix SQL: remove extra comma, use correct variable)
    $stmt = $conn->prepare("SELECT user_id, username, password, role FROM user WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        // Login success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect after login
        if (!empty($_GET['redirect'])) {
            $redirect_url = $_GET['redirect'];
            $allowed_pages = ['products.php', 'cart.php', 'add_to_cart.php', 'index.php'];
            $parsed = parse_url($redirect_url, PHP_URL_PATH);
            $redirect_page = basename($parsed);
            if (in_array($redirect_page, $allowed_pages)) {
                header("Location: $redirect_url");
                exit();
            }
        }

        // Redirect based on role
        if ($user['role'] == 'admin') {
            header("Location: ../Admin/admin.php");
            exit();
        } else {
            header("Location: ../Personal/DashPersonal.php");
            exit();
        }
    } else {
        $error = "Invalid email or password.";
    }
}

?>
