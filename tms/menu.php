<?php
require_once 'config.php';

// Get all menu items
$menu_items = [];
$categories = [];
$result = $conn->query("SELECT * FROM menu_items ORDER BY category, name");
if ($result) {
    $menu_items = $result->fetch_all(MYSQLI_ASSOC);
    $categories = array_unique(array_column($menu_items, 'category'));
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $item_id = intval($_POST['item_id']);
    $quantity = intval($_POST['quantity']);
    
    if ($quantity > 0) {
        // Get item details
        $stmt = $conn->prepare("SELECT id, name, price FROM menu_items WHERE id = ?");
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $item = $result->fetch_assoc();
            
            // Add to cart or update quantity
            if (isset($_SESSION['cart'][$item_id])) {
                $_SESSION['cart'][$item_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$item_id] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $quantity
                ];
            }
            
            $cart_success = "{$item['name']} added to cart!";
        }
    }
}

// Calculate cart total
$cart_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menu - Order Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #e67e22;
            --secondary-color: #333;
            --light-color: #f5f5f5;
            --dark-color: #222;
            --success-color: #4CAF50;
            --danger-color: #f44336;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            background-color: var(--light-color);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        header {
            background-color: var(--secondary-color);
            color: white;
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        
        .logo span {
            color: var(--primary-color);
        }
        
        .cart-icon {
            position: relative;
            color: white;
            font-size: 1.5rem;
            text-decoration: none;
        }
        
        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2rem;
            color: var(--secondary-color);
        }
        
        .menu-category {
            margin-bottom: 50px;
        }
        
        .category-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
            color: var(--secondary-color);
        }
        
        .menu-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .menu-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .menu-item:hover {
            transform: translateY(-5px);
        }
        
        .menu-item-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .menu-item-content {
            padding: 20px;
        }
        
        .menu-item-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }
        
        .menu-item-description {
            color: #666;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        
        .menu-item-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .menu-item-price {
            font-weight: bold;
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .menu-item-quantity {
            width: 60px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }
        
        .add-to-cart-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .add-to-cart-btn:hover {
            background-color: #d35400;
        }
        
        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100%;
            background: white;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            transition: right 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .cart-sidebar.open {
            right: 0;
        }
        
        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .cart-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .close-cart {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }
        
        .cart-items {
            padding: 20px;
        }
        
        .cart-item {
            display: flex;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }
        
        .cart-item-details {
            flex: 1;
        }
        
        .cart-item-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .cart-item-price {
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .cart-item-quantity {
            display: flex;
            align-items: center;
        }
        
        .quantity-btn {
            background: #f0f0f0;
            border: none;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .quantity-input {
            width: 50px;
            text-align: center;
            margin: 0 5px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .remove-item {
            color: var(--danger-color);
            background: none;
            border: none;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .cart-summary {
            padding: 20px;
            border-top: 1px solid #eee;
        }
        
        .cart-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .checkout-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--success-color);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .checkout-btn:hover {
            background-color: #3d8b40;
        }
        
        .empty-cart {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        
        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }
        
        .overlay.active {
            display: block;
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .menu-items {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
            
            .cart-sidebar {
                width: 100%;
                right: -100%;
            }
            
            .cart-sidebar.open {
                right: 0;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>
    
    <!-- Header -->
    <header>
        <div class="container header-content">
            <a href="menu.php" class="logo">Restaurant<span>Menu</span></a>
            <a href="#" class="cart-icon" id="cart-toggle">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count"><?= array_sum(array_column($_SESSION['cart'], 'quantity')) ?></span>
            </a>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div>
                <h1>Delicious Food Delivered To You</h1>
                <p>Order from our wide selection of mouth-watering dishes and enjoy a restaurant-quality meal at home</p>
            </div>
        </div>
    </section>
    
    <!-- Main Content -->
    <main class="container">
        <?php if (isset($cart_success)): ?>
            <div class="alert alert-success">
                <?= $cart_success ?>
            </div>
        <?php endif; ?>
        
        <h1 class="section-title">Our Menu</h1>
        
        <?php foreach ($categories as $category): ?>
            <div class="menu-category">
                <h2 class="category-title"><?= htmlspecialchars($category) ?></h2>
                <div class="menu-items">
                    <?php foreach ($menu_items as $item): ?>
                        <?php if ($item['category'] == $category): ?>
                            <div class="menu-item">
                                <?php if (!empty($item['image_url'])): ?>
                                    <img src="<?= $item['image_url'] ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="menu-item-image">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/300x200?text=No+Image" alt="No image available" class="menu-item-image">
                                <?php endif; ?>
                                <div class="menu-item-content">
                                    <h3 class="menu-item-name"><?= htmlspecialchars($item['name']) ?></h3>
                                    <p class="menu-item-description"><?= htmlspecialchars($item['description']) ?></p>
                                    <div class="menu-item-footer">
                                        <span class="menu-item-price">Kshs. <?= number_format($item['price'], 2) ?></span>
                                        <form method="post" class="add-to-cart-form">
                                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                            <input type="number" name="quantity" value="1" min="1" class="menu-item-quantity">
                                            <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
    
    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cart-sidebar">
        <div class="cart-header">
            <h2 class="cart-title">Your Order</h2>
            <button class="close-cart" id="close-cart">&times;</button>
        </div>
        
        <div class="cart-items">
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart" style="font-size: 3rem; margin-bottom: 15px;"></i>
                    <p>Your cart is empty</p>
                </div>
            <?php else: ?>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="cart-item">
                        <?php 
                        // Get the image URL for this item from the database
                        $image_url = '';
                        $stmt = $conn->prepare("SELECT image_url FROM menu_items WHERE id = ?");
                        $stmt->bind_param("i", $item['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            $menu_item = $result->fetch_assoc();
                            $image_url = $menu_item['image_url'];
                        }
                        ?>
                        <?php if (!empty($image_url)): ?>
                            <img src="<?= $image_url ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-image">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/80x80?text=No+Image" alt="No image available" class="cart-item-image">
                        <?php endif; ?>
                        <div class="cart-item-details">
                            <h3 class="cart-item-name"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="cart-item-price">Kshs. <?= number_format($item['price'], 2) ?></p>
                            <div class="cart-item-quantity">
                                <button class="quantity-btn minus" data-id="<?= $item['id'] ?>">-</button>
                                <input type="number" value="<?= $item['quantity'] ?>" min="1" class="quantity-input" data-id="<?= $item['id'] ?>">
                                <button class="quantity-btn plus" data-id="<?= $item['id'] ?>">+</button>
                                <button class="remove-item" data-id="<?= $item['id'] ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="cart-summary">
                <div class="cart-total">
                    <span>Total:</span>
                    <span>$<?= number_format($cart_total, 2) ?></span>
                </div>
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Cart toggle functionality
        const cartToggle = document.getElementById('cart-toggle');
        const cartSidebar = document.getElementById('cart-sidebar');
        const closeCart = document.getElementById('close-cart');
        const overlay = document.getElementById('overlay');
        
        cartToggle.addEventListener('click', (e) => {
            e.preventDefault();
            cartSidebar.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        closeCart.addEventListener('click', () => {
            cartSidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        overlay.addEventListener('click', () => {
            cartSidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        // Quantity controls
        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemId = this.getAttribute('data-id');
                const input = document.querySelector(`.quantity-input[data-id="${itemId}"]`);
                let quantity = parseInt(input.value);
                
                if (this.classList.contains('minus')) {
                    if (quantity > 1) {
                        input.value = quantity - 1;
                        updateCartItem(itemId, input.value);
                    }
                } else if (this.classList.contains('plus')) {
                    input.value = quantity + 1;
                    updateCartItem(itemId, input.value);
                }
            });
        });
        
        // Quantity input change
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const itemId = this.getAttribute('data-id');
                const quantity = parseInt(this.value);
                
                if (quantity < 1) {
                    this.value = 1;
                }
                
                updateCartItem(itemId, this.value);
            });
        });
        
        // Remove item
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemId = this.getAttribute('data-id');
                removeCartItem(itemId);
            });
        });
        
        // Update cart item quantity
        function updateCartItem(itemId, quantity) {
            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `item_id=${itemId}&quantity=${quantity}&action=update`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    document.querySelector('.cart-count').textContent = data.cart_count;
                    // Update total
                    if (data.cart_total) {
                        document.querySelector('.cart-total span:last-child').textContent = `$${data.cart_total.toFixed(2)}`;
                    }
                    // Reload if item was removed (quantity 0)
                    if (data.removed) {
                        location.reload();
                    }
                }
            });
        }
        
        // Remove cart item
        function removeCartItem(itemId) {
            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `item_id=${itemId}&action=remove`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>