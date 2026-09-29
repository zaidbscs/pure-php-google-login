<?php
require_once 'config.php';
check_auth($conn); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #4285F4;
            margin-bottom: 15px;
        }
        h1 {
            margin: 10px 0;
            font-size: 24px;
        }
        p {
            margin: 5px 0;
            color: #555;
        }
        .info-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            font-size: 14px;
        }
        .info-box strong {
            color: #111;
        }
        .logout-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 25px;
            background: #dc3545;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.2s;
        }
        .logout-btn:hover {
            background: #b02a37;
        }
        code {
            background: #eef2f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Display Google Profile Picture -->
        <?php if (!empty($_SESSION['user_picture'])): ?>
            <img class="profile-pic" src="<?php echo htmlspecialchars($_SESSION['user_picture']); ?>" alt="Profile Picture">
        <?php endif; ?>

        <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p>Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>

        <div class="info-box">
            <p><strong>Website:</strong> <?php echo htmlspecialchars($_SERVER['HTTP_HOST']); ?></p>
            <p><strong>Current URL:</strong> <code><?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?></code></p>
            <p><strong>Login Page:</strong> <code>login.php</code></p>
            <p><strong>Index Page:</strong> <code>index.php</code></p>
            <p><strong>Welcome Page:</strong> <code>welcome.php</code></p>
            <p><strong>Redirect URL:</strong> <code>redirect.php</code></p>
            <p><strong>Stay Logged In:</strong> Via Google for 2 days only.</p>
            <p><strong>Google Data Used:</strong> Profile Picture, Full Name, and Email are taken from your Google account.</p>
        </div>

        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>