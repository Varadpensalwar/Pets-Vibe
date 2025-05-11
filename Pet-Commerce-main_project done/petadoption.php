<?php
session_start(); // Ensure this is at the very top of the file, only once
include 'database.php'; // Ensure database connection is included

// Default values for the filters
$minPrice = isset($_GET['min-price']) ? (float)$_GET['min-price'] : 0;
$maxPrice = isset($_GET['max-price']) ? (float)$_GET['max-price'] : 10000;
$search = isset($_GET['search']) ? '%' . mysqli_real_escape_string($conn, $_GET['search']) . '%' : null;
$breed = isset($_GET['breed']) ? mysqli_real_escape_string($conn, $_GET['breed']) : null;
$species = isset($_GET['species']) ? mysqli_real_escape_string($conn, $_GET['species']) : null;
$gender = isset($_GET['gender']) ? mysqli_real_escape_string($conn, $_GET['gender']) : null;
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : null;

// Fetch categories dynamically
$categoryQuery = "SELECT DISTINCT category FROM pet_management";
$categoryResult = mysqli_query($conn, $categoryQuery);
if (!$categoryResult) {
    die("Error fetching categories: " . mysqli_error($conn));
}
$categories = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);

// Fetch breeds dynamically
$breedQuery = "SELECT DISTINCT breed FROM pet_management";
$breedResult = mysqli_query($conn, $breedQuery);
if (!$breedResult) {
    die("Error fetching breeds: " . mysqli_error($conn));
}
$breeds = mysqli_fetch_all($breedResult, MYSQLI_ASSOC);

// Fetch species dynamically
$speciesQuery = "SELECT DISTINCT species FROM pet_management";
$speciesResult = mysqli_query($conn, $speciesQuery);
if (!$speciesResult) {
    die("Error fetching species: " . mysqli_error($conn));
}
$speciesList = mysqli_fetch_all($speciesResult, MYSQLI_ASSOC);

// Fetch genders dynamically
$genderQuery = "SELECT DISTINCT gender FROM pet_management";
$genderResult = mysqli_query($conn, $genderQuery);
if (!$genderResult) {
    die("Error fetching genders: " . mysqli_error($conn));
}
$genders = mysqli_fetch_all($genderResult, MYSQLI_ASSOC);

// Build the SQL query dynamically based on filters
$query = "SELECT * FROM pet_management WHERE price BETWEEN ? AND ?";
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

// Add the breed filter, if any
if ($breed) {
    $query .= " AND breed = ?";
    $types .= "s";
    $params[] = $breed;
}

// Add the species filter, if any
if ($species) {
    $query .= " AND species = ?";
    $types .= "s";
    $params[] = $species;
}

// Add the gender filter, if any
if ($gender) {
    $query .= " AND gender = ?";
    $types .= "s";
    $params[] = $gender;
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
    die("Error fetching pets: " . $conn->error);
}
$pets = $result->fetch_all(MYSQLI_ASSOC);

// Handle add to cart functionality (same as before)
if (isset($_GET['add_to_cart'])) {
    $petId = (int)$_GET['add_to_cart'];

    // Check if the pet is already in the cart
    if (isset($_SESSION['cart'][$petId])) {
        $_SESSION['cart'][$petId]['quantity']++;
    } else {
        // Fetch pet details from database
        $query = "SELECT * FROM pet_management WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $petId);
        $stmt->execute();
        $result = $stmt->get_result();
        $pet = $result->fetch_assoc();

        // Add the pet to the cart session
        $_SESSION['cart'][$petId] = [
            'id' => $pet['id'],
            'name' => $pet['name'],
            'price' => $pet['price'],
            'quantity' => 1,
            'image' => json_decode($pet['images'], true)[0] ?? 'default_image.jpg' // Add image
        ];
    }

    // Redirect back to the pet adoption page
    header('Location: petadoption.php');
    exit();
}

