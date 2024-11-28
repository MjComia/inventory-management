<?php

session_start();
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "inventory-management");

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form data is sent via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the values sent from the modal form
    $id = $_POST['id'];
    $category = $_POST['category'];
    $brand_name = $_POST['brand_name'];
    $product_model = $_POST['product_model'];
    $quantity = $_POST['quantity'];
    $branch = $_POST['branch'];

    // Write the SQL query to update the record
    $sql = "UPDATE `middleman-sender` 
            SET category = '$category', 
                brand_name = '$brand_name', 
                product_model = '$product_model', 
                quantity = '$quantity', 
                branch = '$branch'
            WHERE id = $id";

    // Execute the query and check if it was successful
    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully.";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Close the connection
mysqli_close($conn);
?>
