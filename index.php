<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_id'])) {
  header('Location: home.php'); exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $stmt = $conn->prepare('SELECT id, password, role, display_name FROM users WHERE username = ?');
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($user = $res->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['role'] = $user['role'];
      $_SESSION['display_name'] = $user['display_name'] ?? $username;
      header('Location: home.php'); exit;
    }
  }
  $error = 'Invalid username or password';
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - VideoHost</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">
  <div class="auth-card">
    <h1>VideoHost</h1>
    <form method="post">
      <input name="username" placeholder="Username" required>
      <input name="password" type="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
    <?php if ($error) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
  </div>
</body>
</html>
