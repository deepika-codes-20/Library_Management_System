<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

// MARK BOOK RETURNED
if(isset($_GET['return'])){
    $id = intval($_GET['return']);

    // Get book id first
    $res = $conn->query("SELECT book_id FROM borrowed_books WHERE id=$id");
    if($row = $res->fetch_assoc()){
        $book_id = $row['book_id'];

        // Update status
        $conn->query("UPDATE borrowed_books SET status='returned', returned_on=NOW() WHERE id=$id");

        // Increase copies count
        $conn->query("UPDATE books SET copies = copies + 1 WHERE id=$book_id");

        $msg = "Book marked as returned!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Borrowed Books – Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../style.css">

  <!-- Admin Theme Color -->
  <style>
    :root {
      --admin-accent: #6c5ce7;
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
      padding: 8px 14px;
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
      <a href="../logout.php">Logout</a>
    </nav>
  </div>
</header>

<main class="container">

  <!-- MAIN CARD (Same Pattern as Dashboard) -->
  <section class="card" style="padding:35px;">

    <h2 class="admin-title">📖 Borrowed Books</h2>

    <?php if(!empty($msg)): ?>
      <div class="card" 
           style="background:#e8ffe8; color:#127c12; padding:12px; margin-bottom:15px; border-radius:8px;">
        <?= $msg ?>
      </div>
    <?php endif; ?>

    <!-- Borrowed Books Table -->
    <table class="table">
      <thead>
        <tr>
          <th>Book</th>
          <th>Member</th>
          <th>Borrowed On</th>
          <th>Due Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>

      <?php
        $query = "
          SELECT bb.*, b.title, m.name, m.enrollment_no 
          FROM borrowed_books bb
          JOIN books b ON bb.book_id = b.id
          JOIN members m ON bb.member_id = m.id
          ORDER BY bb.borrowed_on DESC
        ";

        $result = $conn->query($query);

        while($row = $result->fetch_assoc()):
      ?>

        <tr>
          <td><?= htmlspecialchars($row['title']); ?></td>
          <td><?= htmlspecialchars($row['name']); ?><br>
              <small><?= $row['enrollment_no']; ?></small></td>
          <td><?= $row['borrowed_on']; ?></td>
          <td><?= $row['due_date']; ?></td>

          <td>
            <?php if($row['status'] == 'borrowed'): ?>
              <span class="badge" style="background:#ffeaa7; color:#6d4c41;">Borrowed</span>
            <?php else: ?>
              <span class="badge" style="background:#d4edda; color:#155724;">Returned</span>
            <?php endif; ?>
          </td>

          <td>
            <?php if($row['status'] == 'borrowed'): ?>
              <a class="admin-badge"
                 href="borrowed_books.php?return=<?= $row['id']; ?>"
                 style="padding:6px 10px; font-size:14px;">
                 Mark Returned
              </a>
            <?php else: ?>
              <span style="color:var(--admin-accent); font-weight:600;">✔ Completed</span>
            <?php endif; ?>
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
