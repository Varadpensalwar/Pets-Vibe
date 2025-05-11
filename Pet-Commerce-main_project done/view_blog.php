<?php
include 'database.php'; // Database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];  // Get the blog ID from URL

    // Fetch the full blog post based on the ID
    $query = "SELECT * FROM blogs WHERE id = $id";
    $result = $conn->query($query);

    // Check if the blog exists
    if ($result->num_rows > 0) {
        $blog = $result->fetch_assoc();
    } else {
        echo "<p>Blog not found.</p>";
    }
} else {
    echo "<p>Invalid blog ID.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
 <link rel="stylesheet" href="css/single_blogs.css.">
 <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['title']); ?></title>
    
    <script>
        // JavaScript to handle side panel toggle
        function toggleSidePanel() {
            document.querySelector('.custom-side-panel').classList.toggle('open');
            document.querySelector('.main-content').classList.toggle('shifted');
        }
    </script>
</head>
<body>
    <!-- Side Panel -->
    <div class="custom-side-panel">
        <h2 class="side-panel-title">Blog Navigation</h2>
        <ul class="side-panel-links">
            <li><a href="blogs.php" class="side-panel-link">Recent Posts</a></li>
            <li><a href="blogs.php" class="side-panel-link">Categories</a></li>
        </ul>
    </div>

    <!-- Side Panel Toggle Button -->
    <div class="custom-side-panel-toggle" onclick="toggleSidePanel()">&#9776;</div>

    <!-- Header Section -->
    <header class="custom-page-header">
    <img src="css/images/logo.png" alt="Logo">
        <h1 class="header-title">Blog Details</h1>
    </header>

    <!-- Blog Content Section -->
    <section class="custom-blog-details main-content">
        <div class="custom-blog-detail-content">
            <h1 class="custom-blog-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
            <img src="<?php echo htmlspecialchars($blog['image_url']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="custom-blog-image">
            <p class="custom-blog-category"><?php echo htmlspecialchars($blog['category']); ?></p>
            <p class="custom-blog-description"><?php echo nl2br(htmlspecialchars($blog['description'])); ?></p>
        </div>
    </section>

</body>
</html>
