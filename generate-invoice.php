<?php
require_once '../config/db.php';
require_once './dompdf/autoload.inc.php';

use Dompdf\Dompdf;

if (!isset($_GET['id'])) {
    die("Invalid Order ID");
}

$order_id = (int) $_GET['id'];

/* ORDER */
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Order not found");
}

/* ITEMS */
$items = $conn->query("
    SELECT oi.*, p.name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = $order_id
");

$total = 0;

/* LOGO PATH (ABSOLUTE) */
$logoPath = '../assets/images/logo.png';
$logoBase64 = base64_encode(file_get_contents($logoPath));

$html = '
<style>
body {
    font-family: Arial, sans-serif;
    color: #333;
}

/* HEADER */
.header {
    text-align: center;
    padding-bottom: 10px;
    border-bottom: 2px solid #111827;
}

.logo {
    width: 120px;
    margin-bottom: 10px;
}

.company {
    font-size: 20px;
    font-weight: bold;
}

/* INVOICE INFO */
.info {
    margin-top: 20px;
    font-size: 13px;
}

.info-box {
    padding: 10px;
    border: 1px solid #ddd;
    margin-top: 10px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th {
    background: #111827;
    color: white;
    padding: 10px;
    font-size: 12px;
}

td {
    border: 1px solid #ddd;
    padding: 8px;
    font-size: 12px;
}

/* TOTAL */
.total {
    text-align: right;
    margin-top: 20px;
    font-size: 16px;
    font-weight: bold;
}

/* STATUS */
.status {
    display: inline-block;
    padding: 5px 10px;
    background: #dcfce7;
    color: #166534;
    border-radius: 5px;
    font-size: 12px;
}

/* FOOTER */
.footer {
    margin-top: 40px;
    text-align: center;
    font-size: 11px;
    color: #777;
}
</style>

<div class="header">
    <img class="logo" src="data:image/png;base64,' . $logoBase64 . '">
    <div class="company">Rongin Sajghor Jewellery</div>
    <div>Official Invoice</div>
</div>

<div class="info">
    <div class="info-box">
        <strong>Invoice ID:</strong> #' . $order['id'] . '<br>
        <strong>Status:</strong> <span class="status">' . $order['status'] . '</span><br>
        <strong>Date:</strong> ' . $order['created_at'] . '
    </div>

    <div class="info-box">
        <strong>Customer Details</strong><br>
        Name: ' . $order['customer_name'] . '<br>
        Phone: ' . $order['phone'] . '<br>
        Address: ' . $order['address'] . '<br>
        City: ' . $order['city'] . '
    </div>
</div>

<table>
<tr>
    <th>Product</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Total</th>
</tr>';

while ($item = $items->fetch_assoc()) {

    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;

    $html .= '
    <tr>
        <td>' . $item['name'] . '</td>
        <td>' . $item['quantity'] . '</td>
        <td>' . number_format($item['price'], 2) . '</td>
        <td>' . number_format($subtotal, 2) . '</td>
    </tr>';
}

$html .= '
</table>

<div class="total">
    Grand Total:' . number_format($total, 2) . '
</div>

<div class="footer">
    Thank you for shopping with us ❤️<br>
    Rongin Sajghor - Premium Jewellery Store
</div>
';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("invoice_" . $order_id . ".pdf", ["Attachment" => 1]);
exit();