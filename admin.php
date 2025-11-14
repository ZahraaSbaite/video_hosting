<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') { header('Location: index.php'); exit; }

// users
$users = $conn->query('SELECT id, username, display_name, role, created_at FROM users ORDER BY id DESC')->fetch_all(MYSQLI_ASSOC);
$videos = $conn->query('SELECT v.id, v.video_name, v.video_id, u.username, v.created_at FROM videos v JOIN users u ON v.user_id = u.id ORDER BY v.created_at DESC')->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="layout">
    <aside class="sidenav">
      <div class="brand">VideoHost</div>
      <nav>
        <a href="home.php">Home</a>
        <a href="admin.php" class="active">Admin</a>
        <a href="logout.php">Logout</a>
      </nav>
    </aside>
    <main class="main-content">
      <h1>Admin Dashboard</h1>
      <section class="cards">
        <div class="stat">
          <h3><?php echo count($users); ?></h3>
          <p>Users</p>
        </div>
        <div class="stat">
          <h3><?php echo count($videos); ?></h3>
          <p>Videos</p>
        </div>
      </section>

      <section>
        <h2>Users</h2>
        <table class="admin-table">
          <tr><th>ID</th><th>Username</th><th>Display</th><th>Role</th><th>Joined</th><th>Action</th></tr>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?php echo $u['id']; ?></td>
              <td><?php echo htmlspecialchars($u['username']); ?></td>
              <td><?php echo htmlspecialchars($u['display_name']); ?></td>
              <td><?php echo $u['role']; ?></td>
              <td><?php echo $u['created_at']; ?></td>
              <td>
                <?php if ($u['role'] !== 'admin'): ?>
                  <a href="make_admin.php?id=<?php echo $u['id']; ?>">Make admin</a>
                <?php endif; ?>
                <a href="profile.php?id=<?php echo $u['id']; ?>">View</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      </section>

      <section>
        <h2>Recent Videos</h2>
        <table class="admin-table">
          <tr><th>ID</th><th>Title</th><th>Video ID</th><th>User</th><th>Uploaded</th></tr>
          <?php foreach ($videos as $v): ?>
            <tr>
              <td><?php echo $v['id']; ?></td>
              <td><?php echo htmlspecialchars($v['video_name']); ?></td>
              <td><?php echo htmlspecialchars($v['video_id']); ?></td>
              <td><?php echo htmlspecialchars($v['username']); ?></td>
              <td><?php echo $v['created_at']; ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </section>
    </main>
  </div>
</body>
</html>
