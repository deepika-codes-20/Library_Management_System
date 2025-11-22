<?php
session_start();
if(!isset($_SESSION['member'])){
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

// Logged member
$enroll = $_SESSION['member'];
$member = $conn->query("SELECT * FROM members WHERE enrollment_no='$enroll'")->fetch_assoc();
$member_id = $member['id'];

if(!isset($_GET['id'])){
    header("Location: search_books.php");
    exit;
}

$book_id = intval($_GET['id']);

// Fetch book details
$book = $conn->query("SELECT * FROM books WHERE id=$book_id")->fetch_assoc();
if(!$book){
    die("Book not found!");
}

// Borrow Logic
$msg = "";
$success = false;

if(isset($_POST['borrow'])){
    
    if($book['copies'] <= 0){
        $msg = "This book is currently not available.";
    } else {

        // Optional Check: Prevent borrowing same book twice
        $check = $conn->query("
            SELECT id FROM borrowed_books 
            WHERE member_id=$member_id AND book_id=$book_id AND status='borrowed'
        ");

        if($check->num_rows > 0){
            $msg = "You already borrowed this book!";
        } else {

            // Borrow book (insert record)
            $due_date = date("Y-m-d", strtotime("+7 days"));
            
            $stmt = $conn->prepare("
                INSERT INTO borrowed_books (book_id, member_id, borrowed_on, due_date, status)
                VALUES (?, ?, NOW(), ?, 'borrowed')
            ");
            $stmt->bind_param("iis", $book_id, $member_id, $due_date);
            $stmt->execute();

            // Reduce book copies count
            $conn->query("UPDATE books SET copies = copies - 1 WHERE id=$book_id");

            $success = true;
            $msg = "Book borrowed successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Borrow Book – Member Portal</title>
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
      <a href="member_dashboard.php">Dashboard</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </div>
</header>


<main class="container">

  <section class="card" style="padding:35px; max-width:600px; margin:auto;">

    <h2 class="member-title">📘 Borrow Book</h2>

    <!-- STATUS MESSAGE -->
    <?php if(!empty($msg)): ?>
      <div class="card" style="
            background: <?= $success ? '#e8ffe8' : '#ffeaea' ?>;
            color: <?= $success ? '#127c12' : '#c62828' ?>;
            padding:12px; margin-bottom:20px; border-radius:10px;">
        <?= $msg ?>
      </div>
    <?php endif; ?>

    <!-- BOOK DETAILS -->
    <div style="margin-bottom:25px;">
      <h3 style="margin-bottom:8px;"><?= htmlspecialchars($book['title']) ?></h3>
      <p style="color:var(--muted); margin-bottom:5px;">
        <strong>Author:</strong> <?= htmlspecialchars($book['author']) ?>
      </p>
      <p style="color:var(--muted); margin-bottom:5px;">
        <strong>ISBN:</strong> <?= htmlspecialchars($book['isbn']) ?>
      </p>
      <p style="color:var(--muted);">
        <strong>Available Copies:</strong> <?= $book['copies'] ?>
      </p>
    </div>

    <?php if(!$success): ?>
    <!-- BORROW FORM -->
    <form method="post">
      <button type="submit" name="borrow"
              style="width:100%; padding:12px; background:var(--member-accent); 
                     color:white; font-size:16px; border-radius:10px;">
        Borrow This Book
      </button>
    </form>
    <?php else: ?>
      <div style="text-align:center; margin-top:15px;">
        <a href="my_borrowed_books.php" class="member-btn">View My Borrowed Books</a>
      </div>
    <?php endif; ?>

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
