<?php
$host = 'localhost';
$db   = 'your_database_name';
$user = 'your_db_user';
$pass = 'your_db_password';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

/**
 * Reusable page protection function
 */
function check_auth($conn) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // If session is missing, check the persistent remember-me cookie
    if (!isset($_SESSION['public_id']) && isset($_COOKIE['remember_user'])) {
        $token = $_COOKIE['remember_user'];

        $stmt = $conn->prepare("
            SELECT u.public_id, u.name, u.email, u.picture 
            FROM user_tokens t 
            JOIN users u ON t.user_id = u.public_id 
            WHERE t.token = ? AND t.token_expiry > NOW()
        ");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            $_SESSION['public_id'] = $user['public_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_picture'] = $user['picture'];
        }
        $stmt->close();
    }

    // If still not logged in, redirect to login page
    if (!isset($_SESSION['public_id'])) {
        header("Location: login.php");
        exit();
    }
}
?>