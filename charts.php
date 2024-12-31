<?php 
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
        echo $row['product_id'] . " " . $row['total_shipped'] . "<br>";
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Document</title>
</head>
<body>
    <h2>Bar Chart for the total Quantity Shipped for each Product</h2>
    <div style = "width: 600px; margin: auto;">
        <canvas id="myChart"></canvas>
    </div>

    <script>
    const productNames = <?php echo json_encode($productID); ?>;
    const productQuantity = <?php echo json_encode($productTotal); ?>;
    const ctx = document.getElementById('myChart').getContext('2d');

    new Chart  (ctx, {
        type: 'bar', 
        data: {
            labels: productNames,
            datasets: [{label: 'Quantity Sold',
                data: productQuantity,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
            }]
        },
        options:{
            responsive: true,
            scales: {
                y:{
                    baginAtZero: true
                }
            }
        } 
    });
    </script>
</body>
</html>