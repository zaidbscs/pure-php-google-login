CREATE TABLE IF NOT EXISTS `users` (
  `public_id` VARCHAR(64) PRIMARY KEY,          -- Secure random string acts as the primary identifier
  `google_id` VARCHAR(255) NOT NULL UNIQUE,     -- Google's permanent unique ID
  `name` VARCHAR(255) NOT NULL,                 -- Full name from Google
  `email` VARCHAR(255) NOT NULL,
  `picture` VARCHAR(500) DEFAULT NULL,          -- Google profile image URL
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` VARCHAR(64) NOT NULL,               -- Links to the public_id in users table
  `token` VARCHAR(255) NOT NULL,
  `token_expiry` DATETIME NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`public_id`) ON DELETE CASCADE
);