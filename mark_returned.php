<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: /login.php"); exit; }
require_once '../db_connect.php';
$id = intval($_GET['id'] ?? 0);
if($id){
    // mark returned
    $stmt = $conn->prepare("UPDATE borrowed_books SET status='returned', returned_on=NOW() WHERE id=?");
    $stmt->bind_param("i",$id); $stmt->execute();
    // increment book copies
    $q = $conn->query("SELECT book_id FROM borrowed_books WHERE id=$id");
    if($r = $q->fetch_assoc()){
        $conn->query("UPDATE books SET copies = copies + 1 WHERE id=".$r['book_id']);
    }
}
header("Location: manage_books.php");
exit;
