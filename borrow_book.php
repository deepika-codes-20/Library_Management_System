<?php
session_start();
if(!isset($_SESSION['member'])){ header("location:../index.php"); exit; }
$conn = new mysqli("localhost","root","","library_db");

$username = $_SESSION['member'];
$member = $conn->query("SELECT * FROM members WHERE enrollment_no='$username'")->fetch_assoc();

// Borrow book handling
if(isset($_POST['borrow'])){
    $book_id = $_POST['book_id'];

    // Check availability
    $book = $conn->query("SELECT * FROM books WHERE id='$book_id'")->fetch_assoc();
    if($book['available_copies']>0){
        $borrow_date = date('Y-m-d');
        $due_date = date('Y-m-d', strtotime('+7 days')); // Auto due date 7 days later

        // Insert borrow record with due_date
        $conn->query("INSERT INTO borrow_records(member_id, book_id, borrow_date, due_date, status) 
                      VALUES({$member['id']}, $book_id, '$borrow_date', '$due_date', 'Borrowed')");

        // Update available copies
        $conn->query("UPDATE books SET available_copies=available_copies-1 WHERE id='$book_id'");
        $message = "Book borrowed successfully! Due date: $due_date";
    } else {
        $message = "Book is not available.";
    }
}

// Fetch available books
$books = $conn->query("SELECT * FROM books WHERE available_copies>0");

// Fetch borrowed books with due date
$borrowed = $conn->query("SELECT br.id, b.title, br.borrow_date, br.due_date, br.status 
                          FROM borrow_records br 
                          JOIN books b ON br.book_id=b.id
                          WHERE br.member_id={$member['id']} AND br.status='Borrowed'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrow Book</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background:#f0f2f5; }
        .sidebar { width:220px; position:fixed; top:0; left:0; height:100%; background:#2c3e50; padding:20px; color:#fff; }
        .sidebar h2 { text-align:center; margin-bottom:30px; }
        .sidebar a { display:block; color:#fff; padding:10px 0; margin:5px 0; text-decoration:none; border-radius:6px; transition: background 0.2s; }
        .sidebar a:hover { background:#34495e; }
        .content { margin-left:240px; padding:20px; }

        .form-box { background:#1abc9c; color:#fff; padding:20px; border-radius:10px; margin-bottom:30px; }
        .form-box select { padding:8px; width:200px; border-radius:6px; border:none; margin-right:10px; }
        .form-box input[type=submit] { padding:8px 15px; border:none; border-radius:6px; background:#fff; color:#1abc9c; font-weight:bold; cursor:pointer; }
        .form-box input[type=submit]:hover { background:#f1f1f1; }

        .table-section { background:#16a085; color:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
        table { width:100%; border-collapse:collapse; background:rgba(255,255,255,0.95); color:#333; border-radius:6px; overflow:hidden; }
        th, td { padding:12px; text-align:left; }
        th { background:#f4f4f4; color:#333; }
        tr:nth-child(even) td { background:#f9f9f9; }
        h2,h3 { margin-bottom:15px; }
        .message { margin-bottom:15px; padding:10px; border-radius:6px; background:#27ae60; color:#fff; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Member Panel</h2>
    <a href="member_dashboard.php">Dashboard</a>
    <a href="view_books.php">View Books</a>
    <a href="borrow_book.php">Borrow Book</a>
    <a href="return_book.php">Return Book</a>
    <a href="../logout.php">Logout</a>
</div>

<div class="content">
    <h2>Borrow Book</h2>

    <?php if(isset($message)){ echo "<div class='message'>$message</div>"; } ?>

    <div class="form-box">
        <form method="POST">
            <select name="book_id" required>
                <option value="">Select a Book</option>
                <?php while($row=$books->fetch_assoc()){ ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                <?php } ?>
            </select>
            <input type="submit" name="borrow" value="Borrow">
        </form>
    </div>

    <div class="table-section">
        <h3>Your Borrowed Books</h3>
        <table>
            <tr><th>ID</th><th>Book</th><th>Borrow Date</th><th>Due Date</th><th>Status</th></tr>
            <?php 
            $borrowed->data_seek(0);
            while($row=$borrowed->fetch_assoc()){ ?>
            <tr>
                <td><?php echo $row['id'];?></td>
                <td><?php echo $row['title'];?></td>
                <td><?php echo $row['borrow_date'];?></td>
                <td><?php echo $row['due_date'];?></td>
                <td><?php echo $row['status'];?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

</div>

</body>
</html>


   
