<?php
session_start();

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Clear browser cache and redirect to home page
header("Location: index.php");
exit();
?>

<!DOCTYPE html>
<html>
<head>
    <script>
        // Clear browser history and prevent going back
        if (typeof history.pushState === "function") {
            history.pushState("jibberish", null, null);
            window.onpopstate = function () {
                history.pushState('newjibberish', null, null);
            };
        }
        // Redirect to home page
        window.location.replace('index.php');
    </script>
</head>
<body>
    <p>Logging out...</p>
</body>
</html>
