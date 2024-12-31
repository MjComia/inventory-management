<?php 
require 'dompdf/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$conn = new mysqli("localhost", "root", "", "ims_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if(isset($_POST['customer_id'])){
    $custumer_id = $_POST['customer_id'];
    $sql  = "SELECT * FROM ims_customer WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $custumer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $html = "<h1>Report</h1>";
        
        while($row = $result->fetch_assoc()){
        $html .= "<div class = 'container'>
                    <div>Name:". $row['name']."</div>
                    <div>ID: ". $row['id']."</div>
                    <div>Address: ". $row['address']."</div>
                    <div>Mobile: ". $row['mobile']."</div>
                    </div>";
            $html .= "<hr>";
            $idCustomer = $row['id'];
        }
        $sql2 = "SELECT * FROM ims_order WHERE customer_id = '$idCustomer'";
        $result2 = $conn->query($sql2);

        if($result2->num_rows > 0){
            while($row2 = $result2->fetch_assoc()){
                $html .= "<div class = 'container'>
                            <div>Product ID: ".$row2['product_id']."</div> 
                            <div>Order Date: ".$row2['order_date']."</div> 
                            <div>Total Shipped: ".$row2['total_shipped']."</div></div>";
                            $html .= "<hr>";
                $purchaseID = $row2['product_id'];

              $sql3 = "SELECT * FROM ims_product WHERE pid = '$purchaseID'";
                $result3 = $conn->query($sql3);
                if($result3->num_rows > 0 ){
                    while($row3 = $result3->fetch_assoc()){
                        $html .= "<div class = 'container'>
                        <div>Brand ID: ".$row3['brandid']." </div>
                        <div>Product Name: ".$row3['pname']." </div>
                        <div>Model: ".$row3['model']." </div>
                        <div>Description: ".$row3['description']." </div>
                        </div>";
                    }
                }
            }
        }

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("report.pdf", array("Attachment" => false));
    }else {
        echo "No data found";
    }
    $stmt->close();
}
$conn->close();
?>