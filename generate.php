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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Search Customers</title>
</head>
<body>
    <div class="container mt-5">
    <?php include("menus.php"); ?> 
	<div class="row">
			<div class="col-lg-12">
				<div class="card card-default rounded-0 shadow">
                    <div class="card-header">
                    	<div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            	<h3 class="card-title">Customer Search</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row"><div class="col-sm-12 table-responsive">
                        <form method="get" action="" class="mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <label for="search" class="col-form-label">Search:</label>
                                </div>
                                <div class="col-auto">
                                    <input type="text" id="search" name="search" class="form-control" placeholder="Enter name">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">Find</button>
                                </div>
                            </div>
                        </form>
                        </div></div>
                    </div>
                    
                    <?php 
        if(isset($_GET['search'])){
            $search = $_GET['search'];
            $search  = $conn->real_escape_string($search);
            $sql = "SELECT * FROM ims_customer WHERE name LIKE '%$search%'";
            $result = $conn->query($sql);

            if($result-> num_rows > 0){
                echo "<table class='table table-bordered'>";
                echo "<thead class='table-light'>
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Total Shipped</th>
                            <th>Product Name</th>
                            <th>Action</th>
                        </tr>
                      </thead><tbody>";

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
                    $cell .= "<td><form method='post' action='generate_pdf.php'>
                    <input type='hidden' name='customer_id' value='" . $row['id'] . "'>
                    <button type='submit' formtarget='_blank' class='btn btn-secondary btn-sm'>Generate PDF</button>
                    </form></td></tr>";

                    echo $cell;
                }
                echo "</tbody></table>";

            } else {
                echo "<div class='alert alert-warning' role='alert'>No results found.</div>";
            }
        }
        ?>

                </div>
			</div>
		</div>

       
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
