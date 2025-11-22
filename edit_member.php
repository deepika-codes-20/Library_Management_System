<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

if(!isset($_GET['id'])){
    header("Location: manage_members.php");
    exit;
}

$id = intval($_GET['id']);

// Fetch member
$member = $conn->query("SELECT * FROM members WHERE id=$id")->fetch_assoc();
if(!$member){
    die("Member not found!");
}

$msg = "";

// UPDATE MEMBER
if(isset($_POST['update_member'])){
    $name  = $conn->real_escape_string($_POST['name']);
    $enroll = $conn->real_escape_string($_POST['enrollment_no']);
    $email = $conn->real_escape_string($_POST['email']);

    $stmt = $conn->prepare("UPDATE members SET name=?, enrollment_no=?, email=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $enroll, $email, $id);

    if($stmt->execute()){
        $msg = "Member updated successfully!";
    } else {
        $msg = "Error updating member.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Member – Admin Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../style.css">

<!-- Purple Admin Theme -->
<style>
:root { --admin-accent: #6c5ce7; }
.admin-title {
    color: var(--admin-accent);
    font-size: 30px;
    font-weight: 700;
    text-align:center;
    margin-bottom: 10px;
}
.admin-btn {
    background: var(--admin-accent);
    padding: 10px 16px;
    color:white;
    font-weight:600;
    border-radius:10px;
    border:none;
    cursor:pointer;
    width:100%;
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
            <a href="manage_members.php">Members</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>
</header>

<main class="container">

<section class="card" style="padding:35px; max-width:600px; margin:auto;">

    <h2 class="admin-title">✏️ Edit Member</h2>

    <?php if(!empty($msg)): ?>
        <div class="card" 
             style="background:#e8ffe8; color:#127c12; padding:12px; margin-bottom:20px; border-radius:10px;">
            <?= $msg ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <div class="form-row">
            <label>Full Name:</label>
            <input type="text" name="name" required
                   value="<?= htmlspecialchars($member['name']); ?>"
                   style="border-radius:8px;">
        </div>

        <div class="form-row">
            <label>Enrollment Number:</label>
            <input type="text" name="enrollment_no" required
                   value="<?= htmlspecialchars($member['enrollment_no']); ?>"
                   style="border-radius:8px;">
        </div>

        <div class="form-row">
            <label>Email Address:</label>
            <input type="email" name="email" required
                   value="<?= htmlspecialchars($member['email']); ?>"
                   style="border-radius:8px;">
        </div>

        <div class="form-row" style="margin-top:15px;">
            <button type="submit" name="update_member" class="admin-btn">
                Update Member
            </button>
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
