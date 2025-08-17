<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'school_management';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    die("Database connection failed: " . mysqli_connect_error());
}
?>