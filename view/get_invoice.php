<?php
$conn = new mysqli("localhost", "root", "root", "LTW");

$id = $_GET['id'];
$invoice = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM invoices WHERE invoice_id=$id"));
$details = [];
$result = mysqli_query($conn, "
    SELECT id.*, p.product_name, p.price 
    FROM invoice_details id 
    JOIN products p ON id.product_id = p.id 
    WHERE id.invoice_id=$id
");
while ($row = mysqli_fetch_assoc($result)) {
    $details[] = $row;
}

echo json_encode(['invoice' => $invoice, 'details' => $details]);
?>