<?php 
ob_start();
session_start();
include('inc/header.php');
include 'Inventory.php';
$conn = mysqli_connect("localhost", "root", "", "ims_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql  = "SELECT product_id, total_shipped FROM ims_order";
$result = $conn->query($sql);
$productID = [];
$productTotal = [];
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $productID[] = $row['product_id'];
        $productTotal[] = $row['total_shipped'];
    }
}
$conn->close();
?>

<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/brand.js"></script>
<script src="js/common.js"></script>
<?php include('inc/container.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Product Chart</title>
</head>
<body>
    <div class="container mt-5">
    <?php include("menus.php"); ?> 
    
        <div class="text-center mb-4">
            <h2 class="display-6">Bar Chart for Total Quantity Shipped by Product</h2>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    <canvas id="myChart" style="max-width: 600px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
    const productNames = <?php echo json_encode($productID); ?>;
    const productQuantity = <?php echo json_encode($productTotal); ?>;
    const ctx = document.getElementById('myChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: productNames,
            datasets: [{
                label: 'Quantity Shipped',
                data: productQuantity,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
