<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
$uid = intval($_GET['id'] ?? $_SESSION['user_id']);
$stmt = $conn->prepare('SELECT id, username, display_name, role, created_at FROM users WHERE id = ?');
$stmt->bind_param('i', $uid);
$stmt->execute();
$u = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Profile</title><link rel="stylesheet" href="css/style.css"></head>
<body>
  <div class="layout">
    <aside class="sidenav">
      <div class="brand">VideoHost</div>
      <nav>
        <a href="home.php">Home</a>
        <a href="profile.php" class="active">Profile</a>
        <a href="logout.php">Logout</a>
      </nav>
    </aside>
    <main class="main-content">
      <h2>Profile</h2>
      <div class="profile-card">
        <p><strong>Username:</strong> <?php echo htmlspecialchars($u['username']); ?></p>
        <p><strong>Display name:</strong> <?php echo htmlspecialchars($u['display_name']); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($u['role']); ?></p>
        <p><strong>Joined:</strong> <?php echo htmlspecialchars($u['created_at']); ?></p>
      </div>
    </main>
  </div>
</body></html>
