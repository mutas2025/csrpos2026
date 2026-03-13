<?php
// logout.php

// 1. Start the session (if not already started)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Unset all of the session variables
 $_SESSION = array();

// 3. Destroy the session cookie (optional but recommended for security)
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finally, destroy the session.
session_destroy();

// 5. Redirect to login page
// Change 'index.php' to your actual login page filename if different
header("Location: ../../index.php");
exit;
?>