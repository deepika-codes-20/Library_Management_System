<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

if(!isset($_GET['id'])){
    header("Location: manage_books.php");
    exit;
}

$id = intval($_GET['id']);
$book = $conn->query("SELECT * FROM books WHERE id=$id")->fetch_assoc();

if(!$book){
    die("Book not found!");
}

$msg = "";

// UPDATE BOOK
if(isset($_POST['update_book'])){
    $title  = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $isbn   = $conn->real_escape_string($_POST['isbn']);
    $copies = intval($_POST['copies']);

    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, isbn=?, copies=? WHERE id=?");
    $stmt->bind_param("sssii", $title, $author, $isbn, $copies, $id);

    if($stmt->execute()){
        $msg = "Book updated successfully!";
    } else {
        $msg = "Error updating book.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Book – Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../style.css">

  <style>
    :root {
      --admin-accent: #6c5ce7;
    }
    .admin-title {
      color: var(--admin-accent);
      font-size: 30px;
      font-weight: 700;
      text-align: center;
      margin-bottom: 10px;
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
      <a href="../logout.php">Logout</a>
    </nav>
  </div>
</header>

<main class="container">

  <!-- MAIN CARD -->
  <section class="card" style="padding:35px; max-width:600px; margin:auto;">

    <h2 class="admin-title">✏️ Edit Book</h2>

    <?php if(!empty($msg)): ?>
      <div class="card" 
           style="background:#e8ffe8; color:#127c12; padding:12px; margin-bottom:20px; border-radius:10px;">
        <?= $msg ?>
      </div>
    <?php endif; ?>

    <!-- EDIT FORM -->
    <form method="post">

      <div class="form-row">
        <label>Book Title:</label>
        <input type="text" name="title" required 
               value="<?= htmlspecialchars($book['title']) ?>"
               style="border-radius:8px;">
      </div>

      <div class="form-row">
        <label>Author Name:</label>
        <input type="text" name="author" required
               value="<?= htmlspecialchars($book['author']) ?>"
               style="border-radius:8px;">
      </div>

      <div class="form-row">
        <label>ISBN Number:</label>
        <input type="text" name="isbn"
               value="<?= htmlspecialchars($book['isbn']) ?>"
               style="border-radius:8px;">
      </div>

      <div class="form-row">
        <label>No. of Copies:</label>
        <input type="number" name="copies" required min="1"
               value="<?= $book['copies'] ?>"
               style="border-radius:8px;">
      </div>

      <div class="form-row" style="margin-top:15px;">
        <input type="submit" name="update_book" value="Update Book"
               style="width:100%; background:var(--admin-accent); color:white; padding:12px; font-size:16px; border-radius:10px;">
      </div>

    </form>

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
