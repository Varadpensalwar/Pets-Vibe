<?php
include 'database.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    
    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);

        // Validate file extension (optional)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($image_ext, $allowed_extensions)) {
            echo "Invalid file type. Please upload a JPG, PNG, or GIF image.";
            exit;
        }

        // Generate a unique name for the image
        $image_new_name = time() . '.' . $image_ext;
        $image_upload_path = 'uploads/' . $image_new_name;

        // Move the image to the server directory
        if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
            // Image uploaded successfully, now insert the blog into the database
            $query = "INSERT INTO blogs (title, category, description, image_url) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $title, $category, $description, $image_upload_path);

            if ($stmt->execute()) {
                echo "Blog added successfully!";
                header('Location: manage_blog.php');  // Redirect to the manage blog page
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error uploading the image.";
        }
    } else {
        echo "No image uploaded.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="css/blogs_dashboard.css">
    <title>Add Blog</title>
</head>
<body>
    <div class="containers">
        <!-- Header with Logo and Title -->
        <div class="header">
            <img src="css/images/logo.png" alt="Logo"> <!-- Replace with your logo path -->
            <h1>New Blog</h1>
        </div>

        <!-- HTML Form for Adding Blog -->
        <form action="add_blog.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Blog Title" required>
            <input type="text" name="category" placeholder="Blog Category" required>
            <textarea name="description" placeholder="Blog Description" required></textarea>
            
            <!-- Image Upload Field -->
            <input type="file" name="image" accept="image/*" required>
            
            <!-- Submit Button -->
            <button type="submit">Add Blog</button>
        </form>
    </div>
</body>
</html>
