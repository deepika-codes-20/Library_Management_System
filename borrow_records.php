<?php
session_start();
if(!isset($_SESSION['admin'])){ header("location:../index.php"); exit; }
$conn = new mysqli("localhost","root","","library_db");

// Admin manually returning a book
if(isset($_GET['return'])){
    $record_id = $_GET['return'];
    $record_res = $conn->query("SELECT * FROM borrow_records WHERE id=$record_id");
    $record = $record_res->fetch_assoc();
    $book_id = $record['book_id'];

    // Mark as returned and update book availability
    $conn->query("UPDATE borrow_records SET status='Returned', return_date=NOW() WHERE id=$record_id");
    $conn->query("UPDATE books SET available_copies=available_copies+1 WHERE id=$book_id");
    header("location:borrow_records.php");
}

// Fetch all borrow records (both Borrowed and Returned)
$records = $conn->query("
    SELECT br.id, m.full_name, b.title, br.borrow_date, br.due_date, br.return_date, br.status 
    FROM borrow_records br
    JOIN members m ON br.member_id=m.id
    JOIN books b ON br.book_id=b.id
    ORDER BY br.borrow_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrow Records</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background:#f0f2f5; }
        .sidebar { width:220px; position:fixed; top:0; left:0; height:100%; background:#2c3e50; padding:20px; color:#fff; }
        .sidebar h2 { text-align:center; margin-bottom:30px; }
        .sidebar a { display:block; color:#fff; padding:10px 0; margin:5px 0; text-decoration:none; border-radius:6px; transition: background 0.2s; }
        .sidebar a:hover { background:#34495e; }
        .content { margin-left:240px; padding:20px; }

        h2.page-title { margin-bottom:20px; color:#2c3e50; }
        .table-section { background:#16a085; color:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
        table { width:100%; border-collapse:collapse; background:rgba(255,255,255,0.95); color:#333; border-radius:6px; overflow:hidden; }
        th, td { padding:12px; text-align:left; }
        th { background:#f4f4f4; color:#333; }
        tr:nth-child(even) td { background:#f9f9f9; }
        .btn { padding:6px 12px; background:#3498db; color:#fff; text-decoration:none; border-radius:6px; }
        .btn:hover { background:#2980b9; }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>Library Admin</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="manage_books.php">Manage Books</a>
    <a href="manage_members.php">Manage Members</a>
    <a href="borrow_records.php">Borrow Records</a>
    <a href="../logout.php">Logout</a>
</div>

<div class="content">
<h2 class="page-title">Borrow / Return Records</h2>

<div class="table-section">
<table>
<tr>
<th>ID</th>
<th>Member</th>
<th>Book</th>
<th>Borrow Date</th>
<th>Due Date</th>
<th>Return Date</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = $records->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['full_name']; ?></td>
<td><?php echo $row['title']; ?></td>
<td><?php echo $row['borrow_date']; ?></td>
<td><?php echo $row['due_date']; ?></td>
<td><?php echo $row['return_date'] ? $row['return_date']:date('Y-m-d'); ?></td>
<td><?php echo $row['status']; ?></td>
<td>
<?php if($row['status']=="Borrowed"){ ?>
<a class="btn" href="borrow_records.php?return=<?php echo $row['id']; ?>" onclick="return confirm('Mark as returned?')">Return</a>
<?php } else { echo "-"; } ?>
</td>
</tr>
<?php } ?>

</table>
</div>
</div>
</body>
</html>




