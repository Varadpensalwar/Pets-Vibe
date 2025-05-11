<?php
session_start(); // Ensure this is at the very top of the file, only once
include 'database.php'; // Ensure database connection is included

// Default values for the filters
$minPrice = isset($_GET['min-price']) ? (float)$_GET['min-price'] : 0;
$maxPrice = isset($_GET['max-price']) ? (float)$_GET['max-price'] : 10000;
$search = isset($_GET['search']) ? '%' . mysqli_real_escape_string($conn, $_GET['search']) . '%' : null;
$brand = isset($_GET['brand']) ? mysqli_real_escape_string($conn, $_GET['brand']) : null;
$size = isset($_GET['size']) ? mysqli_real_escape_string($conn, $_GET['size']) : null;
$color = isset($_GET['color']) ? mysqli_real_escape_string($conn, $_GET['color']) : null;
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : null;

// Fetch categories dynamically
$categoryQuery = "SELECT DISTINCT category FROM products";
$categoryResult = mysqli_query($conn, $categoryQuery);
if (!$categoryResult) {
    die("Error fetching categories: " . mysqli_error($conn));
}
$categories = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);

// Fetch brands dynamically
$brandQuery = "SELECT DISTINCT brand FROM products";
$brandResult = mysqli_query($conn, $brandQuery);
if (!$brandResult) {
    die("Error fetching brands: " . mysqli_error($conn));
}
$brands = mysqli_fetch_all($brandResult, MYSQLI_ASSOC);

// Fetch sizes dynamically
$sizeQuery = "SELECT DISTINCT size FROM products";
$sizeResult = mysqli_query($conn, $sizeQuery);
if (!$sizeResult) {
    die("Error fetching sizes: " . mysqli_error($conn));
}
$sizes = mysqli_fetch_all($sizeResult, MYSQLI_ASSOC);

// Fetch colors dynamically
$colorQuery = "SELECT DISTINCT color FROM products";
$colorResult = mysqli_query($conn, $colorQuery);
if (!$colorResult) {
    die("Error fetching colors: " . mysqli_error($conn));
}
$colors = mysqli_fetch_all($colorResult, MYSQLI_ASSOC);

// Build the SQL query dynamically based on filters
$query = "SELECT * FROM products WHERE price BETWEEN ? AND ?";
$types = "dd"; // Default to double for price filter
$params = [$minPrice, $maxPrice];

// Add the search filter, if any
if ($search) {
    $query .= " AND name LIKE ?";
    $types .= "s";
    $params[] = $search;
}

// Add the category filter, if any
if ($category) {
    $query .= " AND category = ?";
    $types .= "s";
    $params[] = $category;
}

// Add the brand filter, if any
if ($brand) {
    $query .= " AND brand = ?";
    $types .= "s";
    $params[] = $brand;
}

// Add the size filter, if any
if ($size) {
    $query .= " AND size = ?";
    $types .= "s";
    $params[] = $size;
}

// Add the color filter, if any
if ($color) {
    $query .= " AND color = ?";
    $types .= "s";
    $params[] = $color;
}

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}

// Bind parameters dynamically
$stmt->bind_param($types, ...$params);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch the results
if (!$result) {
    die("Error fetching products: " . $conn->error);
}
$products = $result->fetch_all(MYSQLI_ASSOC);

// Handle add to cart functionality
if (isset($_GET['add_to_cart'])) {
    $productId = (int)$_GET['add_to_cart'];

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // Fetch product details from database
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        // Add the product to the cart session
        $_SESSION['cart'][$productId] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1,
            'image' => json_decode($product['images'], true)[0] ?? 'default_image.jpg' // Add image
        ];
    }

    // Redirect back to the product listing page
    header('Location: productlisting.php');
    exit();
}

