<?php
session_start();
require_once '../config/db_connect.php';
header('Content-Type: application/json');

// Check if user is logged in as parent
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'parent') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

$action = $_POST['action'] ?? '';
$parent_id = $_SESSION['user_id'];

try {
    switch ($action) {
        case 'get_profile':
            $sql = "SELECT p.id, p.name, p.email, p.student_id, s.name as student_name, s.class, s.roll 
                    FROM parents p 
                    LEFT JOIN students s ON p.student_id = s.id 
                    WHERE p.id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $parent_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($parent = mysqli_fetch_assoc($result)) {
                echo json_encode(['success' => true, 'parent' => $parent]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Parent not found']);
            }
            mysqli_stmt_close($stmt);
            break;
            
        case 'update_profile':
            $name = $_POST['name'];
            $email = $_POST['email'];
            
            $sql = "UPDATE parents SET name = ?, email = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $parent_id);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update profile']);
            }
            mysqli_stmt_close($stmt);
            break;
            
        case 'update_student_info':
            $student_name = $_POST['student_name'];
            $student_class = $_POST['student_class'];
            $student_roll = $_POST['student_roll'];
            $student_email = $_POST['student_email'];
            
            // Get parent's student_id
            $parent_sql = "SELECT student_id FROM parents WHERE id = ?";
            $parent_stmt = mysqli_prepare($conn, $parent_sql);
            mysqli_stmt_bind_param($parent_stmt, "s", $parent_id);
            mysqli_stmt_execute($parent_stmt);
            $parent_result = mysqli_stmt_get_result($parent_stmt);
            $parent_data = mysqli_fetch_assoc($parent_result);
            
            if (!$parent_data) {
                echo json_encode(['success' => false, 'error' => 'Parent data not found']);
                break;
            }
            
            $student_id = $parent_data['student_id'];
            
            $sql = "UPDATE students SET name = ?, class = ?, roll = ?, email = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $student_name, $student_class, $student_roll, $student_email, $student_id);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true, 'message' => 'Student information updated successfully']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update student information']);
            }
            
            mysqli_stmt_close($stmt);
            mysqli_stmt_close($parent_stmt);
            break;
            
        case 'change_password':
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            
            // Verify current password
            $sql = "SELECT password FROM parents WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $parent_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $parent = mysqli_fetch_assoc($result);
            
            if (!password_verify($current_password, $parent['password'])) {
                echo json_encode(['success' => false, 'error' => 'Current password is incorrect']);
                mysqli_stmt_close($stmt);
                break;
            }
            
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE parents SET password = ? WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($update_stmt, "ss", $hashed_password, $parent_id);
            
            if (mysqli_stmt_execute($update_stmt)) {
                echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to change password']);
            }
            
            mysqli_stmt_close($stmt);
            mysqli_stmt_close($update_stmt);
            break;
            
        case 'get_all_teachers':
            $sql = "SELECT id, name, subject, email FROM teachers ORDER BY name";
            $result = mysqli_query($conn, $sql);
            $teachers = [];
            
            while ($row = mysqli_fetch_assoc($result)) {
                $teachers[] = $row;
            }
            
            echo json_encode(['success' => true, 'teachers' => $teachers]);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

mysqli_close($conn);
?>
