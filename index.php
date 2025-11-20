<?php
session_start();
$conn = new mysqli("localhost","root","","library_db");

if(isset($_POST['login'])){
    $username = $conn->real_escape_string($_POST['username']);
    $password = md5($_POST['password']);

    // Admin Login Check
    $admin = $conn->query("SELECT * FROM admin WHERE username='$username' AND password='$password'");
    if($admin->num_rows == 1){
        $_SESSION['admin'] = $username;
        header("location:admin/dashboard.php");
        exit;
    }

    // Member Login Check
    $member = $conn->query("SELECT * FROM members WHERE enrollment_no='$username' AND password='$password'");
    if($member->num_rows == 1){
        $_SESSION['member'] = $username;
        header("location:member/member_dashboard.php");
        exit;
    }

    // If both fail:
    $message = "Invalid Username / Enrollment Number or Password!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Login</title>
    <style>
        body {
            background:#f0f2f5;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            font-family:Arial;
        }
        .login-box {
            background:#3498db;
            color:#fff;
            width:380px;
            padding:40px;
            border-radius:15px;
            box-shadow:0 5px 15px rgba(0,0,0,.2);
            text-align:center;
        }
        .login-box input {
            width:100%;
            padding:12px;
            margin:10px 0;
            border:none;
            border-radius:8px;
        }
        .login-box input[type=submit] {
            background:#fff;
            color:#3498db;
            font-weight:bold;
            cursor:pointer;
        }
        .message {
            background:#e74c3c;
            padding:10px;
            border-radius:8px;
            margin-bottom:10px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>

    <?php if(isset($message)) echo "<div class='message'>$message</div>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username / Enrollment Number" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
    </form>

    <p>New Member? <a href="register_member.php" style="color:white;text-decoration:underline;">Register Here</a></p>
</div>

</body>
</html>


