<?php
include 'database.php'; // Ensure database connection is included

// Handle form submission for adding a new product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (float)$_POST['price'];
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $product_size = mysqli_real_escape_string($conn, $_POST['size']); // Product size can be anything
    $color = mysqli_real_escape_string($conn, $_POST['color']); // Color provided as text
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Process uploaded images
    $imageDir = 'uploads/';
    $uploadedImages = [];

    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
        $imagePath = $imageDir . basename($_FILES['images']['name'][$key]);
        if (move_uploaded_file($tmpName, $imagePath)) {
            $uploadedImages[] = $imagePath; // Store full path of uploaded images
        }
    }

    // Check if images were uploaded before proceeding
    if (empty($uploadedImages)) {
        echo "Error: No images uploaded.";
        exit;
    }

    // Encode the array of image paths as JSON
    $images = json_encode($uploadedImages);

    // Insert product into database
    $query = "INSERT INTO products (name, price, category, brand, size, color, description, images) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sdssssss', $name, $price, $category, $brand, $product_size, $color, $description, $images);

    if ($stmt->execute()) {
        // Product added successfully
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Product</title>
  <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="css/addproduct.css"> <!-- Link to your external stylesheet -->
</head>

<body>
  <div class="container">
    <div class="header">
        <img src="css/images/logo.png" alt="Logo">
        <h1>Add New Product</h1>
    </div>
    
    <!-- Form now allows any size input with text -->
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" required>
      </div>
      
      <div class="form-group">
        <label for="price">Price (INR):</label>
        <input type="number" name="price" id="price" required>
      </div>

      <div class="form-group">
        <label for="category">Category:</label>
        <input type="text" name="category" id="category" required>
      </div>

      <div class="form-group">
        <label for="brand">Brand:</label>
        <input type="text" name="brand" id="brand" required>
      </div>

      <div class="form-group">
        <label for="size">Product Size:</label>
        <input type="text" name="size" id="size" required>
      </div>

      <div class="form-group">
        <label for="color">Color (name):</label>
        <input type="text" name="color" id="color" required>
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>
      </div>

      <div class="form-group">
        <label for="images">Product Images:</label>
        <input type="file" name="images[]" id="images" multiple required>
      </div>

      <button type="submit" class="submit-btn">Add Product</button>
    </form>
  </div>
</body>

</html>
