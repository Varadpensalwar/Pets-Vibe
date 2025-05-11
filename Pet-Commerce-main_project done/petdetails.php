<?php
session_start(); // Ensure this is at the very top of the file, only once
include 'database.php'; // Include the file containing your database functions

// Fetch pet details
$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Validate ID input
$petQuery = "SELECT * FROM pet_management WHERE id = ?";
$stmt = $conn->prepare($petQuery);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$pet = $result->fetch_assoc();

if (!$pet) {
    die("<h1>Pet Not Found!</h1>"); // Display error if pet is missing
}

// Decode images from JSON (all images)
$images = json_decode($pet['images'], true); // Decoding JSON string to PHP array

// Handle add to cart functionality
if (isset($_GET['add_to_cart'])) {
    $petId = (int)$_GET['add_to_cart'];

    // Check if the pet is already in the cart
    if (isset($_SESSION['cart'][$petId])) {
        // Increment the quantity if the pet is already in the cart
        $_SESSION['cart'][$petId]['quantity']++;
    } else {
        // Add the pet to the cart session
        $_SESSION['cart'][$petId] = [
            'id' => $pet['id'],
            'name' => $pet['name'],
            'price' => $pet['price'],
            'quantity' => 1,
            'image' => $images[0] ?? 'default_image.jpg' // Use the first image if available
        ];
    }

    // Redirect back to the pet details page to show updated cart
    header('Location: petdetails.php?id=' . $pet['id']);
    exit();
}

// Handle removing a pet from the cart
if (isset($_GET['remove_from_cart'])) {
    $petIdToRemove = (int)$_GET['remove_from_cart'];

    // Remove pet from the session cart
    if (isset($_SESSION['cart'][$petIdToRemove])) {
        unset($_SESSION['cart'][$petIdToRemove]);
    }

    // Redirect back to the pet details page or cart page
    header('Location: petdetails.php?id=' . $pet['id']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  <title>Pets Vibe - Pet Adoption</title>
  <link rel="stylesheet" href="css/petdetails.css">
  <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
</head>
<body>

  <!-- Header with Logo and Cart Image -->
  <header>
    <div class="logo img">
      <a href="index.php">
        <img src="css/images/logo.png" alt="Your Logo Here" />
      </a>
    </div>
    <h4>PETS-VIBE</h4>
    <div id="cart-btn">
      <img src="css/images/cart2.png" alt="Cart" />
</div>
  </header>

  <!-- Pet Details Section -->
  <div class="pet-details">
    <div class="carousel">
      <!-- Main Image (Larger Image) -->
      <?php if (!empty($images)): ?>
        <img id="main-image" src="<?php echo htmlspecialchars($images[0]); ?>" alt="Main Pet Image" class="main-image">
        <div class="thumbnails">
          <?php foreach ($images as $index => $img): ?>
            <img src="<?php echo htmlspecialchars($img); ?>" alt="Pet Image" class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeImage(this)">
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>No images available.</p>
      <?php endif; ?>
    </div>

    <!-- Pet Info Section -->
    <div class="pet-info">
      <h2><?php echo htmlspecialchars($pet['name']); ?></h2>
      <p class="price">Adoption Fee: ₹<?php echo number_format($pet['price']); ?></p>
      <table class="pet-table">
        <tr><td>Species✨:</td><td><?php echo htmlspecialchars($pet['species']); ?></td></tr>
        <tr><td>Breed🐕:</td><td><?php echo htmlspecialchars($pet['breed']); ?></td></tr>
        <tr><td>Age🐶:</td><td><?php echo htmlspecialchars($pet['age']); ?> years</td></tr>
        <tr><td>Gender🚹:</td><td><?php echo htmlspecialchars($pet['gender']); ?></td></tr>
        <tr><td>Weight⚖️:</td><td><?php echo htmlspecialchars($pet['weight']); ?> kg</td></tr>
        <tr><td>Color🔴:</td><td><?php echo htmlspecialchars($pet['color']); ?></td></tr>
        <tr><td>Description:</td><td><?php echo htmlspecialchars($pet['description']); ?></td></tr>
      </table>
      <!-- Adopt Now Button -->
      <button id="adopt-now" class="btn"><a href="petdetails.php?id=<?php echo $pet['id']; ?>&add_to_cart=<?php echo $pet['id']; ?>">Adopt Now</a></button>
    </div>
  </div>

  <!-- Suggested Pets Section -->
  <section class="suggested-pets">
    <h3>See More Pets</h3>
    <div class="pet-list">
      <?php
      // Fetch related pets based on category or other criteria
      $relatedPetsQuery = "SELECT * FROM pet_management WHERE category = ? LIMIT 4"; 
      $stmt = $conn->prepare($relatedPetsQuery);
      $stmt->bind_param("s", $pet['category']);
      $stmt->execute();
      $relatedResult = $stmt->get_result();

      if ($relatedResult->num_rows > 0):
        while ($relatedPet = $relatedResult->fetch_assoc()): ?>
          <div class="pet-card">
            <img src="<?php echo htmlspecialchars(json_decode($relatedPet['images'], true)[0]); ?>" alt="<?php echo htmlspecialchars($relatedPet['name']); ?>">
            <h4><?php echo htmlspecialchars($relatedPet['name']); ?></h4>
            <p>PRICE: ₹<?php echo number_format($relatedPet['price']); ?> INR</p>
            <p>Description: <?php echo htmlspecialchars(implode(' ', array_slice(explode(' ', $relatedPet['description']), 0, 20))); ?>...</p>
            <button class="add-to-cart-btn"><a href="petdetails.php?id=<?php echo $relatedPet['id']; ?>&add_to_cart=<?php echo $relatedPet['id']; ?>">BUY NOW</a></button>
            <a href="petdetails.php?id=<?php echo $relatedPet['id']; ?>" class="view-details-btn">View Details</a>   
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No related pets available.</p>
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
              <a href="petdetails.php?id=<?php echo $item['id']; ?>&remove_from_cart=<?php echo $item['id']; ?>" class="remove-btn">Remove</a>
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
    // Change main image when thumbnail is clicked
    function changeImage(thumbnail) {
      // Get the source of the clicked thumbnail
      var newImage = thumbnail.src;

      // Update the main image source
      document.getElementById("main-image").src = newImage;

      // Remove 'active' class from all thumbnails
      var thumbnails = document.querySelectorAll(".thumbnail");
      thumbnails.forEach(function (img) {
        img.classList.remove("active");
      });

      // Add 'active' class to the clicked thumbnail
      thumbnail.classList.add("active");
    }

    document.addEventListener("DOMContentLoaded", function () {
      const cartIcon = document.getElementById("cart-btn");
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
