<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Default for XAMPP is an empty password
$dbname = 'gcam_db'; // Change this to your actual database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