// Handle remove from cart functionality (same as before)
if (isset($_GET['remove_from_cart'])) {
    $petId = (int)$_GET['remove_from_cart'];

    // Remove the pet from the cart session
    unset($_SESSION['cart'][$petId]);

    // Redirect back to the cart page
    header('Location: petadoption.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  <title>Pet Adoption - Pet Listing</title>
  <link rel="stylesheet" href="css/petadoption.css">
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
      <form action="petadoption.php" method="get">
        <input type="text" id="search" name="search" placeholder="Search for pets..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
        <button type="submit">🔎</button>
      </form>
    </div>
    <div class="cart-container" id="open-cart">
      <img src="css/images/cart2.png" alt="Cart" class="cart-icon" />
    </div>
  </div>
</header>

<div class="container">

  <!-- Sidebar Section -->
  <aside class="sidebar">
    <h3>Categories</h3>
    <ul>
      <li><a href="petadoption.php">All Pets</a></li>
      <?php foreach ($categories as $cat) : ?>
        <li><a href="petadoption.php?category=<?php echo urlencode($cat['category']); ?>" <?php echo ($category === $cat['category']) ? 'class="selected"' : ''; ?>><?php echo htmlspecialchars($cat['category']); ?></a></li>
      <?php endforeach; ?>
    </ul>

    <h3>Filters</h3>
    <form action="petadoption.php" method="get">
      <!-- Price Filter -->
      <div class="filter-section">
        <label for="min-price">Min Price (INR):</label>
        <input type="number" name="min-price" id="min-price" placeholder="Min Price" min="0" value="<?php echo htmlspecialchars($minPrice); ?>">
      </div>
      <div class="filter-section">
        <label for="max-price">Max Price (INR):</label>
        <input type="number" name="max-price" id="max-price" placeholder="Max Price" min="0" value="<?php echo htmlspecialchars($maxPrice); ?>">
      </div>

      <!-- Breed Filter -->
      <div class="filter-section">
        <label for="breed">Breed:</label>
        <select name="breed" id="breed">
          <option value="">All Breeds</option>
          <?php foreach ($breeds as $b) : ?>
            <option value="<?php echo htmlspecialchars($b['breed']); ?>" <?php echo ($breed === $b['breed']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($b['breed']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Species Filter -->
      <div class="filter-section">
        <label for="species">Species:</label>
        <select name="species" id="species">
          <option value="">All Species</option>
          <?php foreach ($speciesList as $s) : ?>
            <option value="<?php echo htmlspecialchars($s['species']); ?>" <?php echo ($species === $s['species']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['species']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Gender Filter -->
      <div class="filter-section">
        <label for="gender">Gender:</label>
        <select name="gender" id="gender">
          <option value="">All Genders</option>
          <?php foreach ($genders as $g) : ?>
            <option value="<?php echo htmlspecialchars($g['gender']); ?>" <?php echo ($gender === $g['gender']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($g['gender']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="submit" class="filter-btn">Apply Filters</button>
    </form>
  </aside>

  <!-- Pet Grid Section -->
  <section class="product-list">
    <?php if (!empty($pets)) : ?>
      <?php foreach ($pets as $pet) : ?>
        <?php
        $images = json_decode($pet['images'], true);
        $coverImage = $images[0] ?? 'default_image.jpg';
        $description = htmlspecialchars($pet['description']);
        $shortDescription = implode(' ', array_slice(explode(' ', $description), 0, 20));
        ?>
        <div class="product-card">
          <img src="<?php echo htmlspecialchars($coverImage); ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>" />
          <h3><?php echo htmlspecialchars($pet['name']); ?></h3>
          <p class="price">Price: ₹<?php echo number_format($pet['price'], 2); ?></p>
          <p>Description: <?php echo $shortDescription; ?>...</p>
          <button class="add-to-cart-btn">
            <a href="petadoption.php?add_to_cart=<?php echo $pet['id']; ?>">Add to Cart</a>
          </button>
          <a href="petdetails.php?id=<?php echo $pet['id']; ?>" class="view-details-btn">View Details</a>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <p>No pets found with the selected filters.</p>
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
            <a href="petadoption.php?remove_from_cart=<?php echo $item['id']; ?>" class="remove-btn">Remove</a>
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

<script >
    document.addEventListener("DOMContentLoaded", function () {
    const cartIcon = document.getElementById("open-cart");
    const cartSidebar = document.getElementById("cart-sidebar");
    const closeCartButton = document.getElementById("close-cart");

    // Show the cart sidebar
    cartIcon.addEventListener("click", function () {
        cartSidebar.style.display = "block";
    });

    // Close the cart sidebar
    closeCartButton.addEventListener("click", function () {
        cartSidebar.style.display = "none";
    });
});

</script>
</body>
</html>
