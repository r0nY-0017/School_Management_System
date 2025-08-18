<?php
session_start();
require_once '../config/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

$student_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'get_profile') {
    $sql = "SELECT id, name, email, class, roll, phone, address, dob, gender, guardian_name, guardian_phone, admission_date, status FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);

    if ($student) {
        echo json_encode(['success' => true, 'student' => $student]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Student not found']);
    }
    mysqli_stmt_close($stmt);
} elseif ($action === 'update_profile') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $class = $_POST['class'] ?? '';
    $roll = $_POST['roll'] ?? '';
    $phone = $_POST['phone'] ?? null;
    $address = $_POST['address'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $guardian_name = $_POST['guardian_name'] ?? null;
    $guardian_phone = $_POST['guardian_phone'] ?? null;

    // Validate required fields
    if (empty($name) || empty($email) || empty($class) || empty($roll)) {
        echo json_encode(['success' => false, 'error' => 'Name, email, class, and roll are required']);
        exit();
    }

    // Check for unique class+roll and class+email, excluding the current student
    $check_sql = "SELECT id FROM students WHERE (class = ? AND roll = ? OR class = ? AND email = ?) AND id != ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "sssss", $class, $roll, $class, $email, $student_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
        echo json_encode(['success' => false, 'error' => 'Class and roll number or class and email already exists']);
        mysqli_stmt_close($check_stmt);
        exit();
    }
    mysqli_stmt_close($check_stmt);

    // Update student profile
    $sql = "UPDATE students SET name = ?, email = ?, class = ?, roll = ?, phone = ?, address = ?, dob = ?, gender = ?, guardian_name = ?, guardian_phone = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssss", $name, $email, $class, $roll, $phone, $address, $dob, $gender, $guardian_name, $guardian_phone, $student_id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update profile']);
    }
    mysqli_stmt_close($stmt);
} elseif ($action === 'change_password') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    // Validate passwords
    if (empty($current_password) || empty($new_password)) {
        echo json_encode(['success' => false, 'error' => 'Both current and new passwords are required']);
        exit();
    }

    // Verify current password
    $sql = "SELECT password FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);

    if (password_verify($current_password, $student['password'])) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE students SET password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $student_id);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update password']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Incorrect current password']);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

mysqli_close($conn);
?>