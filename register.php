<?php
require 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  $display = $_POST['display_name'] ?? '';

  if (strlen($username) < 3 || strlen($password) < 4) {
    $error = 'Username or password is too short';
  } else {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO users (username, password, display_name) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $username, $hash, $display);
    if ($stmt->execute()) {
      header('Location: index.php'); exit;
    } else {
      $error = 'Username already exists';
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register - VideoHost</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">
  <div class="auth-card">
    <h1>Create account</h1>
    <form method="post">
      <input name="username" placeholder="Username" required>
      <input name="display_name" placeholder="Display name (optional)">
      <input name="password" type="password" placeholder="Password" required>
      <button type="submit">Register</button>
    </form>
    <p>Already registered? <a href="index.php">Login</a></p>
    <?php if ($error) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
  </div>
</body>
</html>
