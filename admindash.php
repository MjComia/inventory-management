<?php
session_start();

if(!isset($_SESSION['username'])  || $_SESSION['role'] !== 'admin'){
    header("Location: index.php");
    exit();
}

echo"Welcome, " . $_SESSION['username'] . "(Admin)";
?>

<a href= "logout.php">Logout</a>