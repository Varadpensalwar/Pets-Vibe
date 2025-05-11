<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $imagePath = null;

    // Handle image upload if a new image is selected
    if (!empty($_FILES['image']['name'])) {
        $imagePath = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    // Prepare SQL query based on whether an image is being updated or not
    if ($imagePath) {
        $sql = "UPDATE pet_products SET title = ?, price = ?, image_path = ? WHERE id = ?";  // Changed to 'pet_products'
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare failed: ' . $conn->error);  // Debugging: Check if prepare failed
        }
        $stmt->bind_param("sdsi", $title, $price, $imagePath, $id);
    } else {
        $sql = "UPDATE pet_products SET title = ?, price = ? WHERE id = ?";  // Changed to 'pet_products'
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare failed: ' . $conn->error);  // Debugging: Check if prepare failed
        }
        $stmt->bind_param("sdi", $title, $price, $id);
    }

    // Execute the query and check if it's successful
    if ($stmt->execute()) {
        echo "Product updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

header("Location: manage_products.php");
exit();
?>
