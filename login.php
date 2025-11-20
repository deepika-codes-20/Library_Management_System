<?php
session_start();
$conn = new mysqli("localhost", "root", "", "library_db");
if ($conn->connect_error) die("Database Connection Failed: ".$conn->connect_error);

if (isset($_POST['login'])) {

    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Admin login
    $admin_pass = md5($password);
    $admin_res = $conn->query("SELECT * FROM admin WHERE username='$username' AND password='$admin_pass'");
    if ($admin_res && $admin_res->num_rows == 1) {
        $_SESSION['admin'] = $username;
        header("Location: admin/dashboard.php");
        exit();
    }

    // Member login (Enrollment No + DOB)
    $member_res = $conn->query("SELECT * FROM members WHERE enrollment_no='$username' AND dob='$password'");
    if ($member_res && $member_res->num_rows == 1) {
        $_SESSION['member'] = $username;
        header("Location: member/member_dashboard.php");
        exit();
    }

    $error = "Invalid login! Admin: username+password, Member: Enrollment No + DOB";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sunrise Public Library - Login</title>
    <style>
        /* Full screen background */
        body{
            margin:0;
            font-family: Arial, sans-serif;
            height:100vh;
            background: url('library-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Overlay for slightly dark background */
        body::before {
            content: "";
            position: absolute;
            top:0; left:0; right:0; bottom:0;
            background-color: rgba(0,0,0,0.5); /* dark overlay */
            z-index:1;
        }

        /* Title text on top */
        .title {
            position: absolute;
            top: 50px;
            width:100%;
            text-align:center;
            z-index:2;
            color: #fff;
        }
        .title h1 {
            font-size: 48px;
            margin:0;
            letter-spacing: 1px;
        }
        .title p {
            font-size: 20px;
            margin-top:5px;
            font-weight: 300;
        }

        /* Login box */
        .login-box{
            position: relative;
            z-index:2;
            background: rgba(255,255,255,0.95);
            padding: 35px;
            border-radius: 12px;
            width: 360px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .login-box h2{
            text-align:center;
            color: #2c3e50;
            margin-bottom:20px;
        }

        .login-box input{
            width:100%;
            padding:12px;
            margin:8px 0;
            border-radius:6px;
            border:1px solid #ccc;
        }

        .login-box input[type="submit"]{
            background: #3498db;
            color: #fff;
            font-weight:bold;
            cursor:pointer;
            border:none;
        }

        .error{
            background:#e74c3c;
            color:#fff;
            padding:10px;
            border-radius:6px;
            text-align:center;
            margin-bottom:12px;
        }

        .login-box a{
            text-decoration:none;
            color:#3498db;
            display:block;
            text-align:center;
            margin-top:12px;
        }
    </style>
</head>
<body>

<div class="title">
    <h1>College Library</h1>
    <p>Welcome to your digital library portal</p>
</div>

<div class="login-box">
    <h2>Login</h2>
    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Admin Username / Enrollment No" required>
        <input type="text" name="password" placeholder="Admin Password / DOB (YYYY-MM-DD)" required>
        <input type="submit" name="login" value="Login">
    </form>
    <a href="register_member.php">New Member? Register Here</a>
</div>

</body>
</html>

