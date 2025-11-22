<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard – My Library</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../style.css">

  <!-- Admin Page Color Theme -->
  <style>
    :root {
      --admin-accent: #6c5ce7; /* purple */
    }
    .admin-title {
      color: var(--admin-accent);
      font-size: 30px;
      font-weight: 700;
      margin-bottom: 10px;
      text-align:center;
    }
    .admin-badge {
      background: var(--admin-accent);
      color: #fff;
      padding: 10px 16px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
    }
  </style>
</head>

<body>

<!-- HEADER (Same as Index Page) -->
<header class="site-header">
  <div class="container header-inner">
    <h1 class="logo">Admin Panel</h1>
    <nav class="main-nav">
      <a href="../index.html">Home</a>
      <a href="../login.php">Logout</a>
    </nav>
  </div>
</header>

<main class="container">

  <!-- HERO SECTION (same design as index page card) -->
  <section class="card" style="padding:40px; text-align:center;">
    <h2 class="admin-title">📘 Admin </h2>
    <p style="color:var(--muted); font-size:16px; max-width:600px; margin:auto;">
      Welcome, <strong><?php echo $_SESSION['admin']; ?></strong> <br>
      Manage books, members, and borrowed records from one place.
    </p>

    <div style="margin-top:25px; display:flex; justify-content:center; gap:20px;">
      <a href="manage_books.php" class="admin-badge">Manage Books</a>
      <a href="manage_members.php" class="admin-badge">Members</a>
      <a href="borrowed_records.php" class="admin-badge">Borrowed Books</a>
    </div>
  </section>

  

</main>

<!-- FOOTER (same as index page) -->
<footer class="site-footer">
  <div class="container">
    <p>© <script>document.write(new Date().getFullYear())</script> My Library Admin Panel</p>
  </div>
</footer>

</body>
</html>
