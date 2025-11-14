<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') { header('Location: index.php'); exit; }
$id = intval($_GET['id'] ?? 0);
if ($id) {
  $stmt = $conn->prepare('UPDATE users SET role = ? WHERE id = ?');
  $role = 'admin';
  $stmt->bind_param('si', $role, $id);
  $stmt->execute();
}
header('Location: admin.php'); exit;
?>