<?php
session_start();
if(!isset($_SESSION['member'])){
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

$enroll = $_SESSION['member'];
$member = $conn->query("SELECT * FROM members WHERE enrollment_no='$enroll'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile – Member Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../style.css">

  <style>
    :root { --member-accent:#1e90ff; }
    .member-title {
        color: var(--member-accent);
        font-size: 30px;
        font-weight: 700;
        text-align:center;
        margin-bottom:15px;
    }
    .member-btn {
        background: var(--member-accent);
        color:white;
        padding:12px 18px;
        border-radius:10px;
        text-decoration:none;
        font-weight:600;
        display:inline-block;
        margin-top:20px;
    }
    .profile-item {
        margin-bottom: 15px;
        padding:10px;
        background:#f7f9fc;
        border-radius:10px;
        font-size:16px;
    }
    .label {
        font-weight:bold;
        color:#555;
    }
  </style>

</head>

<body>

<!-- HEADER -->
<header class="site-header">
  <div class="container header-inner">
    <h1 class="logo">My Library</h1>
    <nav class="main-nav">
      <a href="../index.html">Home</a>
      <a href="member_dashboard.php">Dashboard</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </div>
</header>

<main class="container">

<section class="card" style="padding:35px; max-width:600px; margin:auto;">

    <h2 class="member-title">👤 My Profile</h2>

    <div class="profile-item">
        <span class="label">Full Name:</span><br>
        <?= htmlspecialchars($member['name']) ?>
    </div>

    <div class="profile-item">
        <span class="label">Enrollment No:</span><br>
        <?= htmlspecialchars($member['enrollment_no']) ?>
    </div>

    <div class="profile-item">
        <span class="label">Email:</span><br>
        <?= htmlspecialchars($member['email']) ?>
    </div>

    <div style="text-align:center;">
        <a href="edit_profile.php" class="member-btn">✏️ Edit Profile</a>
    </div>

</section>

</main>

<!-- FOOTER -->
<footer class="site-footer">
  <div class="container">
    <p>
      © <script>document.write(new Date().getFullYear())</script>  
      My Library | Member Portal
    </p>
  </div>
</footer>

</body>
</html>
