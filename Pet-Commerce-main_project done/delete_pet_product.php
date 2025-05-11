<?php
include 'database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Update table name to 'pet_products'
    $sql = "DELETE FROM pet_products WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Check if the prepare() function failed
    if ($stmt === false) {
        die('MySQL prepare failed: ' . $conn->error);  // Debugging: Display error if prepare fails
    }

    // Bind the ID parameter to the statement
    $stmt->bind_param("i", $id);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "Product deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}

header("Location: manage_products.php");
exit();
?>
