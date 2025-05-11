<?php
include 'database.php'; // Ensure database connection is included

// Handle deletion request through form submission
if (isset($_POST['delete'])) {
    $id = $_POST['pet_id'];

    // Sanitize the id to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $id);

    // Delete the pet record from the database
    $deleteQuery = "DELETE FROM pet_management WHERE id = $id";
    
    if ($conn->query($deleteQuery)) {
        echo "Pet deleted successfully!";
        // Refresh the page after deletion
        header("Location: pet_management.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch all pets
$query = "SELECT * FROM pet_management ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Pets</title>
  <link rel="stylesheet" href="css/management.css"> <!-- Link to your external stylesheet -->
  <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
</head>

<body>
<header>
    <div class="logo img">
      <a href="index.php">
        <img src="css/images/logo.png" alt="Your Logo Here" />
      </a>
    </div>
<h4 class="title">Manage Pets</h4>
  
  </header>
  <div class="container">
 
    <table border="1">
      <thead>
        <tr>
          <th>Name</th>
          <th>Species</th>
          <th>Breed</th>
          <th>Price (INR)</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($pet = $result->fetch_assoc()) : 
            // Decode the JSON string of images
            $images = json_decode($pet['images'], true);
            // Get the first image if available
            $firstImage = isset($images[0]) ? $images[0] : ''; 
        ?>
          <tr>
            <td><?= htmlspecialchars($pet['name']); ?></td>
            <td><?= htmlspecialchars($pet['species']); ?></td>
            <td><?= htmlspecialchars($pet['breed']); ?></td>
            <td><?= htmlspecialchars($pet['price']); ?> INR</td>
            <td>
              <?php if ($firstImage) { ?>
                <img src="<?= $firstImage; ?>" alt="Pet Image" width="100">
              <?php } else { ?>
                No Image
              <?php } ?>
            </td>
            <td>
              <a href="pet_update.php?id=<?= $pet['id']; ?>">Edit</a> |
              <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this pet?')">
                <input type="hidden" name="pet_id" value="<?= $pet['id']; ?>">
                <button type="submit" name="delete" class="delete">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <div class="add-btn-container">
    <a href="add_newpet.php" class="btn-add">Add New Pet</a>
</div>

  </div>
</body>

</html>
