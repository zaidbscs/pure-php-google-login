<?php
session_start();
require_once 'config.php';

if (isset($_COOKIE['remember_user'])) {
    $token = $_COOKIE['remember_user'];
    
    // Remove token for this specific device from database
    $stmt = $conn->prepare("DELETE FROM user_tokens WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->close();

    // Clear browser cookie
    setcookie('remember_user', '', time() - 3600, "/");
}

// Clear session data
session_unset();
session_destroy();

header("Location: login.php");
exit();
?>