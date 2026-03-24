<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// 5. Redirect to login page
// Change 'index.php' to your actual login page filename if different
header("Location: ../../index.php");
exit;