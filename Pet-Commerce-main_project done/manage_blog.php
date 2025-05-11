<?php
include 'database.php';  // Database connection

// Fetch all blogs from the database
$query = "SELECT * FROM blogs ORDER BY created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="css/manage_blogs.css.">
    <title>Manage Blogs</title>
</head>
<body>
    <header class="header">
        <img src="css/images/logo.png" alt="Logo">
        <h1>Manage Blogs</h1>
    </header>

    <div class="blogs-container">
        <?php
        // Check if there are any blogs
        if ($result->num_rows > 0) {
            // Loop through each blog and display it
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="blog-card">
                    <img src="' . htmlspecialchars($row['image_url']) . '" alt="Blog Image" class="blog-image">
                    <div class="blog-content">
                        <h3 class="blog-title">' . htmlspecialchars($row['title']) . '</h3>
                        <p class="blog-category">' . htmlspecialchars($row['category']) . '</p>
                        <p class="blog-description">' . htmlspecialchars(substr($row['description'], 0, 100)) . '...</p>
                        <a href="edit_blog.php?id=' . $row['id'] . '" class="edit-btn">Edit</a>
                        <a href="delete_blog.php?id=' . $row['id'] . '" class="delete-btn" onclick="return confirm(\'Are you sure you want to delete this blog?\');">Delete</a>
                    </div>
                </div>';
            }
        } else {
            echo '<p>No blogs available.</p>';
        }
        ?>
    </div>
    <div class="add-pet-container">
    <a href="add_blog.php" class="add-pet-btn">Add New Blogs</a>
</div>

</body>
</html>
