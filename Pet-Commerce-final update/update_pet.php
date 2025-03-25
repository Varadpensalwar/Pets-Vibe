<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $price = $_POST['price'];

    // Handle image upload
    $image_path = $_FILES['image']['name'] ? "uploads/" . basename($_FILES["image"]["name"]) : null;

    // If a new image is uploaded, move it to the target directory
    if ($image_path) {
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
        $sql = "UPDATE pets SET title = ?, gender = ?, age = ?, price = ?, image_path = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdsi", $title, $gender, $age, $price, $image_path, $id);
    } else {
        $sql = "UPDATE pets SET title = ?, gender = ?, age = ?, price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsd", $title, $gender, $age, $price, $id);
    }

    if ($stmt->execute()) {
        echo "Pet updated successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

header("Location: manage_pets.php");
exit;
?>
