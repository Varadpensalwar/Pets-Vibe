<?php
// Include your database connection file
include 'database.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure all form fields are filled out
    if (isset($_POST['title'], $_POST['price'], $_POST['item'], $_POST['quantity'], $_FILES['image']) && 
        !empty($_POST['title']) && !empty($_POST['price']) && !empty($_POST['item']) && !empty($_POST['quantity']) && !empty($_FILES['image']['name'])) {

        // Collect form data
        $title = $_POST['title'];
        $price = $_POST['price'];
        $item = $_POST['item']; // Replaced gender with item
        $quantity = $_POST['quantity']; // Replaced age with quantity
        
        // Handle image upload
        $imagePath = 'uploads/' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            // Image uploaded successfully
        } else {
            echo "Error uploading the image.";
            exit;
        }

        // Prepare the SQL query to insert pet product data
        $sql = "INSERT INTO pet_products (title, price, item, quantity, image_path) 
                VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters to the query
            $stmt->bind_param("sssss", $title, $price, $item, $quantity, $imagePath);

            // Execute the query
            if ($stmt->execute()) {
                // Redirect to manage_products.php after success
                header("Location: manage_products.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing the statement: " . $conn->error;
        }
    } else {
        echo "Missing form fields. Ensure all fields are filled out.";
    }
}

// Close the database connection
$conn->close();
?>
