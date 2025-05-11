<?php
session_start();
include 'database.php'; // Include the file containing your database functions

// Fetch product details
$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Validate ID input
$productQuery = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($productQuery);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("<h1>Product Not Found!</h1>"); // Display error if product is missing
}

// Decode images from JSON (all images)
$images = json_decode($product['images'], true); // Decoding JSON string to PHP array

// Handle add to cart functionality
if (isset($_GET['add_to_cart'])) {
    $productId = (int)$_GET['add_to_cart'];

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$productId])) {
        // Increment the quantity if the product is already in the cart
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // Add the product to the cart session
        $_SESSION['cart'][$productId] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1,
            'image' => $images[0] ?? 'default_image.jpg' // Use the first image if available
        ];
    }

    // Redirect back to the product details page to show updated cart
    header('Location: productdetails.php?id=' . $product['id']);
    exit();
}

// Handle removing a product from the cart
if (isset($_GET['remove_from_cart'])) {
    $productIdToRemove = (int)$_GET['remove_from_cart'];

    // Remove product from the session cart
    if (isset($_SESSION['cart'][$productIdToRemove])) {
        unset($_SESSION['cart'][$productIdToRemove]);
    }

    // Redirect back to the product details page or cart page
    header('Location: productdetails.php?id=' . $product['id']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  <title>Pets Vibe - Product Details</title>
  <link rel="stylesheet" href="css/productdetails.css.">
  <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
</head>
<body>

  <!-- Header with Logo -->
  <header>
    <div class="logo img">
      <a href="index.php">
        <img src="css/images/logo.png" alt="Your Logo Here" />
      </a>
    </div>
<h4 class="title">PETS-VIBE</h4>
    <!-- Cart Button as Image -->
    <img id="cart-btn" src="css/images/cart.png" alt="Cart" class="cart-img" />
  </header>

  <!-- Product Details Section -->
  <div class="product-details">
    <div class="carousel">
      <!-- Main Image (Larger Image) -->
      <?php if (!empty($images)): ?>
        <img id="main-image" src="<?php echo htmlspecialchars($images[0]); ?>" alt="Main Product Image" class="main-image">
        <div class="thumbnails">
          <?php foreach ($images as $index => $img): ?>
            <img src="<?php echo htmlspecialchars($img); ?>" alt="Product Image" class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeImage(this)">
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>No images available.</p>
      <?php endif; ?>
    </div>

    <!-- Product Info Section -->
    <div class="product-info">
      <h2><?php echo htmlspecialchars($product['name']); ?></h2>
      <p class="price">Price: ₹<?php echo number_format($product['price']); ?></p>
      <table class="product-table">
        <tr><td>Category:</td><td><?php echo htmlspecialchars($product['category']); ?></td></tr>
        <tr><td>Brand:</td><td><?php echo htmlspecialchars($product['brand']); ?></td></tr>
        <tr><td>Size:</td><td><?php echo htmlspecialchars($product['size']); ?></td></tr>
        <tr><td>Color:</td><td><?php echo htmlspecialchars($product['color']); ?></td></tr>
        <tr><td>Description:</td><td><?php echo htmlspecialchars($product['description']); ?></td></tr>
      </table>
      <!-- Add to Cart Button -->
      <button id="add-to-cart" class="btn"><a href="productdetails.php?id=<?php echo $product['id']; ?>&add_to_cart=<?php echo $product['id']; ?>">Add to Cart</a></button>
    </div>
  </div>

  <!-- Suggested Products Section -->
  <section class="suggested-products">
    <h3>See More Products</h3>
    <div class="product-list">
      <?php
      // Fetch related products based on category or other criteria
      $relatedProductsQuery = "SELECT * FROM products WHERE category = ? LIMIT 4"; 
      $stmt = $conn->prepare($relatedProductsQuery);
      $stmt->bind_param("s", $product['category']);
      $stmt->execute();
      $relatedResult = $stmt->get_result();

      if ($relatedResult->num_rows > 0):
        while ($relatedProduct = $relatedResult->fetch_assoc()): ?>
          <div class="product-card">
            <img src="<?php echo htmlspecialchars(json_decode($relatedProduct['images'], true)[0]); ?>" alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>">
            <h4><?php echo htmlspecialchars($relatedProduct['name']); ?></h4>
            <p>PRICE: ₹<?php echo number_format($relatedProduct['price']); ?> INR</p>
            <p>Description: <?php echo htmlspecialchars(implode(' ', array_slice(explode(' ', $relatedProduct['description']), 0, 20))); ?>...</p>
            <button class="add-to-cart-btn"><a href="productdetails.php?id=<?php echo $relatedProduct['id']; ?>&add_to_cart=<?php echo $relatedProduct['id']; ?>">Buy Now</a></button>
            <a href="productdetails.php?id=<?php echo $relatedProduct['id']; ?>" class="view-details-btn">View Details</a>   
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No related products available.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- Cart Sidebar (Popup) -->
  <div id="cart-sidebar" class="cart-sidebar">
    <div class="cart-sidebar-content">
      <button id="close-cart" class="close-cart-btn">❌</button>
      <h2>Your Cart</h2>
      <div class="cart-items">
        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
          <?php foreach ($_SESSION['cart'] as $item): ?>
            <div class="cart-item">
              <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-item-image"/>
              <span><?php echo htmlspecialchars($item['name']); ?></span>
              <span>₹<?php echo number_format($item['price'], 2); ?> x <?php echo $item['quantity']; ?></span>
              <a href="productdetails.php?id=<?php echo $item['id']; ?>&remove_from_cart=<?php echo $item['id']; ?>" class="remove-btn">Remove</a>
            </div>
          <?php endforeach; ?>
          <div class="cart-total">
            <strong>Total: ₹<?php echo number_format(array_sum(array_map(function($item) {
              return $item['price'] * $item['quantity'];
            }, $_SESSION['cart'])), 2); ?></strong>
          </div>
          <a href="cart.php" class="checkout-btn">Proceed to Checkout</a>
        <?php else: ?>
          <p>Your cart is empty.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const cartIcon = document.getElementById("cart-btn");
    const cartSidebar = document.getElementById("cart-sidebar");
    const closeCartButton = document.getElementById("close-cart");

    // Initially hidden
    cartSidebar.classList.remove('open'); // Ensure sidebar is closed initially

    // Toggle Cart Sidebar on Icon Click
    cartIcon.addEventListener("click", function () {
      cartSidebar.classList.toggle('open'); // Add/remove open class to toggle visibility
    });

    // Close Cart Sidebar
    closeCartButton.addEventListener("click", function () {
      cartSidebar.classList.remove('open'); // Remove open class to hide the sidebar
    });

    // Function to change main image when clicking on thumbnails
    window.changeImage = function (thumbnail) {
      const mainImage = document.getElementById("main-image");
      mainImage.src = thumbnail.src;
      const thumbnails = document.querySelectorAll(".thumbnail");

      thumbnails.forEach(function (img) {
        img.classList.remove("active");
      });
      thumbnail.classList.add("active");
    }
  });
</script>


</body>
</html>
