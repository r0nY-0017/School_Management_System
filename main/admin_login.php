<?php
session_start();
include 'config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        $_SESSION['error'] = "Please enter both Admin ID/Email and Password";
        header("Location: admin_login.php");
        exit();
    }

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT admin_id, password FROM admins WHERE admin_id = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        $_SESSION['error'] = "Database error. Please try again later.";
        header("Location: admin_login.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $identifier, $identifier);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $row['password'])) {
            // Login successful
            $_SESSION['user_id'] = $row['admin_id']; // Fixed to use admin_id
            session_regenerate_id(true); // Prevent session fixation
            
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password";
        }
    } else {
        $_SESSION['error'] = "Invalid Admin ID or Email";
    }
    
    mysqli_stmt_close($stmt);
    header("Location: admin_login.php"); // Fixed redirect to login page on failure
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h1 class="login-header">Admin Login</h1>
            <div class="login-form-container">

                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="error-msg">
                            ' . htmlspecialchars($_SESSION['error']) . '
                            <button class="close-btn">&times;</button>
                          </div>';
                    unset($_SESSION['error']);
                }
                ?>

                <form action="admin_login.php" method="POST" class="login-form">
                    <div class="input-group">
                        <label for="identifier"><i class="fas fa-user"></i> Admin ID or Email</label>
                        <input type="text" name="identifier" id="identifier" placeholder="Enter Admin ID or Email" required>
                    </div>
                    <div class="input-group">
                        <label for="password"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter Password" required>
                    </div>
                    <button type="submit">Login</button>
                </form>

            </div>
        </div>
    </div>

    <!-- JavaScript to close error message -->
    <script>
        document.querySelectorAll('.close-btn').forEach(button => {
            button.addEventListener('click', () => {
                button.parentElement.style.display = 'none';
            });
        });
    </script>
</body>
</html>