<?php
require_once 'config.php';

$clientID = 'YOUR_GOOGLE_CLIENT_ID';
$clientSecret = 'YOUR_GOOGLE_CLIENT_SECRET';
$redirectUri = 'https://yoursite.com/redirect.php'; // Must match Google Console 

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // 1. Exchange authorization code for access token via cURL
    $tokenUrl = 'https://oauth2.googleapis.com/token';
    $tokenData = [
        'code' => $code,
        'client_id' => $clientID,
        'client_secret' => $clientSecret,
        'redirect_uri' => $redirectUri,
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $tokenResponse = json_decode($response, true);

    if (isset($tokenResponse['access_token'])) {
        $accessToken = $tokenResponse['access_token'];

        // 2. Fetch user profile info from Google API using the access token
        $userInfoUrl = 'https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $accessToken;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $userResponse = curl_exec($ch);
        curl_close($ch);

        $google_account_info = json_decode($userResponse, true);

        if (isset($google_account_info['id'])) {
            $google_id = $google_account_info['id'];
            $email = $google_account_info['email'];
            $name = $google_account_info['name'];
            $picture = $google_account_info['picture'] ?? '';

            // 3. Check if user already exists
            $stmt = $conn->prepare("SELECT public_id FROM users WHERE google_id = ?");
            $stmt->bind_param("s", $google_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $public_id = $row['public_id'];

                // Update name/picture if changed on Google
                $update_stmt = $conn->prepare("UPDATE users SET name = ?, picture = ? WHERE public_id = ?");
                $update_stmt->bind_param("sss", $name, $picture, $public_id);
                $update_stmt->execute();
                $update_stmt->close();
            } else {
                // Generate a secure unguessable public string ID
                $public_id = bin2hex(random_bytes(16));

                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (public_id, google_id, name, email, picture) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $public_id, $google_id, $name, $email, $picture);
                $stmt->execute();
            }
            $stmt->close();

            // 4. Generate persistent token for this specific device
            $remember_token = bin2hex(random_bytes(32));
            
            // Expiration configuration (2 Days)
            $expiry_seconds = time() + (2 * 24 * 60 * 60); 
            $expiry_datetime = date('Y-m-d H:i:s', $expiry_seconds);

            // Save token linked directly to public_id
            $stmt = $conn->prepare("INSERT INTO user_tokens (user_id, token, token_expiry) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $public_id, $remember_token, $expiry_datetime);
            $stmt->execute();
            $stmt->close();

            // Set secure persistent cookie on browser
            setcookie('remember_user', $remember_token, $expiry_seconds, "/", "", true, true);

            // Start session using the secure public ID
            session_start();
            $_SESSION['public_id'] = $public_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_picture'] = $picture;

            header("Location: welcome.php");
            exit();
        } else {
            echo "Failed to fetch user profile information from Google.";
        }
    } else {
        echo "Failed to retrieve access token from Google.";
    }
} else {
    header("Location: login.php");
    exit();
}
?>