<?php
// Start session
session_start();
require_once 'db_connect.php';

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}
// Fetch admin details
$user_id = $_SESSION['user_id'];
$sql = "SELECT full_name, admin_id, email FROM admins WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Fetch statistics (example data)
$total_students = mysqli_query($conn, "SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$total_teachers = mysqli_query($conn, "SELECT COUNT(*) as count FROM teachers")->fetch_assoc()['count'];
$total_parents = mysqli_query($conn, "SELECT COUNT(*) as count FROM parents")->fetch_assoc()['count'];

// Handle user addition (Student, Teacher, Parent)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $role = $_POST['role'];
    $identifier = $_POST['identifier'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $_POST['full_name'];

    $table = ($role === 'student') ? 'students' : (($role === 'teacher') ? 'teachers' : 'parents');
    $sql = "INSERT INTO $table (identifier, email, password, full_name) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $identifier, $email, $password, $full_name);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "User added successfully!";
    } else {
        $_SESSION['error'] = "Error adding user: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
    header("Location: admin_dashboard.php");
    exit();
}

include("html/admin_dashboard.html");
?>