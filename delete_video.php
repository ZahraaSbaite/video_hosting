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

// delete file
if (file_exists($video['file_path'])) unlink($video['file_path']);

$stmt = $conn->prepare('DELETE FROM videos WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

header('Location: home.php'); exit;
?>