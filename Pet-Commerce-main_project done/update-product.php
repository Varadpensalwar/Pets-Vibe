<?php
// Include the database connection
include 'database.php';

// Fetch product ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Product ID is missing.");
}

$id = $_GET['id'];

// Fetch product data from the database
$productQuery = "SELECT * FROM products WHERE id = $id";
$productResult = mysqli_query($conn, $productQuery);
if (!$productResult) {
    die("Error: " . mysqli_error($conn));
}

$product = mysqli_fetch_assoc($productResult);

// Process the form if it's submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form values and escape them to avoid SQL injection
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $size = mysqli_real_escape_string($conn, $_POST['size']); // Sanitize size input

    // Handle image uploads
    $existingImages = json_decode($product['images'], true); // Fetch current images
    $imagePaths = $existingImages ?: [];
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        if ($tmp_name) {
            $imageName = time() . '_' . $_FILES['images']['name'][$key];
            move_uploaded_file($tmp_name, "uploads/" . $imageName);
            $imagePaths[] = "uploads/" . $imageName;
        }
    }

    // Convert image paths to JSON format
    $imagesJson = json_encode($imagePaths);

    // Update the product in the database
    $sql = "UPDATE products SET 
                name='$name', price='$price', category='$category', description='$description', 
                brand='$brand', color='$color', size='$size', images='$imagesJson' 
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo "Product updated successfully!";
        header("Location: product-management.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="css/addproduct.css">
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
        <!-- Logo and Title -->
        <div class="header">
            <img src="css/images/logo.png" alt="Logo">
            <h1>Update Product</h1>
        </div>
        
        <!-- Form -->
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>

            <label for="category">Category:</label>
            <input type="text" name="category" id="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

            <label for="brand">Brand:</label>
            <input type="text" name="brand" id="brand" value="<?php echo htmlspecialchars($product['brand']); ?>" required>

            <label for="color">Color (name):</label>
            <input type="text" name="color" id="color" value="<?php echo htmlspecialchars($product['color']); ?>" required>

            <!-- Product Size Field -->
            <label for="size">Product Size (e.g., 500g, 1L):</label>
            <input type="text" name="size" id="size" value="<?php echo htmlspecialchars($product['size']); ?>" required>

            <label for="images">Upload New Images (optional):</label>
            <input type="file" name="images[]" id="images" multiple>

            <button type="submit">Update Product</button>
        </form>
    </div>
</body>
</html>
