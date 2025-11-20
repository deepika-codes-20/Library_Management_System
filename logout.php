<?php
session_start();

// Unset all session variables for security
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page (index.php)
header("Location: login.php");
exit();
?>