<?php
require_once "db_connect.php";

$msg = "";

// REGISTER MEMBER
if(isset($_POST['register'])){
    $name  = $conn->real_escape_string($_POST['name']);
    $enroll = $conn->real_escape_string($_POST['enrollment_no']);
    $email  = $conn->real_escape_string($_POST['email']);
    $password = md5($_POST['password']);

    // Check if enrollment exists
    $check = $conn->query("SELECT id FROM members WHERE enrollment_no='$enroll'");
    if($check->num_rows > 0){
        $msg = "Enrollment number already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO members (name, enrollment_no, email, password, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $enroll, $email, $password);

        if($stmt->execute()){
            $msg = "Registration successful!";
        } else {
            $msg = "Error occurred during registration.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register Member – My Library</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">

  <!-- MEMBER REGISTRATION BLUE THEME -->
  <style>
    :root {
      --member-accent: #1e90ff; /* Blue Theme */
    }
    .member-title {
      color: var(--member-accent);
      font-size: 30px;
      font-weight: 700;
      text-align:center;
      margin-bottom: 10px;
    }
  </style>
</head>

<body>

<!-- HEADER -->
<header class="site-header">
  <div class="container header-inner">
    <h1 class="logo">My Library</h1>
    <nav class="main-nav">
      <a href="index.html">Home</a>
      <a href="login.php">Login</a>
      <a href="register_member.php" style="color:var(--member-accent); font-weight:bold;">Register</a>
    </nav>
  </div>
</header>

<main class="container">

  <!-- MAIN CARD -->
  <section class="card" style="padding:35px; max-width:600px; margin:auto;">

    <h2 class="member-title">👤 Member Registration</h2>

    <?php if(!empty($msg)): ?>
      <div class="card"
           style="background:#e8f2ff; color:#1558a6; padding:12px; margin-bottom:20px; border-radius:10px;">
        <?= $msg ?>
      </div>
    <?php endif; ?>

    <!-- REGISTRATION FORM -->
    <form method="post">

      <div class="form-row">
        <label>Full Name:</label>
        <input type="text" name="name" required placeholder="Enter your full name" style="border-radius:8px;">
      </div>

      <div class="form-row">
        <label>Enrollment Number:</label>
        <input type="text" name="enrollment_no" required placeholder="Enter your enrollment number" style="border-radius:8px;">
      </div>

      <div class="form-row">
        <label>Email Address:</label>
        <input type="email" name="email" required placeholder="Enter your email" style="border-radius:8px;">
      </div>

      <div class="form-row">
        <label>Password:</label>
        <input type="password" name="password" required placeholder="Create a password" style="border-radius:8px;">
      </div>

      <div class="form-row" style="margin-top:15px;">
        <input type="submit" name="register" value="Register"
               style="width:100%; background:var(--member-accent); color:white; padding:12px; font-size:16px; border-radius:10px;">
      </div>

    </form>

  </section>

</main>

<!-- FOOTER -->
<footer class="site-footer">
  <div class="container">
    <p>© <script>document.write(new Date().getFullYear())</script> My Library. All rights reserved.</p>
  </div>
</footer>

</body>
</html>

