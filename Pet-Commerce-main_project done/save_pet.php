<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $price = $_POST['price'];
    $imagePath = 'css/images/' . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        $query = "INSERT INTO pets (title, gender, age, price, image_path) VALUES ('$title', '$gender', '$age', '$price', '$imagePath')";
        if ($conn->query($query) === TRUE) {
            // Redirect to manage_pets.php on successful addition
            header("Location: manage_pets.php");
            exit();
        } else {
            // Echo only in case of a query error
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    } else {
        // Echo only if the image upload fails
        echo "Failed to upload image.";
    }
}

$conn->close();
?>
