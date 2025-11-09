<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = intval($_POST['item_id']);
    $action = $_POST['action'];
    
    $response = ['success' => false];
    
    if ($action === 'update' && isset($_POST['quantity'])) {
        $quantity = intval($_POST['quantity']);
        
        if ($quantity > 0 && isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id]['quantity'] = $quantity;
            $response['success'] = true;
            $response['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));
            $response['cart_total'] = calculateCartTotal();
        } elseif ($quantity === 0 && isset($_SESSION['cart'][$item_id])) {
            unset($_SESSION['cart'][$item_id]);
            $response['success'] = true;
            $response['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));
            $response['cart_total'] = calculateCartTotal();
            $response['removed'] = true;
        }
    } elseif ($action === 'remove' && isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
        $response['success'] = true;
        $response['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));
        $response['cart_total'] = calculateCartTotal();
    }
    
    echo json_encode($response);
    exit;
}

function calculateCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}
?>