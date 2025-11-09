<?php
require_once 'config.php';

// Redirect to menu if cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: menu.php');
    exit;
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $customer_email = $conn->real_escape_string($_POST['customer_email']);
    $delivery_address = $conn->real_escape_string($_POST['delivery_address']);
    $total_amount = calculateCartTotal();
    $order_date = date('Y-m-d H:i:s');
    $status = 'pending';

    // Validate email
    if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        // Don't display error to user, just redirect
        header("Location: checkout.php");
        exit;
    } else {
        try {
            // Insert order with all required fields
            $sql = "INSERT INTO orders (customer_name, customer_email, delivery_address, order_date, status, total_amount) 
                    VALUES ('$customer_name', '$customer_email', '$delivery_address', '$order_date', '$status', $total_amount)";
            
            if ($conn->query($sql)) {
                $order_id = $conn->insert_id;
                
                // Insert order items using prepared statement
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price, subtotal) 
                                      VALUES (?, ?, ?, ?, ?)");
                
                foreach ($_SESSION['cart'] as $item) {
                    $menu_item_id = $item['id'];
                    $quantity = $item['quantity'];
                    $price = $item['price'];
                    $subtotal = $price * $quantity;
                    
                    $stmt->bind_param("iiidd", $order_id, $menu_item_id, $quantity, $price, $subtotal);
                    $stmt->execute();
                }
                
                // Clear cart
                $_SESSION['cart'] = [];
                
                // Redirect to success page
                header("Location: order_success.php?order_id=$order_id");
                exit;
            }
        } catch (mysqli_sql_exception $e) {
            // Log error instead of displaying
            error_log("Order error: " . $e->getMessage());
            header("Location: checkout.php");
            exit;
        }
    }
}

function calculateCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Restaurant Order</title>
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
        <h1 class="section-title">Checkout</h1>
        
        <div class="checkout-container">
            <div class="checkout-form">
                <h2 class="section-title">Customer Information</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="customer_name">Full Name *</label>
                        <input type="text" id="customer_name" name="customer_name" required
                               value="<?= isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="customer_email">Email Address *</label>
                        <input type="email" id="customer_email" name="customer_email" required
                               value="<?= isset($_POST['customer_email']) ? htmlspecialchars($_POST['customer_email']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="delivery_address">Delivery Address *</label>
                        <textarea id="delivery_address" name="delivery_address" required><?= 
                            isset($_POST['delivery_address']) ? htmlspecialchars($_POST['delivery_address']) : '' 
                        ?></textarea>
                    </div>
                    <button type="submit" name="place_order" class="btn">Place Order</button>
                </form>
            </div>
            
            <div class="order-summary">
                <h2 class="section-title">Order Summary</h2>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="order-item">
                        <span><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                        <span>Kshs. <?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                    </div>
                <?php endforeach; ?>
                
                <div class="order-total">
                    <span>Total:</span>
                    <span>Kshs. <?= number_format(calculateCartTotal(), 2) ?></span>
                </div>
            </div>
        </div>
    </main>
</body>
</html>