<?php
session_start();

if(!isset($_SESSION['username'])  || $_SESSION['role'] !== 'middleman'){
    header("Location: index.php");
    exit();
}

echo"Welcome, " . $_SESSION['username'] . "(Middleman)";
?>

<a href= "logout.php">Logout</a>