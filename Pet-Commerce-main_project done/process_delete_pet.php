<?php
include 'database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare a delete statement
    $sql = "DELETE FROM pets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to manage_pets.php after successful deletion
        header("Location: manage_pets.php");
        exit();
    } else {
        // Echo only in case of an error
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No pet ID provided!";
    echo "<a href='manage_pets.php'>Go Back to Manage Pets</a>";
}
?>
