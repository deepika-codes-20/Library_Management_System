<?php
session_start();
require_once 'db_connect.php';

if(isset($_POST['login'])){
    $username = $conn->real_escape_string($_POST['username']);
    $password = md5($_POST['password']);

    // Admin Login
    $stmt = $conn->prepare("SELECT username FROM admin WHERE username=? AND password=?");
    $stmt->bind_param("ss",$username,$password);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows == 1){
        $_SESSION['admin'] = $username;
        header("Location: admin/dashboard.php");
        exit;
    }

    // Member Login
    $stmt = $conn->prepare("SELECT enrollment_no FROM members WHERE enrollment_no=? AND password=?");
    $stmt->bind_param("ss",$username,$password);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows == 1){
        $_SESSION['member'] = $username;
        header("Location: member/member_dashboard.php");
        exit;
    }

    $message = "Invalid username or password!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login – My Library</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>

<body>

<!-- HEADER (same as index.html) -->
<header class="site-header">
  <div class="container header-inner">
    <h1 class="logo">My Library</h1>
    <nav class="main-nav">
      <a href="index.html">Home</a>
      <a href="login.php" class="active">Login</a>
      <a href="register_member.php">Register</a>
    </nav>
  </div>
</header>

<main class="container">

  <!-- LOGIN CARD with same design as index hero card -->
  <section class="card" style="padding:40px; text-align:center; max-width:450px; margin:auto; margin-top:40px;">

    <h2 style="font-size:30px; margin-bottom:10px;">🔑 Login</h2>
    <p style="color:var(--muted); font-size:16px; margin-bottom:20px;">
      Login to access your account.
    </p>

    <?php if(!empty($message)): ?>
      <div class="card" 
           style="background:#fff3f3; color:#b91c1c; margin-bottom:20px; padding:12px; border-radius:10px;">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <form method="post" style="margin-top:10px; text-align:left;">

      <div class="form-row">
        <label>Username / Enrollment No:</label>
        <input type="text" name="username" required placeholder="Enter username..." style="border-radius:8px;">
      </div>

      <div class="form-row">
        <label>Password:</label>
        <input type="password" name="password" required placeholder="Enter password..." style="border-radius:8px;">
      </div>

      <div class="form-row" style="margin-top:15px;">
        <input type="submit" name="login" value="Login"
               style="width:100%; font-size:16px; padding:12px; border-radius:10px;">
      </div>

    </form>

    <p style="margin-top:15px; font-size:14px;">
      New here? <a href="register_member.php" style="color:var(--accent); font-weight:600;">Register Now</a>
    </p>

  </section>

</main>

<!-- FOOTER (same as index.html) -->
<footer class="site-footer">
  <div class="container">
    <p>© <script>document.write(new Date().getFullYear())</script> My Library. All rights reserved.</p>
  </div>
</footer>

</body>
</html>



