<?php
header('Content-Type: application/json');
require_once 'config.php';

$action = $_GET['action'] ?? '';

try {
    switch($action) {
        case 'get_menu':
            $stmt = $conn->query("SELECT * FROM menu_items");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;
            
        case 'create_order':
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Start transaction
            $conn->beginTransaction();
            
            // Insert order
            $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, delivery_address, total_amount) 
                                  VALUES (:name, :email, :phone, :address, :total)");
            $stmt->execute([
                ':name' => $data['customer_name'],
                ':email' => $data['customer_email'],
                ':phone' => $data['customer_phone'],
                ':address' => $data['delivery_address'],
                ':total' => $data['total_amount']
            ]);
            $order_id = $conn->lastInsertId();
            
            // Insert order items
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) 
                                  VALUES (:order_id, :item_id, :quantity, :price)");
            foreach ($data['items'] as $item) {
                $stmt->execute([
                    ':order_id' => $order_id,
                    ':item_id' => $item['id'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price']
                ]);
            }
            
            // Commit transaction
            $conn->commit();
            
            echo json_encode(['order_id' => $order_id, 'message' => 'Order placed successfully']);
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }
} catch(PDOException $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>