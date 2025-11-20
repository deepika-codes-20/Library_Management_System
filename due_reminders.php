<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("location:../index.php");
    exit;
}

$conn = new mysqli("localhost","root","","library_db");
if($conn->connect_error){
    die("Database Connection Failed: ".$conn->connect_error);
}

// Today's date
$today = date('Y-m-d');

// Fetch books due today (or overdue if you want)
$sql = "SELECT br.id, m.full_name, m.email, b.title, br.borrow_date, br.due_date 
        FROM borrow_records br
        JOIN members m ON br.member_id = m.id
        JOIN books b ON br.book_id = b.id
        WHERE br.status='Borrowed' AND br.due_date = '$today'";

$result = $conn->query($sql);

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){

        $to = $row['email'];
        $subject = "Library Book Due Today Reminder";

        $message = "
        Dear ".$row['full_name'].",\n\n
        This is a friendly reminder that the book '".$row['title']."' you borrowed from Sunrise Public Library
        is due today (".$row['due_date'].").\n
        Please return it today to avoid any penalties.\n\n
        Thank you,\n
        Sunrise Public Library
        ";

        $headers = "From: library@sunriselibrary.com";

        // Send the email
        if(mail($to, $subject, $message, $headers)){
            echo "Reminder sent to ".$row['full_name']." (".$to.")<br>";
        } else {
            echo "Failed to send reminder to ".$row['full_name']." (".$to.")<br>";
        }
    }
} else {
    echo "No books are due today.";
}

$conn->close();
?>
