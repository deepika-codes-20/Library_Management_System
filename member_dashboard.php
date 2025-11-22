<?php
session_start();
if(!isset($_SESSION['member'])){
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

// Get member data
$enroll = $_SESSION['member'];
$member = $conn->query("SELECT * FROM members WHERE enrollment_no='$enroll'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Member Dashboard – My Library</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../style.css">

  <!-- Blue Theme -->
  <style>
    :root {
      --member-accent: #1e90ff; /* Blue color */
    }
    .member-title {
      color: var(--member-accent);
      font-size: 30px;
      font-weight: 700;
      text-align: center;
      margin-bottom: 15px;
    }
    .member-box {
      background: #f0f7ff;
      border-left: 5px solid var(--member-accent);
      padding: 18px 20px;
      border-radius: 12px;
      transition: 0.2s ease;
    }
    .member-box:hover {
      background: #e2efff;
      transform: translateY(-3px);
    }
    .member-btn {
      background: var(--member-accent);
      color: #fff;
      padding: 10px 16px;
      text-decoration: none;
      border-radius: 10px;
      font-weight: 600;
      display: inline-block;
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
      <a href="../login.php">Logout</a>
    </nav>
  </div>
</header>

<main class="container">

  <!-- CARD -->
  <section class="card" style="padding:35px;">

    <h2 class="member-title">👋 Welcome, <?= htmlspecialchars($member['name']); ?>!</h2>

    <p style="text-align:center; color:var(--muted); margin-bottom:25px;">
      Your personal library dashboard to manage books and view borrowed records.
    </p>

    <!-- GRID -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px;">

      <!-- Box 1 -->
      <div class="member-box">
        <h3 style="margin:0 0 8px;">📚 Search Books</h3>
        <p style="color:var(--muted); margin-bottom:12px;">
          Browse all available books in the library.
        </p>
        <a href="search_books.php" class="member-btn">Search</a>
      </div>

      <!-- Box 2 -->
      <div class="member-box">
        <h3 style="margin:0 0 8px;">📖 My Borrowed Books</h3>
        <p style="color:var(--muted); margin-bottom:12px;">
          View the books you have currently borrowed and their due dates.
        </p>
        <a href="my_borrowed_books.php" class="member-btn">View</a>
      </div>

      <!-- Box 3 -->
      <div class="member-box">
        <h3 style="margin:0 0 8px;">👤 My Profile</h3>
        <p style="color:var(--muted); margin-bottom:12px;">
          Check your registered details.
        </p>
        <a href="profile.php" class="member-btn">Open</a>
      </div>

    </div>

  </section>

</main>

<!-- FOOTER -->
<footer class="site-footer">
  <div class="container">
    <p>© <script>document.write(new Date().getFullYear())</script> My Library | Member Dashboard</p>
  </div>
</footer>

</body>
</html>

