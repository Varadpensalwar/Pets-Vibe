<?php
include 'database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM pet_products WHERE id = ?";  // Changed 'products' to 'pet_products'
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('MySQL prepare failed: ' . $conn->error);  // Debugging: Check if prepare statement fails
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
} else {
    header("Location: manage_products.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/control.css">
    <title>Edit Product</title>
</head>
<body>
<h2>Edit Product</h2>

<form action="update_product.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

    <label for="title">Product Title:</label>
    <input type="text" id="title" name="title" value="<?php echo $product['title']; ?>" required><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" step="0.01" required><br><br>

    <label for="image">Product Image:</label>
    <input type="file" id="image" name="image" accept="image/*"><br><br>

    <input type="submit" value="Update Product">
</form>

<a href="manage_products.php">Manage Products</a>

</body>
</html>
