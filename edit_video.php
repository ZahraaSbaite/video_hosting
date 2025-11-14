<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM videos WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$video = $res->fetch_assoc()) { header('Location: home.php'); exit; }

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user';
if ($video['user_id'] != $user_id && $role !== 'admin') { header('Location: home.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $video_name = $_POST['video_name'] ?? '';
  $description = $_POST['description'] ?? '';
  $stmt = $conn->prepare('UPDATE videos SET video_name = ?, description = ? WHERE id = ?');
  $stmt->bind_param('ssi', $video_name, $description, $id);
  $stmt->execute();
  header('Location: home.php'); exit;
}
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Edit Video</title><link rel="stylesheet" href="css/style.css"></head>
<body>
  <div class="edit-card">
    <h2>Edit Video</h2>
    <form method="post">
      <input name="video_name" value="<?php echo htmlspecialchars($video['video_name']); ?>" required>
      <textarea name="description" required><?php echo htmlspecialchars($video['description']); ?></textarea>
      <button type="submit">Save</button>
      <a href="home.php" class="btn">Cancel</a>
    </form>
  </div>
</body></html>
