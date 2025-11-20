<?php
session_start();
if(!isset($_SESSION['member'])){ header("location:../index.php"); exit; }
$conn = new mysqli("localhost","root","","library_db");

// Fetch all books
$books = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Books</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background:#f0f2f5; }
        .sidebar { width:220px; position:fixed; top:0; left:0; height:100%; background:#2c3e50; padding:20px; color:#fff; }
        .sidebar h2 { text-align:center; margin-bottom:30px; }
        .sidebar a { display:block; color:#fff; padding:10px 0; margin:5px 0; text-decoration:none; border-radius:6px; transition: background 0.2s; }
        .sidebar a:hover { background:#34495e; }
        .content { margin-left:240px; padding:20px; }

        .table-section { background:#3498db; color:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
        table { width:100%; border-collapse:collapse; background:rgba(255,255,255,0.95); color:#333; border-radius:6px; overflow:hidden; }
        th, td { padding:12px; text-align:left; }
        th { background:#f4f4f4; color:#333; }
        tr:nth-child(even) td { background:#f9f9f9; }
        h2,h3 { margin-bottom:15px; }
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
    <h2>All Books</h2>

    <div class="table-section">
        <h3>Library Books</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>ISBN</th>
                <th>Total Copies</th>
                <th>Available Copies</th>
            </tr>
            <?php while($row = $books->fetch_assoc()){ ?>
            <tr>
                <td><?php echo $row['id'];?></td>
                <td><?php echo $row['title'];?></td>
                <td><?php echo $row['author'];?></td>
                <td><?php echo $row['publisher'];?></td>
                <td><?php echo $row['isbn'];?></td>
                <td><?php echo $row['total_copies'];?></td>
                <td><?php echo $row['available_copies'];?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

</div>

</body>
</html>

