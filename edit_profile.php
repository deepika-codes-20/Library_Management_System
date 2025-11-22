<?php
session_start();
if(!isset($_SESSION['member'])){
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

$enroll = $_SESSION['member'];
$member = $conn->query("SELECT * FROM members WHERE enrollment_no='$enroll'")->fetch_assoc();
$member_id = $member['id'];

$msg = "";

// UPDATE PROFILE
if(isset($_POST['update_profile'])){

    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);

    // If password field filled → update password
    if(!empty($_POST['password'])){
        $password = md5($_POST['password']);
        $conn->query("UPDATE members SET name='$name', email='$email', password='$password' WHERE id=$member_id");
    } else {
        $conn->query("UPDATE members SET name='$name', email='$email' WHERE id=$member_id");
    }

    $msg = "Profile updated successfully!";

    // Refresh data
    $member = $conn->query("SELECT * FROM members WHERE enrollment_no='$enroll'")->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile – Member Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../style.css">

  <style>
    :root { --member-accent:#1e90ff; }
    .member-title {
        color: var(--member-accent);
        font-size: 30px;
        font-weight: 700;
        text-align:center;
        margin-bottom:15px;
    }
    .member-btn {
        background: var(--member-accent);
        color:#fff;
        padding:10px 16px;
        border-radius:10px;
        font-weight:600;
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

    <h2 class="member-title">✏️ Edit Profile</h2>

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
            <label>Email:</label>
            <input type="email" name="email" required
                   value="<?= htmlspecialchars($member['email']); ?>"
                   style="border-radius:8px;">
        </div>

        <div class="form-row">
            <label>New Password (optional):</label>
            <input type="password" name="password"
                   placeholder="Enter new password (optional)"
                   style="border-radius:8px;">
        </div>

        <div class="form-row" style="margin-top:15px;">
            <button type="submit" name="update_profile" class="member-btn">
                Update Profile
            </button>
        </div>

    </form>

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
