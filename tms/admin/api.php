<?php
header('Content-Type: application/json');
require_once 'config.php';

// Get all menu items
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'get_menu') {
    $result = $conn->query("SELECT * FROM menu_items");
    $menu = [];
    while ($row = $result->fetch_assoc()) {
        $menu[] = $row;
    }
    echo json_encode($menu);
    exit;
}

// Create a new order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'create_order') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $customer_name = $data['customer_name'];
    $customer_email = $data['customer_email'];
    $customer_phone = $data['customer_phone'];
    $delivery_address = $data['delivery_address'];
    $items = $data['items'];
    $total_amount = $data['total_amount'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, delivery_address, total_amount) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", $customer_name, $customer_email, $customer_phone, $delivery_address, $total_amount);
        $stmt->execute();
        $order_id = $conn->insert_id;
        $stmt->close();
        
        // Insert order items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }
        $stmt->close();
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode(['order_id' => $order_id, 'message' => 'Order placed successfully']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// Get all orders (admin view)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'get_orders') {
    $result = $conn->query("
        SELECT o.*, 
               GROUP_CONCAT(CONCAT(m.name, ' (', oi.quantity, ' x $', oi.price, ')') AS items
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN menu_items m ON oi.menu_item_id = m.id
        GROUP BY o.id
        ORDER BY o.order_date DESC
    ");
    
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    echo json_encode($orders);
    exit;
}

// Get order details by ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'get_order' && isset($_GET['id'])) {
    $order_id = $_GET['id'];
    
    // Get order info
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$order) {
        http_response_code(404);
        echo json_encode(['error' => 'Order not found']);
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
    
    echo json_encode(['order' => $order, 'items' => $items]);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Invalid request']);
?>