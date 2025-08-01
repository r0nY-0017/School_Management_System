<?php
session_start();
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
                            ' . $_SESSION['error'] . '
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

<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = mysqli_real_escape_string($conn, $_POST['identifier']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE admin_id = '$identifier' OR email = '$identifier'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = 'admin';
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password";
        }
    } else {
        $_SESSION['error'] = "Invalid Admin ID or Email";
    }
    
    header("Location: admin_login.php");
    exit();
}
?>