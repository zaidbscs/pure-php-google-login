<?php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Check if the user is already logged in via session or cookie
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

// 2. If they are ALREADY logged in, skip the login screen and send them to the dashboard
if (isset($_SESSION['public_id'])) {
    header("Location: welcome.php");
    exit();
}

// 3. Otherwise, show the normal Google login page

$clientID = 'YOUR_GOOGLE_CLIENT_ID';
$redirectUri = 'https://yoursite.com/redirect.php'; // Update with your actual redirect URL
 

$params = [
    'response_type' => 'code',
    'client_id'     => $clientID,
    'redirect_uri'  => $redirectUri,
    'scope'         => 'email profile',
    'access_type'   => 'online',
    'prompt'        => 'select_account'
];

$login_url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login with Google</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .login-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h2 {
            margin: 0 0 10px;
            font-size: 22px;
        }
        p {
            color: #555;
            margin-bottom: 25px;
            font-size: 14px;
        }
        .google-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: #4285F4;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 15px;
            transition: background 0.2s;
            width: 100%;
            box-sizing: border-box;
        }
        .google-btn:hover {
            background: #3367d6;
        }
        .info-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-top: 25px;
            text-align: left;
            font-size: 13px;
            color: #555;
        }
        .info-box p {
            margin: 5px 0;
            color: #555;
        }
        code {
            background: #eef2f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Welcome to Our Website</h2>
        <p>Please sign in with your Google account to continue</p>

        <a href="<?php echo htmlspecialchars($login_url); ?>" class="google-btn">
            <svg width="18" height="18" viewBox="0 0 48 48">
                <path fill="#fff" d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z"/>
            </svg>
            Login with Google
        </a>

        <div class="info-box">
            <p><strong>Login Page:</strong> <code>login.php</code></p>
            <p><strong>Index Page:</strong> <code>index.php</code></p>
            <p><strong>Welcome Page:</strong> <code>welcome.php</code></p>
            <p><strong>Redirect URL:</strong> <code>redirect.php</code></p>
            <p><strong>Stay Logged In:</strong> Via Google for 2 days only.</p>
            <p><strong>Google Data Used:</strong> Profile Picture, Full Name, and Email are taken from your Google account.</p>
        </div>
    </div>
</body>
</html>