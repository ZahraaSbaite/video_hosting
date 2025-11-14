CREATE DATABASE IF NOT EXISTS video_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE video_hosting;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  display_name VARCHAR(255) DEFAULT NULL,
  role ENUM('user','admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS videos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  video_id VARCHAR(100) NOT NULL,
  video_name VARCHAR(255) NOT NULL,
  description TEXT,
  file_path VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Example admin account (password is 'admin123')
INSERT INTO users (username, password, display_name, role)
VALUES ('admin', '$2y$10$XrX8k1B1pVh0sG2Qx6s2pexQwQ3g9r0Zx1JQkV4e6Yq2c3rT1mF6e', 'Site Admin', 'admin');
