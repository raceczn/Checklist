<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "your_password"; // Replace 'your_password' with your actual MySQL root password
$db_name = "checklist";

// Establish connection
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
