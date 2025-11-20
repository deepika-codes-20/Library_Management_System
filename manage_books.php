<?php
session_start();
if(!isset($_SESSION['admin'])){ header("location:../index.php"); exit; }
$conn = new mysqli("localhost","root","","library_db");

$message = '';

// Add Book
if(isset($_POST['add_book'])){
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $isbn = $_POST['isbn'];
    $total = $_POST['total_copies'];
    $conn->query("INSERT INTO books (title, author, publisher, isbn, total_copies, available_copies) 
                  VALUES ('$title','$author','$publisher','$isbn','$total','$total')");
    $message = "✅ Book '$title' added successfully!";
}

// Edit Book
if(isset($_POST['edit_book'])){
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $isbn = $_POST['isbn'];
    $total = $_POST['total_copies'];

    $book = $conn->query("SELECT * FROM books WHERE id=$id")->fetch_assoc();
    $available = $book['available_copies'] + ($total - $book['total_copies']);
    if($available < 0) $available = 0;

    $conn->query("UPDATE books SET title='$title', author='$author', publisher='$publisher', isbn='$isbn', total_copies='$total', available_copies='$available' WHERE id=$id");
    $message = "✅ Book '$title' updated successfully!";
}

// Delete Book
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM books WHERE id=$id");
    $message = "✅ Book deleted successfully!";
}

// Mark as returned (for borrowed books)
if(isset($_GET['return'])){
    $record_id = $_GET['return'];
    $record_res = $conn->query("SELECT * FROM borrow_records WHERE id=$record_id");
    $record = $record_res->fetch_assoc();
    $book_id = $record['book_id'];

    $conn->query("UPDATE borrow_records SET status='Returned', return_date=CURDATE() WHERE id=$record_id");
    $conn->query("UPDATE books SET available_copies=available_copies+1 WHERE id=$book_id");
    $message = "✅ Book marked as returned!";
}

// Fetch all books
$books = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Books</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background:#f0f2f5; }
        .sidebar { width:220px; position:fixed; top:0; left:0; height:100%; background:#2c3e50; padding:20px; color:#fff; }
        .sidebar h2 { text-align:center; margin-bottom:30px; }
        .sidebar a { display:block; color:#fff; padding:10px 0; margin:5px 0; text-decoration:none; border-radius:6px; transition: background 0.2s; }
        .sidebar a:hover { background:#34495e; }
        .content { margin-left:240px; padding:20px; }

        h2.page-title { margin-bottom:20px; color:#2c3e50; }
        .message { margin-bottom:15px; padding:10px; border-radius:6px; background:#27ae60; color:#fff; }
        .form-box { background:#1abc9c; color:#fff; padding:20px; border-radius:10px; margin-bottom:30px; }
        .form-box input, .form-box select { padding:8px; margin-right:10px; border-radius:6px; border:none; }
        .form-box input[type=submit] { background:#fff; color:#1abc9c; font-weight:bold; cursor:pointer; }
        .form-box input[type=submit]:hover { background:#f1f1f1; }

        .table-section { background:#16a085; color:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
        table { width:100%; border-collapse:collapse; background:rgba(255,255,255,0.95); color:#333; border-radius:6px; overflow:hidden; }
        th, td { padding:12px; text-align:left; }
        th { background:#f4f4f4; color:#333; }
        tr:nth-child(even) td { background:#f9f9f9; }
        .btn { padding:6px 12px; background:#3498db; color:#fff; text-decoration:none; border-radius:6px; }
        .btn:hover { background:#2980b9; }
        .btn-delete { background:red; }
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
<h2 class="page-title">Manage Books</h2>

<?php if($message != ''){ ?>
    <div class="message"><?php echo $message; ?></div>
<?php } ?>

<!-- Add Book Form -->
<div class="form-box">
<h3>Add New Book</h3>
<form method="post">
    <input type="text" name="title" placeholder="Title" required>
    <input type="text" name="author" placeholder="Author" required>
    <input type="text" name="publisher" placeholder="Publisher" required>
    <input type="text" name="isbn" placeholder="ISBN" required>
    <input type="number" name="total_copies" placeholder="Total Copies" required>
    <input type="submit" name="add_book" value="Add Book">
</form>
</div>

<!-- Books Table -->
<div class="table-section">
<table>
<tr>
<th>ID</th><th>Title</th><th>Author</th><th>Publisher</th><th>ISBN</th><th>Total</th><th>Available</th><th>Borrowed By</th><th>Actions</th>
</tr>

<?php 
while($row=$books->fetch_assoc()){ 
    $borrowed_res = $conn->query("SELECT br.id as record_id, m.full_name FROM borrow_records br 
                                  JOIN members m ON br.member_id=m.id
                                  WHERE br.book_id={$row['id']} AND br.status='Borrowed'");
?>
<tr>
<td><?php echo $row['id'];?></td>
<td><?php echo $row['title'];?></td>
<td><?php echo $row['author'];?></td>
<td><?php echo $row['publisher'];?></td>
<td><?php echo $row['isbn'];?></td>
<td><?php echo $row['total_copies'];?></td>
<td><?php echo $row['available_copies'];?></td>
<td>
<?php 
if($borrowed_res->num_rows>0){
    while($b=$borrowed_res->fetch_assoc()){
        echo $b['full_name']." <a class='btn' href='manage_books.php?return=".$b['record_id']."' onclick='return confirm(\"Mark as returned?\")'>[Return]</a><br>";
    }
} else { echo "-"; }
?>
</td>
<td>
<a class="btn" href="manage_books.php?edit=<?php echo $row['id'];?>" onclick="return editBook(<?php echo $row['id'];?>,'<?php echo addslashes($row['title']);?>','<?php echo addslashes($row['author']);?>','<?php echo addslashes($row['publisher']);?>','<?php echo $row['isbn'];?>','<?php echo $row['total_copies'];?>')">Edit</a>
<a class="btn btn-delete" href="manage_books.php?delete=<?php echo $row['id'];?>" onclick="return confirm('Delete this book?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>

<!-- Hidden Edit Form -->
<div class="form-box" id="editForm" style="display:none;">
<h3>Edit Book</h3>
<form method="post">
    <input type="hidden" name="id" id="edit_id">
    <input type="text" name="title" id="edit_title" placeholder="Title" required>
    <input type="text" name="author" id="edit_author" placeholder="Author" required>
    <input type="text" name="publisher" id="edit_publisher" placeholder="Publisher" required>
    <input type="text" name="isbn" id="edit_isbn" placeholder="ISBN" required>
    <input type="number" name="total_copies" id="edit_total" placeholder="Total Copies" required>
    <input type="submit" name="edit_book" value="Update Book">
</form>
</div>

<script>
function editBook(id,title,author,publisher,isbn,total){
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_author').value = author;
    document.getElementById('edit_publisher').value = publisher;
    document.getElementById('edit_isbn').value = isbn;
    document.getElementById('edit_total').value = total;
    window.scrollTo(0,document.body.scrollHeight);
    return false;
}
</script>

</div>
</body>
</html>




