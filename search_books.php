<?php
session_start();
if(!isset($_SESSION['member'])){
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

$books = [];
$queryText = "";

// SEARCH
if(isset($_GET['search'])){
    $queryText = $conn->real_escape_string($_GET['search']);
    $books = $conn->query("
        SELECT * FROM books
        WHERE title LIKE '%$queryText%' 
        OR author LIKE '%$queryText%' 
        OR isbn LIKE '%$queryText%'
    ");
} else {
    $books = $conn->query("SELECT * FROM books ORDER BY title ASC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Books – Member Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../style.css">

  <!-- Blue Theme -->
  <style>
    :root {
      --member-accent: #1e90ff;
    }
    .member-title {
      color: var(--member-accent);
      font-size: 28px;
      font-weight: 700;
      text-align:center;
      margin-bottom: 10px;
    }
    .search-input {
      width: 100%;
      padding: 12px;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    .member-btn {
      background: var(--member-accent);
      color: #fff;
      padding: 8px 14px;
      text-decoration: none;
      border-radius: 10px;
      font-weight: 600;
      font-size: 14px;
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
      <a href="member_dashboard.php">Dashboard</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </div>
</header>

<main class="container">

  <!-- MAIN CARD -->
  <section class="card" style="padding:35px;">

    <h2 class="member-title">🔍 Search Books</h2>

    <!-- SEARCH FORM -->
    <form method="get" style="margin-bottom:25px;">
      <input type="text" name="search" class="search-input" 
             placeholder="Search by title, author, or ISBN..."
             value="<?= htmlspecialchars($queryText) ?>">
      <div style="text-align:right; margin-top:10px;">
        <button class="member-btn" type="submit">Search</button>
      </div>
    </form>

    <!-- BOOKS TABLE -->
    <table class="table">
      <thead>
        <tr>
          <th>Book</th>
          <th>Author</th>
          <th>ISBN</th>
          <th>Copies</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
      <?php if($books->num_rows > 0): ?>
        <?php while($b = $books->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($b['title']) ?></td>
          <td><?= htmlspecialchars($b['author']) ?></td>
          <td><?= htmlspecialchars($b['isbn']) ?></td>
          <td><?= $b['copies'] ?></td>

          <td>
            <?php if($b['copies'] > 0): ?>
              <a href="borrow_book.php?id=<?= $b['id'] ?>" class="member-btn">Borrow</a>
            <?php else: ?>
              <span style="color:#888; font-weight:600;">Not Available</span>
            <?php endif; ?>
          </td>

        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" style="text-align:center; padding:15px; color:#888;">
            No books found.
          </td>
        </tr>
      <?php endif; ?>
      </tbody>

    </table>

  </section>

</main>

<!-- FOOTER -->
<footer class="site-footer">
  <div class="container">
    <p>© <script>document.write(new Date().getFullYear())</script> My Library | Member Portal</p>
  </div>
</footer>

</body>
</html>
