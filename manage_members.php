<?php
session_start();
if(!isset($_SESSION['admin'])){ header("location:../index.php"); exit; }
$conn = new mysqli("localhost","root","","library_db");

$message = '';

// Add Member
if(isset($_POST['add_member'])){
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $enroll = $_POST['enrollment_no'];
    $dob = $_POST['dob'];
    $conn->query("INSERT INTO members (full_name,email,enrollment_no,dob,reg_date) 
                  VALUES ('$name','$email','$enroll','$dob',CURDATE())");
    $message = "✅ Member '$name' added successfully!";
}

// Edit Member
if(isset($_POST['edit_member'])){
    $id = $_POST['id'];
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $enroll = $_POST['enrollment_no'];
    $dob = $_POST['dob'];
    $conn->query("UPDATE members SET full_name='$name', email='$email', enrollment_no='$enroll', dob='$dob' WHERE id=$id");
    $message = "✅ Member '$name' updated successfully!";
}

// Delete Member
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM members WHERE id=$id");
    $message = "✅ Member deleted successfully!";
}

// Fetch all members
$members = $conn->query("SELECT * FROM members");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Members</title>
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
        .form-box input { padding:8px; margin-right:10px; border-radius:6px; border:none; }
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
<h2 class="page-title">Manage Members</h2>

<?php if($message != ''){ ?>
    <div class="message"><?php echo $message; ?></div>
<?php } ?>

<!-- Add Member Form -->
<div class="form-box">
<h3>Add New Member</h3>
<form method="post">
    <input type="text" name="full_name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="enrollment_no" placeholder="Enrollment No" required>
    <input type="date" name="dob" placeholder="DOB" required>
    <input type="submit" name="add_member" value="Add Member">
</form>
</div>

<!-- Members Table -->
<div class="table-section">
<table>
<tr>
<th>ID</th><th>Name</th><th>Email</th><th>Enrollment No</th><th>DOB</th><th>Registered</th><th>Actions</th>
</tr>
<?php while($row=$members->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['id'];?></td>
<td><?php echo $row['full_name'];?></td>
<td><?php echo $row['email'];?></td>
<td><?php echo $row['enrollment_no'];?></td>
<td><?php echo $row['dob'];?></td>
<td><?php echo $row['reg_date'];?></td>
<td>
<a class="btn" href="manage_members.php?edit=<?php echo $row['id'];?>" onclick="return editMember(<?php echo $row['id'];?>,'<?php echo addslashes($row['full_name']);?>','<?php echo addslashes($row['email']);?>','<?php echo addslashes($row['enrollment_no']);?>','<?php echo $row['dob'];?>')">Edit</a>
<a class="btn btn-delete" href="manage_members.php?delete=<?php echo $row['id'];?>" onclick="return confirm('Delete this member?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>

<!-- Hidden Edit Form -->
<div class="form-box" id="editForm" style="display:none;">
<h3>Edit Member</h3>
<form method="post">
    <input type="hidden" name="id" id="edit_id">
    <input type="text" name="full_name" id="edit_name" placeholder="Full Name" required>
    <input type="email" name="email" id="edit_email" placeholder="Email" required>
    <input type="text" name="enrollment_no" id="edit_enroll" placeholder="Enrollment No" required>
    <input type="date" name="dob" id="edit_dob" placeholder="DOB" required>
    <input type="submit" name="edit_member" value="Update Member">
</form>
</div>

<script>
function editMember(id,name,email,enroll,dob){
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_enroll').value = enroll;
    document.getElementById('edit_dob').value = dob;
    window.scrollTo(0,document.body.scrollHeight);
    return false;
}
</script>

</div>
</body>
</html>


