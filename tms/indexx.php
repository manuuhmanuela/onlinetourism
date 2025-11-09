<?php
require_once 'config.php';

// Simple authentication
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Admin login check
$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Handle admin login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    if ($_POST['username'] === 'admin' && $_POST['password'] === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $is_admin = true;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error = "Invalid credentials";
    }
}

// Handle menu item submission
if ($is_admin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_menu_item'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    
    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = $target_file;
        }
    }
    
    $sql = "INSERT INTO menu_items (name, description, price, category, image_url) 
            VALUES ('$name', '$description', $price, '$category', '$image_url')";
    
    if ($conn->query($sql)) {
        $success_message = "Menu item added successfully!";
    } else {
        $error_message = "Error adding menu item: " . $conn->error;
    }
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $customer_phone = $conn->real_escape_string($_POST['customer_phone']);
    $customer_address = $conn->real_escape_string($_POST['customer_address']);
    $total_amount = 0;
    
    // Calculate total amount
    foreach ($_POST['items'] as $item_id => $quantity) {
        if ($quantity > 0) {
            $item_result = $conn->query("SELECT price FROM menu_items WHERE id = $item_id");
            if ($item_result && $item_result->num_rows > 0) {
                $item = $item_result->fetch_assoc();
                $total_amount += $item['price'] * $quantity;
            }
        }
    }
    
    if ($total_amount > 0) {
        // Insert order
        $sql = "INSERT INTO orders (customer_name, customer_phone, customer_address, total_amount, status) 
                VALUES ('$customer_name', '$customer_phone', '$customer_address', $total_amount, 'pending')";
        
        if ($conn->query($sql)) {
            $order_id = $conn->insert_id;
            
            // Insert order items
            foreach ($_POST['items'] as $item_id => $quantity) {
                if ($quantity > 0) {
                    $item_result = $conn->query("SELECT price FROM menu_items WHERE id = $item_id");
                    if ($item_result && $item_result->num_rows > 0) {
                        $item = $item_result->fetch_assoc();
                        $price = $item['price'];
                        $subtotal = $price * $quantity;
                        
                        $conn->query("INSERT INTO order_items (order_id, item_id, quantity, price, subtotal) 
                                       VALUES ($order_id, $item_id, $quantity, $price, $subtotal)");
                    }
                }
            }
            
            $order_success = "Order placed successfully! Your order ID is #$order_id";
        } else {
            $order_error = "Error placing order: " . $conn->error;
        }
    } else {
        $order_error = "Please select at least one item to order";
    }
}

// Get all menu items
$menu_items = [];
$categories = [];
$result = $conn->query("SELECT * FROM menu_items ORDER BY category, name");
if ($result) {
    $menu_items = $result->fetch_all(MYSQLI_ASSOC);
    // Get unique categories
    $categories = array_unique(array_column($menu_items, 'category'));
}

