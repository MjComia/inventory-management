<?php
session_start();

if(!isset($_SESSION['username'])  || $_SESSION['role'] !== 'admin'){
    header("Location: index.php");
    exit(); 
}
$conn = mysqli_connect("localhost", "root", "", "inventory-management");
$sql = "SELECT * FROM `middleman-sender`";
$result = mysqli_query($conn, $sql);
echo"Welcome, " . $_SESSION['username'] . "(Admin)";
?>


<a href= "logout.php">Logout</a>

<!--------------------------------H T M L------------------------------------------------------------------------------------------------------------------>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="assets/cs/main.css">
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
    <h1>Admin Dashboard</h1>
    <table>
        <thead>
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
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
                            <button onclick='openEditModal(" . json_encode($row) . ")'>Edit</button>
                            <button onclick='deleteRow(" . $row["id"] . ")'>Delete</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
        
      <!-- Edit Modal -->
      <div id="editModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); border:1px solid #ccc; background:#fff; padding:20px; z-index:1000;">
        <form id="editForm">
            <input type="hidden" name="id" id="editID">
            <label> Category: </label>
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

   <!-- Add New Row -->
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
                  location.reload();
              }).catch(err => alert('Error: ' + err));
        }

        function deleteRow(id) {
            if (confirm("Are you sure you want to delete this record?")) {
                fetch('delete.php?id=' + id, {
                    method: 'GET'
                }).then(response => response.text())
                  .then(data => {
                      alert('Record deleted successfully!');
                      location.reload();
                  }).catch(err => alert('Error: ' + err));
            }
        }

        function submitAdd() {
            const formData = new FormData(document.getElementById('addForm'));
            fetch('add.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text())
              .then(data => {
                  alert('Record added successfully!');
                  location.reload();
              }).catch(err => alert('Error: ' + err));
        }
    </script>
    <a href="logout.php">Logout</a>
</body>
</html>

