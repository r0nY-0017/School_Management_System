<?php
session_start();
require_once '../config/db_connect.php';
header('Content-Type: application/json');

// Check if user is logged in as teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'add_student':
            $student_id = $_POST['student_id'];
            $name = $_POST['name'];
            $class = $_POST['class'];
            $roll = $_POST['roll'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            // Check if student ID already exists
            $check_sql = "SELECT id FROM students WHERE id = ?";
            $check_stmt = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "s", $student_id);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            
            if (mysqli_num_rows($check_result) > 0) {
                echo json_encode(['success' => false, 'error' => 'Student ID already exists']);
                break;
            }
            
            $sql = "INSERT INTO students (id, name, class, roll, email, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssss", $student_id, $name, $class, $roll, $email, $password);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true, 'message' => 'Student added successfully']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to add student']);
            }
            mysqli_stmt_close($stmt);
            mysqli_stmt_close($check_stmt);
            break;
            
        case 'delete_student':
            $student_id = $_POST['student_id'];
            
            $sql = "DELETE FROM students WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $student_id);
            
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    echo json_encode(['success' => true, 'message' => 'Student deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Student not found']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to delete student']);
            }
            mysqli_stmt_close($stmt);
            break;
            
        case 'get_students':
            $sql = "SELECT id, name, class, roll, email FROM students ORDER BY class, roll";
            $result = mysqli_query($conn, $sql);
            $students = [];
            
            while ($row = mysqli_fetch_assoc($result)) {
                $students[] = $row;
            }
            
            echo json_encode(['success' => true, 'students' => $students]);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

mysqli_close($conn);
?>
