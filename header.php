<?php
// header.php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Library System</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link rel="stylesheet" href="/style.css" />
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <h1 class="logo">My Library</h1>
      <nav class="main-nav">
        <a href="/index.html">Home</a>
        <?php if(isset($_SESSION['admin'])): ?>
            <a href="/admin/dashboard.php">Admin</a>
            <a href="/logout.php">Logout</a>
        <?php elseif(isset($_SESSION['member'])): ?>
            <a href="/member/member_dashboard.php">My Account</a>
            <a href="/logout.php">Logout</a>
        <?php else: ?>
            <a href="/login.php">Login</a>
            <a href="/register_member.php">Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>
  <main class="container">
