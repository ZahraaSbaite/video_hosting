<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user';

// Search & filter
$q = $_GET['q'] ?? '';
$filter = [];
$sql = "SELECT v.*, u.username FROM videos v JOIN users u ON v.user_id = u.id";
if ($q) {
  $sql .= " WHERE v.video_id LIKE ? OR v.video_name LIKE ? OR u.username LIKE ?";
  $term = '%'.$q.'%';
  $filter = [$term, $term, $term];
}
$sql .= " ORDER BY v.created_at DESC";

$stmt = $conn->prepare($sql);
if ($q) {
  $stmt->bind_param('sss', ...$filter);
}
$stmt->execute();
$res = $stmt->get_result();

$videos = $res->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Home - VideoHost</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="js/app.js" defer></script>
</head>
<body>
  <div class="layout">
    <aside class="sidenav">
      <div class="brand">VideoHost</div>
      <nav>
        <a href="home.php" class="active">Home</a>
        <a href="profile.php">Profile</a>
        <?php if ($role === 'admin'): ?>
          <a href="admin.php">Admin Dashboard</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
      </nav>
    </aside>

    <main class="main-content">
      <header class="topbar">
        <div class="search">
          <form method="get">
            <input name="q" placeholder="Search by id, name or user" value="<?php echo htmlspecialchars($q); ?>">
            <button type="submit">Search</button>
          </form>
        </div>
        <div class="user">Hello, <?php echo htmlspecialchars($_SESSION['display_name'] ?? 'User'); ?></div>
      </header>

      <section class="upload-section">
        <h2>Upload Video</h2>
        <form action="upload.php" method="post" enctype="multipart/form-data" class="upload-form">
          <input name="video_id" placeholder="Video ID (required)" required>
          <input name="video_name" placeholder="Video Title (required)" required>
          <textarea name="description" placeholder="Description" required></textarea>
          <input type="file" name="video_file" accept="video/*" required>
          <button type="submit">Upload</button>
        </form>
      </section>

      <section class="gallery">
        <h2>Videos</h2>
        <div class="grid">
          <?php foreach ($videos as $v): ?>
            <div class="card">
              <video class="thumb" src="<?php echo htmlspecialchars($v['file_path']); ?>" preload="metadata"></video>
              <div class="meta">
                <h3><?php echo htmlspecialchars($v['video_name']); ?></h3>
                <p class="small">ID: <?php echo htmlspecialchars($v['video_id']); ?> • By: <?php echo htmlspecialchars($v['username']); ?></p>
                <p><?php echo nl2br(htmlspecialchars($v['description'])); ?></p>
                <div class="card-actions">
                  <?php if ($v['user_id'] == $user_id || $role === 'admin'): ?>
                    <a href="edit_video.php?id=<?php echo $v['id']; ?>" class="btn">Edit</a>
                    <a href="delete_video.php?id=<?php echo $v['id']; ?>" class="btn danger" onclick="return confirm('Delete this video?');">Delete</a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
