<?php
// Prevent any output before JSON response
error_reporting(0);
ini_set('display_errors', 0);

session_start();
require_once '../config/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'parent') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

$parent_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'get_profile') {
    try {
        $sql = "SELECT id, name, email, student_id FROM parents WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . mysqli_error($conn)]);
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $parent_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $parent = mysqli_fetch_assoc($result);

        if ($parent) {
            // Add default values for missing columns
            $parent['phone'] = '';
            $parent['address'] = '';
            $parent['occupation'] = '';
            $parent['relation'] = '';
            echo json_encode(['success' => true, 'parent' => $parent]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Parent not found']);
        }
        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
} elseif ($action === 'update_profile') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? null;
    $address = $_POST['address'] ?? null;
    $occupation = $_POST['occupation'] ?? null;

    // Validate required fields
    if (empty($name) || empty($email)) {
        echo json_encode(['success' => false, 'error' => 'Name and email are required']);
        exit();
    }

    // Check for unique email, excluding the current parent
    $check_sql = "SELECT id FROM parents WHERE email = ? AND id != ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ss", $email, $parent_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
        echo json_encode(['success' => false, 'error' => 'Email already exists']);
        mysqli_stmt_close($check_stmt);
        exit();
    }
    mysqli_stmt_close($check_stmt);

    // Update parent profile (only name and email since other columns don't exist in database)
    $sql = "UPDATE parents SET name = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $parent_id);

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
    $sql = "SELECT password FROM parents WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $parent_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $parent = mysqli_fetch_assoc($result);

    if (password_verify($current_password, $parent['password'])) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE parents SET password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $parent_id);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update password']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Incorrect current password']);
    }
    mysqli_stmt_close($stmt);
} elseif ($action === 'get_student_info') {
    $sql = "SELECT id, name, email, class, roll FROM students WHERE id = (SELECT student_id FROM parents WHERE id = ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $parent_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);

    if ($student) {
        echo json_encode(['success' => true, 'student' => $student]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Student not found']);
    }
    mysqli_stmt_close($stmt);
} elseif ($action === 'update_student_info') {
    $student_id = $_POST['student_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $class = $_POST['class'] ?? '';
    $roll = $_POST['roll'] ?? '';

    // Validate required fields
    if (empty($student_id) || empty($name) || empty($email) || empty($class) || empty($roll)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required']);
        exit();
    }

    // Verify the student belongs to this parent
    $check_parent_sql = "SELECT id FROM parents WHERE id = ? AND student_id = ?";
    $check_parent_stmt = mysqli_prepare($conn, $check_parent_sql);
    mysqli_stmt_bind_param($check_parent_stmt, "ss", $parent_id, $student_id);
    mysqli_stmt_execute($check_parent_stmt);
    $check_parent_result = mysqli_stmt_get_result($check_parent_stmt);

    if (mysqli_num_rows($check_parent_result) === 0) {
        echo json_encode(['success' => false, 'error' => 'Unauthorized to update this student']);
        mysqli_stmt_close($check_parent_stmt);
        exit();
    }
    mysqli_stmt_close($check_parent_stmt);

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

    // Update student info
    $sql = "UPDATE students SET name = ?, email = ?, class = ?, roll = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $class, $roll, $student_id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update student info']);
    }
    mysqli_stmt_close($stmt);
} elseif ($action === 'get_all_teachers') {
    try {
        $sql = "SELECT id, name, subject FROM teachers";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . mysqli_error($conn)]);
            exit();
        }
        $teachers = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $teachers[] = $row;
        }

        echo json_encode(['success' => true, 'teachers' => $teachers]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

mysqli_close($conn);
?>