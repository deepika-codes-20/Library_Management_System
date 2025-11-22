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

$borrow_id = intval($_GET['id']);

// Fetch borrow record
$result = $conn->query("
    SELECT * FROM borrowed_books WHERE id=$borrow_id AND status='borrowed'
");

$borrow = $result->fetch_assoc();
if(!$borrow){
    die("Invalid or already returned!");
}

$book_id = $borrow['book_id'];

// UPDATE returned status
$conn->query("UPDATE borrowed_books SET status='returned', returned_on=NOW() WHERE id=$borrow_id");

// Increase available copies
$conn->query("UPDATE books SET copies = copies + 1 WHERE id=$book_id");

$msg = "Book returned successfully!";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Return Book – Member</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../style.css">

<style>
:root { --member-accent: #1e90ff; }
.member-title { color: var(--member-accent); font-size: 30px; font-weight: 700; text-align:center; }
.member-btn { background: var(--member-accent); color:#fff; padding:10px 16px; border-radius:10px; font-weight:600; text-decoration:none; }
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
  <h2 class="member-title">✔ Book Returned</h2>

  <div style="background:#e8ffe8; color:#127c12; padding:15px; border-radius:10px;">
      <?= $msg ?>
  </div>

  <div style="margin-top:25px;">
    <a href="my_borrowed_books.php" class="member-btn">Go to My Borrowed Books</a>
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

