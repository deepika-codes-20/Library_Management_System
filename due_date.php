<?php
session_start();
if(!isset($_SESSION['member'])){
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

if(!isset($_GET['id'])){
    header("Location: my_borrowed_books.php");
    exit;
}

$id = intval($_GET['id']);

// Get borrow record
$res = $conn->query("SELECT * FROM borrowed_books WHERE id=$id AND status='borrowed'");
$bb = $res->fetch_assoc();

if(!$bb){
    die("Invalid request!");
}

if($bb['extended'] == 1){
    die("You already extended this book once!");
}

// Extend due date by 7 days
$new_due_date = date("Y-m-d", strtotime($bb['due_date']." +7 days"));

$conn->query("UPDATE borrowed_books SET due_date='$new_due_date', extended=1 WHERE id=$id");

$msg = "Due date extended successfully!";
?>

<!DOCTYPE html>
<html>
<head>
<title>Extend Due Date</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../style.css">

<style>
:root { --member-accent:#1e90ff; }
.member-title { color:var(--member-accent); font-size:28px; font-weight:700; text-align:center; }
.member-btn { background:var(--member-accent); color:#fff; padding:10px 16px; border-radius:10px; text-decoration:none; }
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

<section class="card" style="padding:35px; text-align:center;">
  <h2 class="member-title">📅 Due Date Extended</h2>

  <div style="background:#e8ffe8; color:#127c12; padding:12px; border-radius:10px;">
      <?= $msg ?>
  </div>

  <div style="margin-top:25px;">
    <a href="my_borrowed_books.php" class="member-btn">Back to Borrowed Books</a>
  </div>
</section>

</main>

<footer class="site-footer">
<div class="container">
<p>© <script>document.write(new Date().getFullYear())</script> My Library</p>
</div>
</footer>

</body>
</html>
