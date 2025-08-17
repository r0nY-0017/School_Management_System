<?php
session_start();
require_once '../config/db_connect.php';
header('Content-Type: application/json');

// Check if user is logged in as student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

$action = $_POST['action'] ?? '';
$student_id = $_SESSION['user_id'];

try {
    switch ($action) {
        case 'get_profile':
            $sql = "SELECT id, name, class, roll, email FROM students WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $student_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($student = mysqli_fetch_assoc($result)) {
                echo json_encode(['success' => true, 'student' => $student]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Student not found']);
            }
            mysqli_stmt_close($stmt);
            break;
            
        case 'update_profile':
            $name = $_POST['name'];
            $class = $_POST['class'];
            $roll = $_POST['roll'];
            $email = $_POST['email'];
            
            $sql = "UPDATE students SET name = ?, class = ?, roll = ?, email = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $name, $class, $roll, $email, $student_id);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update profile']);
            }
            mysqli_stmt_close($stmt);
            break;
            
        case 'change_password':
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            
            // Verify current password
            $sql = "SELECT password FROM students WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $student_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $student = mysqli_fetch_assoc($result);
            
            if (!password_verify($current_password, $student['password'])) {
                echo json_encode(['success' => false, 'error' => 'Current password is incorrect']);
                mysqli_stmt_close($stmt);
                break;
            }
            
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE students SET password = ? WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($update_stmt, "ss", $hashed_password, $student_id);
            
            if (mysqli_stmt_execute($update_stmt)) {
                echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to change password']);
            }
            
            mysqli_stmt_close($stmt);
            mysqli_stmt_close($update_stmt);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

mysqli_close($conn);
?>
