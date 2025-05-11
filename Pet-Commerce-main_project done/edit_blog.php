<?php
include 'database.php';  // Database connection

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the existing blog data from the database
    $query = "SELECT * FROM blogs WHERE id = $id";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $blog = $result->fetch_assoc();
    } else {
        echo "Blog not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

// Handle the form submission for editing the blog
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Handle Image Upload (if a new image is uploaded)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);

        // Validate file extension
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($image_ext, $allowed_extensions)) {
            // Generate a unique name for the image
            $image_new_name = time() . '.' . $image_ext;
            $image_upload_path = 'uploads/' . $image_new_name;

            // Move the image to the server directory
            if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
                $image_url = $image_upload_path;
            } else {
                echo "Error uploading the image.";
                exit;
            }
        } else {
            echo "Invalid file type. Please upload a JPG, PNG, or GIF image.";
            exit;
        }
    } else {
        // Keep the current image
        $image_url = $blog['image_url'];
    }

    // Update the blog in the database
    $update_query = "UPDATE blogs SET title = ?, category = ?, description = ?, image_url = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $title, $category, $description, $image_url, $id);

    if ($stmt->execute()) {
        header('Location: manage_blog.php');  // Redirect back to manage blog page
        exit;
    } else {
        echo "Error: " . $conn->error;
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
    <title>Edit Blog</title>
</head>
<body>
    <div class="container">
        <!-- Header with Logo and Title -->
        <div class="header">
            <img src="css/images/logo.png" alt="Logo"> <!-- Replace with your logo path -->
            <h1>Edit Blog</h1>
        </div>

        <!-- HTML Form for Editing Blog -->
        <form action="edit_blog.php?id=<?php echo htmlspecialchars($blog['id']); ?>" method="POST" enctype="multipart/form-data">
            <label for="title">Blog Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" placeholder="Blog Title" required>

            <label for="category">Blog Category</label>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($blog['category']); ?>" placeholder="Blog Category" required>

            <label for="description">Blog Description</label>
            <textarea id="description" name="description" placeholder="Blog Description" required><?php echo htmlspecialchars($blog['description']); ?></textarea>

            <!-- Image Upload Field -->
            <label for="image">Change Image (Optional):</label>
            <input type="file" id="image" name="image" accept="image/*">

            <!-- Display the current image -->
            <p>Current Image:</p>
            <img src="<?php echo htmlspecialchars($blog['image_url']); ?>" alt="Current Blog Image" class="blog-image" width="200">

            <button type="submit">Update Blog</button>
        </form>
    </div>
</body>
</html>
