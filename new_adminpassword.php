<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("location:../login.php");
    exit;
}

$conn = new mysqli("localhost","root","","library_db");
$message = '';

// Handle form submission
if(isset($_POST['update'])){
    $current_pass = $_POST['current_password'];
    $new_user = $_POST['new_username'];
    $new_pass = $_POST['new_password'];
    
    // Fetch current admin credentials
    $admin = $conn->query("SELECT * FROM admin WHERE username='{$_SESSION['admin']}'")->fetch_assoc();
    
    if($admin && md5($current_pass) == $admin['password']){ // using MD5
        // Update username and password
        $conn->query("UPDATE admin SET username='$new_user', password=MD5('$new_pass') WHERE id={$admin['id']}");
        $_SESSION['admin'] = $new_user; // update session
        $message = "✅ Username and password updated successfully!";
    } else {
        $message = "❌ Current password is incorrect!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Admin Credentials</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { background:#f0f2f5; font-family:Arial, sans-serif; }
        .content { margin-left:240px; padding:20px; }
        .form-box { background:#3498db; color:#fff; padding:25px; border-radius:10px; max-width:400px; }
        input[type=text], input[type=password] { width:100%; padding:10px; margin:10px 0; border-radius:6px; border:none; }
        input[type=submit] { padding:10px 15px; border:none; border-radius:6px; background:#fff; color:#3498db; font-weight:bold; cursor:pointer; }
        input[type=submit]:hover { background:#f1f1f1; }
        .message { margin:15px 0; padding:10px; border-radius:6px; background:#27ae60; color:#fff; }
        .message.error { background:#e74c3c; }
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
<h2>Change Admin Credentials</h2>

<?php if($message != ''){ 
    $cls = strpos($message,'❌')!==false ? 'error':'';
    echo "<div class='message $cls'>$message</div>"; 
} ?>

<div class="form-box">
    <form method="POST">
        <input type="password" name="current_password" placeholder="Current Password" required>
        <input type="text" name="new_username" placeholder="New Username" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="submit" name="update" value="Update Credentials">
    </form>
</div>

</div>
</body>
</html>
