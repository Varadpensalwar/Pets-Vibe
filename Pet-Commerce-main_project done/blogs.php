<?php
include 'database.php'; // Database connection

// Fetch all blogs from the database
$query = "SELECT * FROM blogs ORDER BY created_at DESC";
$result = $conn->query($query);

// Fetch distinct categories for the sidebar
$categoryQuery = "SELECT DISTINCT category FROM blogs";
$categoryResult = $conn->query($categoryQuery);

// Check if there are any blogs
if ($result->num_rows > 0):
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/blogs.css.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
    <title>All Blogs</title>
</head>
<body>

    <!-- Header Section -->
    <header>
    <div class="logo img">
      <a href="index.php">
        <img src="css/images/logo.png" alt="Your Logo Here" />
      </a>
    </div>
<h4 class="title">Our-Blogs</h4>
  
  </header>

    <div class="container">
        <!-- Sidebar Section -->
        <aside class="sidebar">
            <h2>Categories</h2>
            <ul>
                <?php
                // Check if categories exist and loop through them
                if ($categoryResult->num_rows > 0) {
                    while ($category = $categoryResult->fetch_assoc()) {
                        echo '<li><a href="?category=' . urlencode($category['category']) . '">' . $category['category'] . '</a></li>';
                    }
                } else {
                    echo '<li>No categories available</li>';
                }
                ?>
            </ul>
        </aside>

        <!-- Blogs Listing Section -->
        <section class="blogs-container">
            <?php
            // If a category is selected, modify the query
            if (isset($_GET['category'])) {
                $category = $_GET['category'];
                $query = "SELECT * FROM blogs WHERE category = '$category' ORDER BY created_at DESC";
                $result = $conn->query($query);
            }

            // Loop through each blog and display it
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
                <div class="blog-card">
                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['title']; ?>" class="blog-image">
                    <div class="blog-content">
                        <h3 class="blog-title"><?php echo $row['title']; ?></h3>
                        <p class="blog-category"><?php echo $row['category']; ?></p>
                        <p class="blog-description"><?php echo substr($row['description'], 0, 100) . '...'; ?></p>
                        <a href="view_blog.php?id=<?php echo $row['id']; ?>" class="read-more-btn">Read More</a>
                    </div>
                </div>
            <?php
                endwhile;
            else:
                echo "<p>No blogs available.</p>";
            endif;
            ?>
        </section>
    </div>
</body>
</html>

<?php
else:
    echo "<p>No blogs available.</p>";
endif;
?>
