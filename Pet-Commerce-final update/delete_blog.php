<?php
include 'database.php'; // Database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];  // Get the blog ID from the URL

    // Delete the blog from the database
    $query = "DELETE FROM blogs WHERE id = $id";
    if ($conn->query($query) === TRUE) {
        echo "Blog deleted successfully!";
        header('Location: manage_blog.php');  // Redirect back to manage blogs page
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Blog ID is missing.";
}
?>
