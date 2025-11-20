<?php
session_start();
if(!isset($_SESSION['member'])){ header("location:../index.php"); exit; }
$conn = new mysqli("localhost","root","","library_db");

// Get member info
$username = $_SESSION['member']; // assuming enrollment_no is stored in session
$member = $conn->query("SELECT * FROM members WHERE enrollment_no='$username'")->fetch_assoc();

// Fetch data
$books = $conn->query("SELECT * FROM books");
$borrowed = $conn->query("SELECT br.id, b.title, br.borrow_date, br.status 
                          FROM borrow_records br 
                          JOIN books b ON br.book_id=b.id
                          WHERE br.member_id={$member['id']}");

// Counts
$total_books = $books->num_rows;
$borrowed_books = $borrowed->num_rows;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background:#f0f2f5; }
        .sidebar {
            width: 220px;
            position: fixed;
            top:0;
            left:0;
            height:100%;
            background:#2c3e50;
            padding:20px;
            color:#fff;
        }
        .sidebar h2 { text-align:center; margin-bottom:30px; }
        .sidebar a {
            display:block;
            color:#fff;
            padding:10px 0;
            margin:5px 0;
            text-decoration:none;
            border-radius:6px;
            transition: background 0.2s;
        }
        .sidebar a:hover { background:#34495e; }

        .content { margin-left:240px; padding:20px; }

        /* Cards */
        .cards { display:flex; gap:20px; flex-wrap:wrap; margin-top:20px; }
        .card {
            flex:1; min-width:180px;
            padding:30px; border-radius:12px;
            text-align:center; cursor:pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            color:#fff;
        }
        .card:hover { transform:translateY(-5px); box-shadow:0 6px 15px rgba(0,0,0,0.2); }
        .card h2 { font-size:3rem; margin-bottom:10px; }
        .card p { font-size:1.2rem; }

        /* Card colors */
        #booksCard { background:#1abc9c; }
        #borrowedCard { background:#e67e22; }

        /* Table sections */
        .table-section { display:none; margin-top:30px; padding:20px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
        #books { background:#16a085; color:#fff; }
        #borrowed { background:#d35400; color:#fff; }

        table {
            width:100%; border-collapse:collapse; background:rgba(255,255,255,0.95); color:#333; border-radius:6px; overflow:hidden;
        }
        th, td { padding:12px; text-align:left; }
        th { background:#f4f4f4; color:#333; }
        tr:nth-child(even) td { background:#f9f9f9; }
        h3 { margin-bottom:15px; }
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
    <h2 class="page-title">Welcome, <?php echo $member['full_name']; ?>!</h2>

    <!-- Cards -->
    <div class="cards">
        <div id="booksCard" class="card" onclick="showSection('books')">
            <h2><?php echo $total_books;?></h2>
            <p>Total Books Available</p>
        </div>
        <div id="borrowedCard" class="card" onclick="showSection('borrowed')">
            <h2><?php echo $borrowed_books;?></h2>
            <p>Books Borrowed</p>
        </div>
    </div>

    <!-- Books Table -->
    <div id="books" class="table-section">
        <h3>Books Available</h3>
        <table>
            <tr><th>ID</th><th>Title</th><th>Author</th><th>Publisher</th><th>ISBN</th><th>Total</th><th>Available</th></tr>
            <?php 
            $books->data_seek(0);
            while($row=$books->fetch_assoc()){ ?>
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

    <!-- Borrowed Books Table -->
    <div id="borrowed" class="table-section">
        <h3>Your Borrowed Books</h3>
        <table>
            <tr><th>ID</th><th>Book</th><th>Borrow Date</th><th>Status</th></tr>
            <?php 
            $borrowed->data_seek(0);
            while($row=$borrowed->fetch_assoc()){ ?>
            <tr>
                <td><?php echo $row['id'];?></td>
                <td><?php echo $row['title'];?></td>
                <td><?php echo $row['borrow_date'];?></td>
                <td><?php echo $row['status'];?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

</div>

<script>
function showSection(sectionId){
    const sections = ['books','borrowed'];
    sections.forEach(id=>{
        document.getElementById(id).style.display = (id===sectionId) ? 'block':'none';
    });
}
</script>

</body>
</html>

