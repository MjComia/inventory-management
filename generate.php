<?php 
include('inc/header.php');
include 'Inventory.php';
$conn = mysqli_connect("localhost", "root", "", "ims_db");
?>
<?php include('inc/container.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method = "get" action = "">
        <label for = "search">Search: </label>
        <input type = "text" id = "search" name = "search" placeholder="Enter keyword">
        <button type="submit">Find</button>
    </form>

<?php 
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $search  = $conn->real_escape_string($search);
    $sql = "SELECT * FROM ims_customer WHERE name LIKE '%$search%'";
    $result = $conn->query($sql);

    if($result-> num_rows > 0){
        
        echo "<table style='border: 1px solid black; padding: 2rem;'>";
        echo "<tr><th style='padding: 1rem;'>ID</th>
                <th style='padding: 1rem;'>Column Name</th>
                <th style='padding: 1rem;'>Total Shipped</th>
                <th style='padding: 1rem;'>Product Name</th>
                <th style='padding: 1rem;'>Action</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            $cell = "<tr><td>" . $row['id'] . "</td>
                <td>" . $row['name'] . "</td>";
            $idCustomer = $row['id'];
            $sql2 ="SELECT * FROM ims_order WHERE customer_id LIKE'%$idCustomer%'";
            $result2 = $conn->query($sql2);
            if($result2-> num_rows > 0){
                while ($row2 = $result2->fetch_assoc()) {
                    $cell .= "<td>" . $row2['total_shipped'] . "</td>";
                    $purchaseID  = $row2['product_id'];
              
                }
            }
            $sql3 = "SELECT pname FROM ims_product WHERE pid = '$purchaseID'";
            $result3 = $conn->query($sql3);
            if($result3-> num_rows > 0){
                while ($row3 = $result3->fetch_assoc()) {
                    $cell .= "<td>" . $row3['pname'] . "</td>";
                }
            }
            $cell .= "<td><form method = 'post' action = 'generate_pdf.php'>
            <input type = 'hidden' name = 'customer_id' value = '" . $row['id'] ."'>
            <button type = 'submit' formtarget = '_blank'>Generate PDF</button>
            </form></td></tr>";
                
            echo $cell;
        }
        echo "</table>";

    } else {
        echo "No results found.";
    }
}
?>
</body>
</html>