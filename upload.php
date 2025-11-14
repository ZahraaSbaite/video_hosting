<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $video_id = $_POST['video_id'] ?? '';
  $video_name = $_POST['video_name'] ?? '';
  $description = $_POST['description'] ?? '';

  if (!isset($_FILES['video_file'])) { header('Location: home.php'); exit; }

  $dir = 'uploads/';
  if (!is_dir($dir)) mkdir($dir, 0755, true);

  $filename = time().'_'.basename($_FILES['video_file']['name']);
  $target = $dir.$filename;

  if (move_uploaded_file($_FILES['video_file']['tmp_name'], $target)) {
    $stmt = $conn->prepare('INSERT INTO videos (user_id, video_id, video_name, description, file_path) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('issss', $user_id, $video_id, $video_name, $description, $target);
    $stmt->execute();
  }
}
header('Location: home.php');
exit;
?>