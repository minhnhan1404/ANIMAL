<?php
require "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<h1>ADMIN DASHBOARD</h1>
<a href="../auth/logout.php">Đăng xuất</a>
