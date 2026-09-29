<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Redirecting...</title>
    <style>
        body {
            text-align: center;
            margin-top: 100px;
            font-family: Arial, sans-serif;
            color: #333;
            background: #f4f6f8;
        }
        a {
            color: #0066cc;
        }
        .info-box {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin: 25px auto;
            max-width: 500px;
            text-align: left;
            font-size: 13px;
            color: #555;
        }
        .info-box p {
            margin: 5px 0;
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
    <h2>Home Landing Page</h2>
    <p>If you are logged in, you will be redirected to the welcome page.</p>
    <p>If you are not logged in, you will be redirected to the login page.</p>

    <div class="info-box">
        
        
        <p><strong>Login Page:</strong> <code>login.php</code></p>
        <p><strong>Index Page:</strong> <code>index.php</code></p>
         <p><strong>Welcome Page:</strong> <code>welcome.php</code> </p>
        <p><strong>Redirect Page:</strong> <code>redirect.php</code> </p>
        <p><strong>Stay Logged In:</strong> Via Google for 2 days only.</p>
        <p><strong>Google Data Used:</strong> Profile Picture, Full Name, and Email are taken from your Google account.</p>
    </div>

    <h2>Redirecting in <span id="timer">5</span> seconds...</h2>
    <p>Please wait while we take you to your destination.</p>
    <p><a href="welcome.php">Click here</a> if you do not want to wait.</p>

    <script>
        let seconds = 5;
        function countdown() {
            document.getElementById('timer').innerText = seconds;
            if (seconds <= 0) {
                window.location.href = 'welcome.php';
            } else {
                seconds--;
                setTimeout(countdown, 1000);
            }
        }
        window.onload = countdown;
    </script>
</body>
</html>