// Handle remove from cart functionality
if (isset($_GET['remove_from_cart'])) {
    $productId = (int)$_GET['remove_from_cart'];

    // Remove the product from the cart session
    unset($_SESSION['cart'][$productId]);

    // Redirect back to the cart page
    header('Location: productlisting.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  <title>PetsVibe - Product Listing</title>
  <link rel="stylesheet" href="css/product.css">
   <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
</head>
<body>

<header>
  <div class="header-container">
    <div class="logo">
      <a href="index.php">
        <img src="css/images/logo.png" alt="Monito Logo" />
      </a>
    </div>
    <div class="search-bar">
      <form action="productlisting.php" method="get">
        <input type="text" id="search" name="search" placeholder="Search for products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
        <button type="submit">🔎</button>
      </form>
    </div>
    <div class="cart-container" id="open-cart">
      <img src="css/images/cart.png" alt="Cart" class="cart-icon" />
    </div>
  </div>
</header>

<div class="container">

  <!-- Sidebar Section -->
  <aside class="sidebar">
    <h3>Categories</h3>
    <ul>
      <li><a href="productlisting.php">All Products</a></li>
      <?php foreach ($categories as $cat) : ?>
        <li><a href="productlisting.php?category=<?php echo urlencode($cat['category']); ?>" <?php echo ($category === $cat['category']) ? 'class="selected"' : ''; ?>><?php echo htmlspecialchars($cat['category']); ?></a></li>
      <?php endforeach; ?>
    </ul>

    <h3>Filters</h3>
    <form action="productlisting.php" method="get">
      <!-- Price Filter -->
      <div class="filter-section">
        <label for="min-price">Min Price (INR):</label>
        <input type="number" name="min-price" id="min-price" placeholder="Min Price" min="0" value="<?php echo htmlspecialchars($minPrice); ?>">
      </div>
      <div class="filter-section">
        <label for="max-price">Max Price (INR):</label>
        <input type="number" name="max-price" id="max-price" placeholder="Max Price" min="0" value="<?php echo htmlspecialchars($maxPrice); ?>">
      </div>

      <!-- Brand Filter -->
      <div class="filter-section">
        <label for="brand">Brand:</label>
        <select name="brand" id="brand">
          <option value="">All Brands</option>
          <?php foreach ($brands as $b) : ?>
            <option value="<?php echo htmlspecialchars($b['brand']); ?>" <?php echo ($brand === $b['brand']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($b['brand']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Size Filter -->
      <div class="filter-section">
        <label for="size">Size:</label>
        <select name="size" id="size">
          <option value="">All Sizes</option>
          <?php foreach ($sizes as $s) : ?>
            <option value="<?php echo htmlspecialchars($s['size']); ?>" <?php echo ($size === $s['size']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['size']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Color Filter -->
      <div class="filter-section">
        <label for="color">Color:</label>
        <select name="color" id="color">
          <option value="">All Colors</option>
          <?php foreach ($colors as $c) : ?>
            <option value="<?php echo htmlspecialchars($c['color']); ?>" <?php echo ($color === $c['color']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['color']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="submit" class="filter-btn">Apply Filters</button>
    </form>
  </aside>

  <!-- Product Grid Section -->
  <section class="product-list">
    <?php if (!empty($products)) : ?>
      <?php foreach ($products as $product) : ?>
        <?php
        $images = json_decode($product['images'], true);
        $coverImage = $images[0] ?? 'default_image.jpg';
        $description = htmlspecialchars($product['description']);
        $shortDescription = implode(' ', array_slice(explode(' ', $description), 0, 20));
        ?>
        <div class="product-card">
          <img src="<?php echo htmlspecialchars($coverImage); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
          <h3><?php echo htmlspecialchars($product['name']); ?></h3>
          <p class="price">Price: ₹<?php echo number_format($product['price'], 2); ?></p>
          <p>Description: <?php echo $shortDescription; ?>...</p>
          <button class="add-to-cart-btn">
            <a href="productlisting.php?add_to_cart=<?php echo $product['id']; ?>">Add to Cart</a>
          </button>
          <a href="productdetails.php?id=<?php echo $product['id']; ?>" class="view-details-btn">View Details</a>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <p>No products found with the selected filters.</p>
    <?php endif; ?>
  </section>

</div>

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
            <a href="productlisting.php?remove_from_cart=<?php echo $item['id']; ?>" class="remove-btn">Remove</a>
          </div>
        <?php endforeach; ?>
        <div class="cart-total">
          <strong>Total: ₹<?php echo number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart'])), 2); ?></strong>
        </div>
      <?php else: ?>
        <p>Your cart is empty.</p>
      <?php endif; ?>
    </div>
    <a href="cart.php" class="checkout-btn">Proceed to Checkout</a>
  </div>
</div>

<script>
// Open and close the cart sidebar
document.getElementById('open-cart').addEventListener('click', function() {
  document.getElementById('cart-sidebar').style.display = 'block';
});
document.getElementById('close-cart').addEventListener('click', function() {
  document.getElementById('cart-sidebar').style.display = 'none';
});
</script>

</body>
</html>
