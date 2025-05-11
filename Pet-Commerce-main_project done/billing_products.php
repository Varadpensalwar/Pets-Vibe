<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Page</title>
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to your CSS file -->
</head>
<body>

<h2>Billing Information</h2>

<?php
include 'database.php';

// Check if product_id is set in the URL
if (isset($_GET['product_id'])) {
    // Handle Product Billing
    $product_id = intval($_GET['product_id']);
    
    // Fetch product details from the database
    $query = "SELECT * FROM pet_products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get the product data
        $product = $result->fetch_assoc();
        
        // Display product details
        echo "<div class='billing_details'>";
        echo "<img src='" . htmlspecialchars($product['image_path']) . "' alt='Product Image' width='200'>";
        echo "<h3>Product Name: " . htmlspecialchars($product['title']) . "</h3>";
        echo "<p>Item: " . htmlspecialchars($product['item']) . "</p>"; // Replaced 'gender' with 'item'
        echo "<p>Quantity: " . htmlspecialchars($product['quantity']) . "</p>"; // Replaced 'age' with 'quantity'
        echo "<p>Price: &#8377; " . number_format($product['price'], 2) . "</p>";
        echo "</div>";
        
        // Provide a form for payment or confirmation
        echo "<form action='process_payment.php' method='POST'>";
        echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($product['id']) . "'>";
        echo "<input type='hidden' name='price' value='" . htmlspecialchars($product['price']) . "'>";
        echo "<button type='submit' class='btn btn_bg'>Proceed to Payment</button>";
        echo "</form>";
    } else {
        echo "<p>No product found with the provided ID.</p>";
    }

    $stmt->close();
    
} else {
    echo "<p>Invalid request. Please go back to manage products.</p>";
}

$conn->close();
?>

</body>
</html>
