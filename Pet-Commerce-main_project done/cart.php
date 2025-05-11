<?php
session_start();
include 'database.php'; // Ensure database connection is included

// Remove item from cart functionality
if (isset($_POST['remove_from_cart'])) {
    $productId = $_POST['product_id'];
    unset($_SESSION['cart'][$productId]); // Remove product from the session cart
    header('Location: cart.php'); // Redirect to cart after removal
    exit();
}

// Update item quantity functionality
if (isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $newQuantity = max(1, (int)$_POST['quantity']); // Ensure quantity is at least 1
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity; // Update quantity
    }
    header('Location: cart.php'); // Redirect to cart after update
    exit();
}

// Empty cart functionality
if (isset($_POST['empty_cart'])) {
    unset($_SESSION['cart']);
    header('Location: cart.php'); // Redirect to cart after emptying
    exit();
}

// Fetch cart items (from session)
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Calculate total price
$totalPrice = 0;
foreach ($cart as $item) {
    $totalPrice += $item['price'] * $item['quantity']; // Calculate total price
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="css/cart.css..">
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
    <title>Your Cart</title>
</head>
<body>
    <header>
    <div class="logo-container">
        <img src="css/images/logo.png" alt="Pets Vibe Logo">
        <h1>Pets Vibe</h1>
    </div>
    <a href="productlisting.php">Back to Shop</a>
    </header>

    <div class="cart-container">
        <?php if (empty($cart)) : ?>
            <p>Your cart is empty. <a href="productlisting.php" class="continueshoping">Continue shopping</a></p>

        <?php else : ?>
            <table>
            
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $productId => $item) : ?>
                        <tr>
                            <td>
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="100">
                            </td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>₹<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form action="cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                    <button type="submit" name="update_quantity">Update</button>
                                </form>
                            </td>
                            <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <form action="cart.php" method="post" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                    <button type="submit" name="remove_from_cart">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="cart-actions">
                <form action="cart.php" method="post" style="display:inline;">
                    <button type="submit" name="empty_cart" class="empty-cart-btn">Empty Cart</button>
                </form>
                <div class="total-price">
                    <p>Total Price: ₹<?php echo number_format($totalPrice, 2); ?></p>
                </div>
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
