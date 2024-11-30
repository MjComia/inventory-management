<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "inventory-management");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Log incoming data for debugging
error_log(print_r($_POST, true));

// Check if ID is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteId'])) {
    $id = $_POST['deleteId'];

    // Prepare delete SQL query
    $sql = "DELETE FROM `middleman-sender` WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>alert('Row deleted successfully!'); window.location.href='middlemandash.php';</script>";
        } else {
            echo "<script>alert('No rows are deleted'); window.location.href='middlemandash.php';</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('Invalid Request!'); window.location.href='middlemandash.php';</script>";
}

mysqli_close($conn);
