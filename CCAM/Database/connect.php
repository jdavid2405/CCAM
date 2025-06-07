<?php 
    $hostname = "localhost";
    $username = "root"; // Change this to your MySQL username
    $password = ""; // Change this to your MySQL password      
    $database = "ccam_db"; // Change this to your database name
    $conn = mysqli_connect($hostname, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>