<?php
session_start();
if(!isset($_SESSION['member'])){
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

$enroll = $_SESSION['member'];
$member = $conn->query("SELECT id FROM members WHERE enrollment_no='$enroll'")->fetch_assoc();
$member_id = $member['id'];

$today = date("Y-m-d");

$query = "
  SELECT bb.*, b.title 
  FROM borrowed_books bb
  JOIN books b ON bb.book_id=b.id
  WHERE bb.member_id=$member_id 
  AND bb.status='borrowed'
  AND bb.due_date < '$today'
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Notifications – Overdue Books</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../style.css">
<style>
:root { --member-accent:#1e90ff; }
.member-title { color:var(--member-accent); font-size:30px; font-weight:700; text-align:center; }
.overdue-box {
    background:#ffeaea;
    border-left:5px solid #d63031;
    padding:15px;
    border-radius:10px;
    margin-bottom:15px;
}
</style>
</head>

<body>

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
<section class="card" style="padding:35px;">

  <h2 class="member-title">🔔 Overdue Notifications</h2>

  <?php if($result->num_rows == 0): ?>
    <p style="text-align:center; color:var(--muted);">You have no overdue books!</p>
  <?php else: ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="overdue-box">
        <h3><?= htmlspecialchars($row['title']); ?></h3>
        <p><strong>Due Date:</strong> <?= $row['due_date']; ?></p>
        <p style="color:#b21f2d;">⚠ This book is overdue. Please return it as soon as possible.</p>

        <a href="return_book.php?id=<?= $row['id'] ?>" 
           style="background:#d63031; color:white; padding:8px 14px; border-radius:8px;">
           Return Now
        </a>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>

</section>
</main>

<footer class="site-footer">
<div class="container">
<p>© <script>document.write(new Date().getFullYear())</script> My Library</p>
</div>
</footer>

</body>
</html>
