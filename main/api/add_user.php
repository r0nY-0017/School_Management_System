<?php
require_once '../config/db_connect.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$role = $input['role'];
$id = $input['id'];
$name = $input['name'];
$email = $input['email'];
$password = password_hash($input['password'], PASSWORD_DEFAULT);

try {
    if ($role === 'student') {
        $class = $input['class'];
        $roll = $input['roll'];
        $sql = "INSERT INTO students (id, name, class, roll, email, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $id, $name, $class, $roll, $email, $password);
    } elseif ($role === 'teacher') {
        $subject = $input['subject'];
        $sql = "INSERT INTO teachers (id, name, subject, email, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $id, $name, $subject, $email, $password);
    } elseif ($role === 'parent') {
        $student_id = $input['student_id'];
        $sql = "INSERT INTO parents (id, name, student_id, email, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $id, $name, $student_id, $email, $password);
    } else {
        throw new Exception("Invalid role");
    }

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception("Error adding user: " . mysqli_error($conn));
    }
    mysqli_stmt_close($stmt);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
mysqli_close($conn);
?>