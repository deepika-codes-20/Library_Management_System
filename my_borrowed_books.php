<?php
session_start();
if(!isset($_SESSION['member'])){
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

// Current Member
$enroll = $_SESSION['member'];
$member = $conn->query("SELECT id, name FROM members WHERE enrollment_no='$enroll'")->fetch_assoc();
$member_id = $member['id'];

// FETCH BORROWED BOOKS
$query = "
  SELECT bb.*, b.title, b.author
  FROM borrowed_books bb
  JOIN books b ON bb.book_id = b.id
  WHERE bb.member_id = $member_id
  ORDER BY bb.borrowed_on DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Borrowed Books – Member Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../style.css">

  <style>
    :root {
      --member-accent: #1e90ff;
    }
    .member-title {
      color: var(--member-accent);
      font-size: 28px;
      font-weight: 700;
      text-align:center;
      margin-bottom: 15px;
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

  <section class="card" style="padding:35px;">

    <h2 class="member-title">📘 My Borrowed Books</h2>

    <p style="text-align:center; color:var(--muted); margin-bottom:20px;">
      Track the books you have borrowed and check due dates.
    </p>

    <table class="table">
      <thead>
        <tr>
          <th>Book</th>
          <th>Author</th>
          <th>Borrowed On</th>
          <th>Due Date</th>
          <th>Status</th>
        </tr>
      </thead>

      <tbody>

      <?php if($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>

        <tr>
          <td><?= htmlspecialchars($row['title']); ?></td>
          <td><?= htmlspecialchars($row['author']); ?></td>
          <td><?= $row['borrowed_on']; ?></td>
          <td><?= $row['due_date']; ?></td>

          <td>
            <?php if($row['status'] == "borrowed"): ?>
              <span class="badge" style="background:#ffecb3; color:#a97400; padding:6px 10px;">Borrowed</span>
            <?php else: ?>
              <span class="badge" style="background:#d4edda; color:#155724; padding:6px 10px;">Returned</span>
            <?php endif; ?>
          </td>
        </tr>

        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" style="text-align:center; padding:15px; color:#888;">
            You haven't borrowed any books yet.
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
