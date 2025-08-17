<?php
session_start();
header('Content-Type: application/json');
require_once 'config/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

// Check database connection
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit();
}

try {
    $admin_id = $_SESSION['user_id'];
    $updates = [];
    $params = [];
    $types = '';
    
    // Process text fields
    $fields = [
        'name', 'email', 'phone', 'emergency_contact', 'national_id',
        'dob', 'gender', 'blood_group', 'qualification', 'address'
    ];
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $updates[] = "$field = ?";
            $params[] = $_POST[$field];
            $types .= 's';
        }
    }
    
    // Process password if provided
    if (!empty($_POST['password'])) {
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $updates[] = "password = ?";
        $params[] = $hashed_password;
        $types .= 's';
    }
    
    // Process profile image if uploaded
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "uploads/profiles/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $new_filename = "admin_" . $admin_id . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is valid
        $check = getimagesize($_FILES['profile_image']['tmp_name']);
        if ($check === false) {
            throw new Exception("File is not an image");
        }
        
        // Move uploaded file
        if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            throw new Exception("Failed to upload image");
        }
        
        $updates[] = "profile_image = ?";
        $params[] = $new_filename;
        $types .= 's';
    }
    
    // If no updates, return
    if (empty($updates)) {
        echo json_encode(['success' => true, 'message' => 'No changes detected']);
        exit();
    }
    
    // Build and execute the update query
    $sql = "UPDATE admins SET " . implode(', ', $updates) . " WHERE admin_id = ?";
    $params[] = $admin_id;
    $types .= 's';
    
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare update statement");
    }
    
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Failed to execute update");
    }
    
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    if (isset($stmt)) mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>