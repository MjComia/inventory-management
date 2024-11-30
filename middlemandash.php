<?php
session_start();

if(!isset($_SESSION['username'])  || $_SESSION['role'] !== 'middleman'){
    header("Location: index.php");
    exit(); 
}
$conn = mysqli_connect("localhost", "root", "", "inventory-management");
$sql = "SELECT * FROM `middleman-sender`";
$result = mysqli_query($conn, $sql);
echo"Welcome, " . $_SESSION['username'] . "(Middleman)";


?>

<!--------------------------------H T M L------------------------------------------------------------------------------------------------------------------>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Middleman</title>

    <!--Stylesheets-->
    <link rel="stylesheet" type="text/css" href="assets/cs/main.css"/>
    
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>

</form>
<h1>Table Data</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Brand name</th>
                <th>Product Model</th>
                <th>Quantity</th>
                <th>Branch</th>
                <th>Document</th>
                <th>Status</th>
                <th>Isle-Shelf</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Step 3: Display data in the HTML table
            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["category"] . "</td>";
                    echo "<td>" . $row["brand_name"] . "</td>";
                    echo "<td>" . $row["product_model"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>" . $row["branch"] . "</td>";
                    echo "<td>" . $row["document"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>" . $row["isle-shelf"] . "</td>";
                    echo "<td>
                    <button onclick='openEditModal(". json_encode($row) .")'>Edit</button>
                    <form method='POST' action='delete_row.php' style='display:inline;'>
                        <input type='hidden' name='deleteId' value='". $row["id"] ."'>
                        <button type='submit' name='delete' onclick='return confirm(\"Are you sure you want to delete this row?\");'>Delete</button>
                    </form>
                  </td>
                  ";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div id="editModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); border:1px solid #ccc; background:#fff; padding:20px; z-index:1000;">
    <form id="editForm">
        <input type="hidden" name="id" id="editId">
        <label>Category:</label>
        <input type="text" name="category" id="editCategory"><br>
        <label>Brand Name:</label>
        <input type="text" name="brand_name" id="editBrandName"><br>
        <label>Product Model:</label>
        <input type="text" name="product_model" id="editProductModel"><br>
        <label>Quantity:</label>
        <input type="number" name="quantity" id="editQuantity"><br>
        <label>Branch:</label>
        <input type="text" name="branch" id="editBranch"><br>
        <button type="button" onclick="submitEdit()">Save</button>
        <button type="button" onclick="closeEditModal()">Cancel</button>
    </form>
</div>
<div id="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;" onclick="closeEditModal()"></div>

<div> 
    <div>Add a row</div>
    <form action = "<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method= "post" >
        <label>ID</label>
        <input type = "number" name = "id"><br>
        <label>Category:</label>
        <input type="text" name="category" ><br>
        <label>Brand Name:</label>
        <input type="text" name="brand_name" ><br>
        <label>Product Model:</label>
        <input type="text" name="product_model" ><br>
        <label>Quantity:</label>
        <input type="number" name="quantity" ><br>
        <label>Branch:</label>
        <input type="text" name="branch"><br>
        <label>Document:</label>
        <input type="text" name="document"><br>
        <label>Status:</label>
        <input type="text" name="status"><br>
        <label>Isle-Shelf:</label>
        <input type="text" name="isle_shelf"><br>
        <input type = "submit" name = "submit" value = "add">
    </form>

<form method = "get" action  ="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>">
<input type = "text" name = "search" placeholder = "Search..." >
<button type = "submit">Search</button> 



</div>
<script>
function openEditModal(row) {
    document.getElementById('editId').value = row.id;
    document.getElementById('editCategory').value = row.category;
    document.getElementById('editBrandName').value = row.brand_name;
    document.getElementById('editProductModel').value = row.product_model;
    document.getElementById('editQuantity').value = row.quantity;
    document.getElementById('editBranch').value = row.branch;

    document.getElementById('editModal').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

function submitEdit() {
    const formData = new FormData(document.getElementById('editForm'));
    fetch('update.php', {
        method: 'POST',
        body: formData
    }).then(response => response.text())
      .then(data => {
          alert('Record updated successfully!');
          location.reload(); // Reload the page to show updated data
      }).catch(err => alert('Error: ' + err));
}

</script>
</body>
</html>
<a href= "logout.php">Logout</a>


<?php 
$conn = mysqli_connect("localhost", "root", "", "inventory-management");

$sql = "SELECT * FROM `middleman-sender`";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $conn->real_escape_string($_GET['search']); // Sanitize input
    $sql .= " WHERE category LIKE '%$searchTerm%'
                OR brand_name LIKE '%$searchTerm'
                OR product_model LIKE '%$searchTerm'
                OR branch LIKE '%$searchTerm'
                OR id LIKE '%$searchTerm'
                OR status LIKE '%$searchTerm'";
}

// Execute the query
$result = $conn->query($sql);

// Check if results exist
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Brand Name</th>
                <th>Product Model</th>
                <th>Quantity</th>
                <th>Branch</th>
                <th>Document</th>
                <th>Status</th>
                <th>Isle-Shelf</th>
            </tr>";
    // Fetch and display rows
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['category']}</td>
                <td>{$row['brand_name']}</td>
                <td>{$row['product_model']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['branch']}</td>
                <td>{$row['document']}</td>
                <td>{$row['status']}</td>
                <td>{$row["isle-shelf"]}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No results found.";
}

