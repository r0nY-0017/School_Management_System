<?php


// Check if the user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to access your profile.";
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
$admin_data = [];
$error = '';
$success = '';

// Fetch admin data
$sql = "SELECT admin_id, name, email, phone, dob, gender, join_date, address, emergency_contact, national_id, qualification, blood_group, profile_image FROM admins WHERE admin_id = ?";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $admin_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin_data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
} else {
    $error = "Failed to fetch profile data. Please try again.";
}

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['adminName'] ?? '');
    $email = trim($_POST['adminEmail'] ?? '');
    $phone = trim($_POST['adminPhone'] ?? '');
    $dob = $_POST['adminDob'] ?? '';
    $gender = $_POST['adminGender'] ?? '';
    $join_date = $_POST['adminJoinDate'] ?? '';
    $address = trim($_POST['adminAddress'] ?? '');
    $emergency_contact = trim($_POST['adminEmergencyContact'] ?? '');
    $national_id = trim($_POST['adminNationalId'] ?? '');
    $qualification = trim($_POST['adminQualification'] ?? '');
    $blood_group = $_POST['adminBloodGroup'] ?? '';
    $password = $_POST['adminPassword'] ?? '';
    $confirm_password = $_POST['adminConfirmPassword'] ?? '';

    // Validate input
    if (empty($name) || empty($email)) {
        $error = "Name and Email are required.";
    } elseif ($password && $password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Handle profile image upload
        $profile_image = $admin_data['profile_image'];
        if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['profileImage']['tmp_name'];
            $file_name = $_FILES['profileImage']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($file_ext, $allowed_exts)) {
                $upload_dir = 'uploads/profile_images/';
                $new_file_name = $admin_id . '_' . time() . '.' . $file_ext;
                if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                    $profile_image = $upload_dir . $new_file_name;
                } else {
                    $error = "Failed to upload profile image.";
                }
            } else {
                $error = "Invalid image format. Allowed formats: JPG, PNG, GIF.";
            }
        }

        // Prepare update query
        $sql = "UPDATE admins SET name = ?, email = ?, phone = ?, dob = ?, gender = ?, join_date = ?, address = ?, emergency_contact = ?, national_id = ?, qualification = ?, blood_group = ?, profile_image = ?";
        $params = [$name, $email, $phone, $dob, $gender, $join_date, $address, $emergency_contact, $national_id, $qualification, $blood_group, $profile_image];
        $types = "ssssssssssss";

        // Handle password update if provided
        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = ?";
            $params[] = $hashed_password;
            $types .= "s";
        }

        $sql .= " WHERE admin_id = ?";
        $params[] = $admin_id;
        $types .= "s";

        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            if (mysqli_stmt_execute($stmt)) {
                $success = "Profile updated successfully.";
                // Refresh admin data
                $sql = "SELECT admin_id, name, email, phone, dob, gender, join_date, address, emergency_contact, national_id, qualification, blood_group, profile_image FROM admins WHERE admin_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $admin_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $admin_data = mysqli_fetch_assoc($result);
            } else {
                $error = "Failed to update profile. Please try again.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Failed to prepare update query. Please try again.";
        }
    }
}
?>