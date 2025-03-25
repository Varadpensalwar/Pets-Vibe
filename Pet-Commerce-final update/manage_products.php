<?php
// Include your database connection
include 'database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Featured Pet Products</title>
    <link rel="stylesheet" href="css/control.css.">
</head>
<body>

<header>
    <div class="logo img">
      <a href="index.php">
        <img src="css/images/logo.png" alt="Your Logo Here" />
      </a>
    </div>
<h4 class="title">Featured Products</h4>
  
  </header>

<div class="container">
<?php
// Fetch all pet products
$sql = "SELECT * FROM pet_products";
$result = $conn->query($sql);

// Check if products are available
if ($result->num_rows > 0) {
    // Display all products in a table
    echo '<table>';
    echo '<tr><th>Title</th><th>Price</th><th>Item</th><th>Qty</th><th>Image</th><th>Actions</th></tr>';
    
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['title'] . '</td>';
        echo '<td>₹' . $row['price'] . '</td>';
        echo '<td>' . $row['item'] . '</td>'; // Replaced gender with item
        echo '<td>' . $row['quantity'] . '</td>'; // Replaced age with quantity
        echo '<td><img src="' . $row['image_path'] . '" width="100"></td>';
        echo '<td>';
        echo '<a href="edit_pet_product.php?id=' . $row['id'] . '">Edit</a> | ';
        echo '<a href="delete_pet_product.php?id=' . $row['id'] . '">Delete</a>';
        echo '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
} else {
    echo "No pet products available.";
}

$conn->close();
?>
</div>

<a href="add_product.php" class="button-link">Add New Pet Product</a>

</body>
</html>
