
<?php
$conn = new mysqli("localhost", "root", "root", "LTW");

// Get product sales data
$sales_query = "SELECT p.product_name, SUM(id.quantity) as total_quantity 
                FROM invoice_details id
                JOIN products p ON id.product_id = p.id 
                GROUP BY id.product_id";
$sales_result = mysqli_query($conn, $sales_query);
$sales_data = [];
while ($row = mysqli_fetch_assoc($sales_result)) {
    $sales_data[] = $row;
}

// Get revenue data
$revenue_query = "SELECT p.product_name, SUM(id.quantity * p.price) as total_revenue 
                 FROM invoice_details id
                 JOIN products p ON id.product_id = p.id 
                 GROUP BY id.product_id";
$revenue_result = mysqli_query($conn, $revenue_query);
$revenue_data = [];
while ($row = mysqli_fetch_assoc($revenue_result)) {
    $revenue_data[] = $row;
}

echo json_encode(['sales' => $sales_data, 'revenue' => $revenue_data]);
?>