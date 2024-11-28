<?php
session_start();

if(!isset($_SESSION['username'])  || $_SESSION['role'] !== 'sender'){
    header("Location: index.php");
    exit();
}

echo"Welcome, " . $_SESSION['username'] . "(Sender)";
?>

<a href= "logout.php">Logout</a>

<!--------------------------------H T M L------------------------------------------------------------------------------------------------------------------>
