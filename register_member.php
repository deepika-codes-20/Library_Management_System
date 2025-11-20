<?php
$conn = new mysqli("localhost","root","","library_db");
if($conn->connect_error) die("DB Error: ".$conn->connect_error);

if(isset($_POST['register'])){

    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $enrollment_no = $conn->real_escape_string($_POST['enrollment_no']);
    $dob = $conn->real_escape_string($_POST['dob']);  // YYYY-MM-DD

    // Check if already registered
    $check = $conn->query("SELECT id FROM members WHERE enrollment_no='$enrollment_no'");
    if($check->num_rows > 0){
        $msg = "Member already exists!";
    } else {
        $sql = "INSERT INTO members (full_name,email,enrollment_no,dob) 
                VALUES ('$full_name','$email','$enrollment_no','$dob')";
        if($conn->query($sql)){
            $msg = "Registration successful! You can now login.";
        } else {
            $msg = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register Member</title>
    <style>
        body{font-family:Arial;background:#f4f6f9;display:flex;justify-content:center;align-items:center;height:100vh;}
        .box{background:#3498db;color:#fff;padding:30px;border-radius:12px;width:380px;box-shadow:0 0 15px rgba(0,0,0,0.2);}
        input{width:100%;padding:10px;margin:7px 0;border-radius:6px;border:none;}
        input[type=submit]{background:#fff;color:#3498db;font-weight:bold;cursor:pointer;}
        .msg{background:#2ecc71;padding:10px;border-radius:6px;margin-bottom:10px;}
        .err{background:#e74c3c;padding:10px;border-radius:6px;margin-bottom:10px;}
    </style>
</head>
<body>

<div class="box">
    <h2>Register Member</h2>

    <?php if(isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

    <form method="post">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="enrollment_no" placeholder="Enrollment Number" required>
        <input type="date" name="dob" required>
        <input type="submit" name="register" value="Register">
    </form>

    <p style="margin-top:10px;">
        <a href="login.php" style="color:#fff;text-decoration:underline;">Already have account? Login</a>
    </p>
</div>

</body>
</html>

