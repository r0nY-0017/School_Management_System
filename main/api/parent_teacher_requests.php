<?php
// Prevent any output before JSON response
error_reporting(0);
ini_set('display_errors', 0);

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

// Create teacher_requests table if it doesn't exist
$create_table_sql = "CREATE TABLE IF NOT EXISTS teacher_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id VARCHAR(50) NOT NULL,
    student_id VARCHAR(50) NOT NULL,
    teacher_id VARCHAR(50) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    message TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY parent_id (parent_id),
    KEY student_id (student_id),
    KEY teacher_id (teacher_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1";

mysqli_query($conn, $create_table_sql);

try {
    switch ($action) {
        case 'submit_request':
            $teacher_id = $_POST['teacher_id'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];
            
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
            
            $sql = "INSERT INTO teacher_requests (parent_id, student_id, teacher_id, subject, message) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $parent_id, $student_id, $teacher_id, $subject, $message);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true, 'message' => 'Teacher request submitted successfully']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to submit request']);
            }
            
            mysqli_stmt_close($stmt);
            mysqli_stmt_close($parent_stmt);
            break;
            
        case 'get_my_requests':
            $sql = "SELECT tr.*, t.name as teacher_name, t.subject as teacher_subject, s.name as student_name 
                    FROM teacher_requests tr 
                    LEFT JOIN teachers t ON tr.teacher_id = t.id 
                    LEFT JOIN students s ON tr.student_id = s.id 
                    WHERE tr.parent_id = ? 
                    ORDER BY tr.created_at DESC";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $parent_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $requests = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $requests[] = $row;
            }
            
            echo json_encode(['success' => true, 'requests' => $requests]);
            mysqli_stmt_close($stmt);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

mysqli_close($conn);
?>
