<?php
session_start();
if(!isset($_SESSION['member'])){ header("location:../index.php"); exit; }
$conn = new mysqli("localhost","root","","library_db");

$username = $_SESSION['member'];
$member = $conn->query("SELECT * FROM members WHERE enrollment_no='$username'")->fetch_assoc();

// Return book handling
if(isset($_POST['return'])){
    $borrow_id = $_POST['borrow_id'];
    $record = $conn->query("SELECT * FROM borrow_records WHERE id='$borrow_id'")->fetch_assoc();
    if($record){
        // Update borrow record
        $conn->query("UPDATE borrow_records SET status='Returned' WHERE id='$borrow_id'");
        // Update book copies
        $conn->query("UPDATE books SET available_copies=available_copies+1 WHERE id={$record['book_id']}");
        $message = "Book returned successfully!";
    }
}

// Fetch borrowed books including due date
$borrowed = $conn->query("SELECT br.id, b.title, br.borrow_date, br.due_date, br.status 
                          FROM borrow_records br 
                          JOIN books b ON br.book_id=b.id
                          WHERE br.member_id={$member['id']} AND br.status='Borrowed'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Book</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background:#f0f2f5; }
        .sidebar { width:220px; position:fixed; top:0; left:0; height:100%; background:#2c3e50; padding:20px; color:#fff; }
        .sidebar h2 { text-align:center; margin-bottom:30px; }
        .sidebar a { display:block; color:#fff; padding:10px 0; margin:5px 0; text-decoration:none; border-radius:6px; transition: background 0.2s; }
        .sidebar a:hover { background:#34495e; }
        .content { margin-left:240px; padding:20px; }

        .form-box { background:#e67e22; color:#fff; padding:20px; border-radius:10px; margin-bottom:30px; }
        .form-box select { padding:8px; width:250px; border-radius:6px; border:none; margin-right:10px; }
        .form-box input[type=submit] { padding:8px 15px; border:none; border-radius:6px; background:#fff; color:#e67e22; font-weight:bold; cursor:pointer; }
        .form-box input[type=submit]:hover { background:#f1f1f1; }

        .table-section { background:#d35400; color:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
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
    <h2>Return Book</h2>

    <?php if(isset($message)){ echo "<div class='message'>$message</div>"; } ?>

    <div class="form-box">
        <form method="POST">
            <select name="borrow_id" required>
                <option value="">Select Book to Return</option>
                <?php while($row=$borrowed->fetch_assoc()){ ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo $row['title']; ?> (Borrowed on: <?php echo $row['borrow_date']; ?>, Due: <?php echo $row['due_date']; ?>)
                    </option>
                <?php } ?>
            </select>
            <input type="submit" name="return" value="Return">
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


