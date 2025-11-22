<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

// DELETE BOOK
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM books WHERE id=$id");
    $msg = "Book deleted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Books – Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../style.css">

  <!-- Admin Theme Color -->
  <style>
    :root {
      --admin-accent: #6c5ce7; /* purple for admin pages */
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

<!-- HEADER -->
<header class="site-header">
  <div class="container header-inner">
    <h1 class="logo">Admin Panel</h1>
    <nav class="main-nav">
      <a href="dashboard.php">Dashboard</a>
      <a href="../login.php">Logout</a>
    </nav>
  </div>
</header>

<main class="container">

  <!-- MAIN CARD SAME PATTERN -->
  <section class="card" style="padding:35px;">

    <h2 class="admin-title">📚 Manage Books</h2>

    <?php if(!empty($msg)): ?>
      <div class="card" 
           style="background:#e8ffe8; color:#127c12; padding:12px; margin-bottom:15px; border-radius:8px;">
        <?= $msg ?>
      </div>
    <?php endif; ?>

    <div style="text-align:center; margin-bottom:25px;">
      <a href="add_book.php" class="admin-badge">+ Add New Book</a>
    </div>

    <!-- BOOKS TABLE -->
    <table class="table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Author</th>
          <th>ISBN</th>
          <th>Copies</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
        <?php
          $books = $conn->query("SELECT * FROM books ORDER BY id DESC");
          while($b = $books->fetch_assoc()):
        ?>
        <tr>
          <td><?= htmlspecialchars($b['title']) ?></td>
          <td><?= htmlspecialchars($b['author']) ?></td>
          <td><?= htmlspecialchars($b['isbn']) ?></td>
          <td><?= $b['copies'] ?></td>
          <td>
            <a class="admin-badge"
               href="edit_book.php?id=<?= $b['id'] ?>"
               style="padding:6px 10px; font-size:14px;">Edit</a>

            <a class="admin-badge"
               href="manage_books.php?delete=<?= $b['id'] ?>"
               onclick="return confirm('Delete this book?');"
               style="background:#d63031; padding:6px 10px; font-size:14px;">
               Delete
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>

    </table>

  </section>

</main>

<!-- FOOTER -->
<footer class="site-footer">
  <div class="container">
    <p>© <script>document.write(new Date().getFullYear())</script> My Library Admin Panel</p>
  </div>
</footer>

</body>
</html>

