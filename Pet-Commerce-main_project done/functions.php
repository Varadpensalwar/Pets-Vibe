<?php
// Include the database connection
include 'database.php';

// Function to fetch products by category
function fetchProductsByCategory($category) {
    global $conn;

    // SQL query to get products based on the category
    $stmt = $conn->prepare("SELECT id, name, price, images FROM products WHERE category = ?");
    $stmt->bind_param("s", $category); // Bind the category dynamically
    $stmt->execute(); // Execute the query

    // Fetch the result
    $result = $stmt->get_result();
    $products = [];

    // Collect products into an array
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $stmt->close(); // Close the statement
    return $products; // Return the products array
}
?>
