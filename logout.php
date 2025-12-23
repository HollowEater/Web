<?php
session_start();

// Xóa tất cả session người dùng
unset($_SESSION['user_logged_in']);
unset($_SESSION['user_id']);
unset($_SESSION['user_email']);
unset($_SESSION['user_name']);

// Chuyển về trang chủ
header("Location: index.php");
exit();
?>