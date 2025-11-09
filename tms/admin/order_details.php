<?php
require_once 'config.php';

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$order_id = (int)$_GET['id'];

// Get order info
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    header('Location: admin.php');
    exit;
}

// Get order items
$stmt = $conn->prepare("
    SELECT m.name, oi.quantity, oi.price, (oi.quantity * oi.price) AS item_total
    FROM order_items oi
    JOIN menu_items m ON oi.menu_item_id = m.id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order #<?= $order['id'] ?> Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .order-details { background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); padding: 20px; }
        h1 { margin-top: 0; }
        .back-link { display: inline-block; margin-bottom: 20px; }
        .customer-info, .order-summary { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; font-size: 1.2em; text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="back-link">&larr; Back to Orders</a>
        
        <div class="order-details">
            <h1>Order #<?= $order['id'] ?></h1>
            
            <div class="customer-info">
                <h2>Customer Information</h2>
                <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($order['delivery_address']) ?></p>
                <p><strong>Order Date:</strong> <?= date('M j, Y g:i a', strtotime($order['order_date'])) ?></p>
                <p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
            </div>
            
            <div class="order-summary">
                <h2>Order Summary</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>$<?= number_format($item['item_total'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="total">
                    <p>Total: $<?= number_format($order['total_amount'], 2) ?></p>
                </div>
            </div>
            
            <form method="post" action="update_status.php">
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                <label for="status">Update Status:</label>
                <select name="status" id="status">
                    <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
                <button type="submit">Update</button>
            </form>
        </div>
    </div>
</body>
</html>