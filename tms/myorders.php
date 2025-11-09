<?php
require_once 'config.php';

// Get the latest order only
function getLatestOrder($conn) {
    $query = "SELECT id, customer_name, customer_email, delivery_address, 
                     order_date, status, total_amount 
              FROM orders 
              ORDER BY order_date DESC 
              LIMIT 1";
    $result = $conn->query($query);
    return $result ? $result->fetch_assoc() : null;
}

$latestOrder = getLatestOrder($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest Order</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
        }
        .order-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        .order-id {
            font-weight: bold;
            color: #343a40;
        }
        .order-date {
            color: #6c757d;
        }
        .order-status {
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: 500;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .customer-info {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
            color: #495057;
        }
        .info-value {
            color: #212529;
        }
        .order-total {
            font-size: 1.2rem;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #343a40;
        }
        .no-orders {
            text-align: center;
            color: #6c757d;
            padding: 40px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Order</h1>
        
        <?php if ($latestOrder): ?>
            <div class="order-card">
                <div class="order-header">
                    <span class="order-id">Order #<?= $latestOrder['id'] ?></span>
                    <span class="order-date">
                        <?= date('F j, Y \a\t g:i A', strtotime($latestOrder['order_date'])) ?>
                    </span>
                    <span class="order-status status-<?= strtolower($latestOrder['status']) ?>">
                        <?= $latestOrder['status'] ?>
                    </span>
                </div>
                
                <div class="customer-info">
                    <div class="info-row">
                        <span class="info-label">Customer Name:</span>
                        <span class="info-value"><?= htmlspecialchars($latestOrder['customer_name']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?= htmlspecialchars($latestOrder['customer_email']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Delivery Address:</span>
                        <span class="info-value"><?= htmlspecialchars($latestOrder['delivery_address']) ?></span>
                    </div>
                </div>
                
                <div class="order-total">
                    Total: Kshs. <?= number_format($latestOrder['total_amount'], 2) ?>
                </div>
            </div>
        <?php else: ?>
            <div class="no-orders">
                <p>No orders found in the system.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>