// Close the connection
$conn->close();


if($_SERVER)

?>


<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, "category", FILTER_SANITIZE_SPECIAL_CHARS);
    $brand_name = filter_input(INPUT_POST, "brand_name", FILTER_SANITIZE_SPECIAL_CHARS);
    $product_model = filter_input(INPUT_POST, "product_model", FILTER_SANITIZE_SPECIAL_CHARS);
    $quantity = filter_input(INPUT_POST, "quantity", FILTER_SANITIZE_SPECIAL_CHARS);
    $branch = filter_input(INPUT_POST, "branch", FILTER_SANITIZE_SPECIAL_CHARS);
    $document = filter_input(INPUT_POST, "document", FILTER_SANITIZE_SPECIAL_CHARS);
    $status = filter_input(INPUT_POST, "status", FILTER_SANITIZE_SPECIAL_CHARS);
    $isle_shelf = filter_input(INPUT_POST, "isle_shelf", FILTER_SANITIZE_SPECIAL_CHARS);

    if(empty($id) || empty($category) || empty($brand_name) || 
    empty($product_model) || empty($quantity) || 
    empty($branch) || empty($document) || 
    empty($status) || empty($isle_shelf)){
        echo"FILL UP ALL THE TEXT FIELD";
    }else {
        $sql = "INSERT INTO `middleman-sender`(id, category, brand_name, product_model,
                           quantity, branch, document, status, `isle-shelf`)
                            VALUES ('$id', '$category', '$brand_name', '$product_model', 
                        '$quantity', '$branch', '$document', '$status', '$isle_shelf')";
        try{
            mysqli_query($conn, $sql);
            echo"Now added!";

              }catch(mysqli_sql_exception){
                   echo"That ID is already taken";
               }               
    }
}
/*
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete']))
    $deleteId = filter_input(INPUT_POST, 'deleteId', FILTER_SANITIZE_NUMBER_INT);

    if ($deleteId) {
        // Prepare the DELETE query
        $deleteQuery = "DELETE FROM `middleman-sender` WHERE id = ?";
        $stmt = mysqli_prepare($conn, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "i", $deleteId);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Row with ID $deleteId deleted successfully!'); location.reload();</script>";
        } else {
            echo "<script>alert('Error deleting row: ". mysqli_error($conn) ."');</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Invalid ID provided for deletion.');</script>";
    }
*/
?>