// Show admin login if not logged in
if (!$is_admin && basename($_SERVER['PHP_SELF']) == 'admin.php') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
            .login-form { max-width: 400px; margin: 100px auto; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            h1 { text-align: center; color: #333; }
            .form-group { margin-bottom: 15px; }
            label { display: block; margin-bottom: 5px; }
            input { width: 100%; padding: 8px; box-sizing: border-box; }
            button { background: #4CAF50; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; }
            .error { color: red; text-align: center; }
        </style>
    </head>
    <body>
        <div class="login-form">
            <h1>Admin Login</h1>
            <?php if (isset($error)): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $is_admin ? 'TMS Admin - Menu Management' : 'Our Menu - Order Online' ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        header { background-color: #333; color: white; padding: 20px 0; text-align: center; margin-bottom: 30px; position: relative; }
        .logout { position: absolute; right: 20px; top: 20px; }
        .logout a { color: white; text-decoration: none; background: #f44336; padding: 8px 15px; border-radius: 3px; }
        .section { background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 30px; }
        h2 { margin-top: 0; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        tr:hover { background-color: #f9f9f9; }
        .menu-image { max-width: 100px; max-height: 100px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { 
            width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; box-sizing: border-box; 
        }
        .form-group textarea { height: 100px; }
        button { background: #4CAF50; color: white; border: none; padding: 10px 15px; border-radius: 3px; cursor: pointer; }
        button:hover { background: #45a049; }
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 3px; }
        .alert-success { background: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; }
        .alert-error { background: #f2dede; color: #a94442; border: 1px solid #ebccd1; }
        
        /* Customer menu styles */
        .menu-category { margin-bottom: 30px; }
        .menu-items { display: flex; flex-wrap: wrap; gap: 20px; }
        .menu-item { flex: 1 1 300px; border: 1px solid #ddd; border-radius: 5px; padding: 15px; background: white; }
        .menu-item-image { width: 100%; height: 200px; object-fit: cover; border-radius: 5px; margin-bottom: 10px; }
        .menu-item-name { font-weight: bold; font-size: 1.2em; margin-bottom: 5px; }
        .menu-item-price { color: #e67e22; font-weight: bold; margin-bottom: 10px; }
        .menu-item-quantity { width: 60px; padding: 5px; text-align: center; }
        .checkout-form { max-width: 600px; margin: 0 auto; }
        .nav { display: flex; justify-content: center; margin-bottom: 20px; }
        .nav a { margin: 0 10px; padding: 10px 15px; background: #333; color: white; text-decoration: none; border-radius: 3px; }
        .nav a:hover { background: #555; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><?= $is_admin ? 'TMS Admin Panel' : 'Restaurant Menu - Order Online' ?></h1>
            <?php if ($is_admin): ?>
                <div class="logout">
                    <a href="?logout=1">Logout</a>
                </div>
            <?php else: ?>
                <div class="nav">
                    <a href="#menu">View Menu</a>
                    <a href="#order">Place Order</a>
                </div>
            <?php endif; ?>
        </header>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?= $error_message ?></div>
        <?php endif; ?>
        
        <?php if (isset($order_success)): ?>
            <div class="alert alert-success"><?= $order_success ?></div>
        <?php endif; ?>
        
        <?php if (isset($order_error)): ?>
            <div class="alert alert-error"><?= $order_error ?></div>
        <?php endif; ?>
        
        <?php if ($is_admin): ?>
            <!-- Admin Panel Content -->
            <div class="section">
                <h2>Add New Menu Item</h2>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Item Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" id="category" name="category" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
                    </div>
                    <button type="submit" name="add_menu_item">Add Menu Item</button>
                </form>
            </div>
            
            <div class="section">
                <h2>Current Menu Items</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menu_items as $item): ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td>
                                <?php if (!empty($item['image_url'])): ?>
                                    <img src="<?= $item['image_url'] ?>" class="menu-image">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= htmlspecialchars($item['category']) ?></td>
                            <td>
                                <a href="edit_menu_item.php?id=<?= $item['id'] ?>">Edit</a> | 
                                <a href="delete_menu_item.php?id=<?= $item['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Customer Menu Content -->
            <div id="menu" class="section">
                <h2>Our Menu</h2>
                <?php foreach ($categories as $category): ?>
                    <div class="menu-category">
                        <h3><?= htmlspecialchars($category) ?></h3>
                        <div class="menu-items">
                            <?php foreach ($menu_items as $item): ?>
                                <?php if ($item['category'] == $category): ?>
                                    <div class="menu-item">
                                        <?php if (!empty($item['image_url'])): ?>
                                            <img src="<?= $item['image_url'] ?>" class="menu-item-image">
                                        <?php endif; ?>
                                        <div class="menu-item-name"><?= htmlspecialchars($item['name']) ?></div>
                                        <div class="menu-item-price">$<?= number_format($item['price'], 2) ?></div>
                                        <div class="menu-item-description"><?= htmlspecialchars($item['description']) ?></div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div id="order" class="section">
                <h2>Place Your Order</h2>
                <form method="post" class="checkout-form">
                    <h3>Order Items</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menu_items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td>$<?= number_format($item['price'], 2) ?></td>
                                    <td>
                                        <input type="number" name="items[<?= $item['id'] ?>]" 
                                               class="menu-item-quantity" min="0" value="0">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <h3>Customer Information</h3>
                    <div class="form-group">
                        <label for="customer_name">Full Name</label>
                        <input type="text" id="customer_name" name="customer_name" required>
                    </div>
                    <div class="form-group">
                        <label for="customer_phone">Phone Number</label>
                        <input type="tel" id="customer_phone" name="customer_phone" required>
                    </div>
                    <div class="form-group">
                        <label for="customer_address">Delivery Address</label>
                        <textarea id="customer_address" name="customer_address" required></textarea>
                    </div>
                    
                    <button type="submit" name="place_order">Place Order</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>