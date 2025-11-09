<?php
require_once 'config.php';

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    header('Location: menu.php');
    exit;
}

$order_id = intval($_GET['order_id']);

// Get order details
$order = [];
$order_items = [];

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    
    // Get order items - CORRECTED to use menu_item_id instead of item_id
    $stmt = $conn->prepare("SELECT mi.name, oi.quantity, oi.price, oi.subtotal 
                           FROM order_items oi 
                           JOIN menu_items mi ON oi.menu_item_id = mi.id 
                           WHERE oi.order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $items_result = $stmt->get_result();
    
    if ($items_result) {
        $order_items = $items_result->fetch_all(MYSQLI_ASSOC);
    }
} else {
    header('Location: menu.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Restaurant Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* [Keep all your existing CSS styles] */
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <a href="menu.php" class="logo">Restaurant<span>Menu</span></a>
        </div>
    </header>
    
    <main class="container">
        <div class="success-container">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title">Order Confirmed!</h1>
            <p>Thank you for your order. We've received it and will start preparing your food shortly.</p>
            
            <div class="order-details">
                <div class="order-info">
                    <p><strong>Order Number:</strong> #<?= $order['id'] ?></p>
                    <p><strong>Order Date:</strong> <?= date('F j, Y \a\t g:i A', strtotime($order['order_date'])) ?></p>
                    <p><strong>Customer Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                    <p><strong>Delivery Address:</strong> <?= htmlspecialchars($order['delivery_address']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                </div>
                
                <div class="order-items">
                    <h3>Order Items:</h3>
                    <?php foreach ($order_items as $item): ?>
                        <div class="order-item">
                            <span><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                            <span>$<?= number_format($item['subtotal'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="order-total">
                        <span>Total:</span>
                        <span>$<?= number_format($order['total_amount'], 2) ?></span>
                    </div>
                </div>
            </div>
            
            <a href="menu.php" class="btn">Back to Menu</a>
        </div>
    </main>
</body>
</html>