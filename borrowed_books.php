
<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

// MARK AS RETURNED
if(isset($_GET['return'])) {
    $id = intval($_GET['return']);

    // get book id
    $record = $conn->query("SELECT book_id FROM borrowed_books WHERE id=$id")->fetch_assoc();
    if($record){
        $book_id = $record['book_id'];

        // mark returned
        $conn->query("UPDATE borrowed_books SET status='returned', returned_on=NOW() WHERE id=$id");

        // update book copies
        $conn->query("UPDATE books SET copies = copies + 1 WHERE id=$book_id");

        $msg = "Book marked as returned successfully!";
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

<!-- ADMIN PURPLE THEME -->
<style>
:root { --admin-accent: #6c5ce7; }
.admin-title {
    font-size: 30px;
    font-weight: bold;
    color: var(--admin-accent);
    text-align:center;
    margin-bottom: 10px;
}
.admin-btn {
    background: var(--admin-accent);
    padding: 8px 14px;
    text-decoration: none;
    color: white;
    font-weight: 600;
    border-radius: 10px;
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
        <a href="manage_books.php">Books</a>
        <a href="manage_members.php">Members</a>
        <a href="../logout.php">Logout</a>
      </nav>
  </div>
</header>

<main class="container">

<section class="card" style="padding:35px;">

    <h2 class="admin-title">📘 Borrowed Books</h2>

    <?php if(!empty($msg)): ?>
        <div class="card" style="background:#e8ffe8; color:#127c12; padding:10px;">
            <?= $msg ?>
        </div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Book</th>
                <th>Member</th>
                <th>Borrowed On</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Returned On</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php
        $query = "
            SELECT bb.*, b.title, m.name AS member_name, m.enrollment_no
            FROM borrowed_books bb
            JOIN books b ON bb.book_id = b.id
            JOIN members m ON bb.member_id = m.id
            ORDER BY bb.borrowed_on DESC
        ";

        $res = $conn->query($query);

        while($row = $res->fetch_assoc()):
        ?>

        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td>
                <?= htmlspecialchars($row['member_name']) ?><br>
                <small><?= $row['enrollment_no'] ?></small>
            </td>
            <td><?= $row['borrowed_on'] ?></td>
            <td><?= $row['due_date'] ?></td>

            <td>
                <?php if($row['status'] == 'borrowed'): ?>
                    <span class="badge" style="background:#ffeaa7; color:#6d4c41;">Borrowed</span>
                <?php else: ?>
                    <span class="badge" style="background:#d4edda; color:#155724;">Returned</span>
                <?php endif; ?>
            </td>

            <td><?= $row['returned_on'] ? $row['returned_on'] : "-" ?></td>

            <td>
                <?php if($row['status'] == 'borrowed'): ?>
                    <a href="borrowed_books.php?return=<?= $row['id'] ?>"
                       class="admin-btn"
                       style="background:#00b894;"
                       onclick="return confirm('Mark this book as returned?');">
                       ✔ Return
                    </a>
                <?php else: ?>
                    <span style="color:var(--admin-accent); font-weight:bold;">Completed</span>
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
