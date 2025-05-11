<?php
// Include the database connection
include 'database.php';

// Fetch pet ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Pet ID is missing.");
}

$id = $_GET['id'];

// Fetch pet data from the database
$query = "SELECT * FROM pet_management WHERE id = $id";
$result = $conn->query($query);
if ($result->num_rows === 0) {
    die("Error: No pet found with this ID.");
}

$pet = $result->fetch_assoc();

// Process the form if it's submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form values and escape them to avoid SQL injection
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $species = mysqli_real_escape_string($conn, $_POST['species']);
    $breed = mysqli_real_escape_string($conn, $_POST['breed']);
    $age = $_POST['age'];
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $weight = $_POST['weight'];
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $category = mysqli_real_escape_string($conn, $_POST['category']); // Get the category input
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];

    // Handle image uploads
    $existingImages = json_decode($pet['images'], true); // Fetch current images
    $imagePaths = $existingImages ?: [];
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        if ($tmp_name) {
            $imageName = time() . '_' . $_FILES['images']['name'][$key];
            move_uploaded_file($tmp_name, "uploads/" . $imageName);
            $imagePaths[] = "uploads/" . $imageName;
        }
    }

    // Convert image paths to JSON format
    $imagesJson = json_encode($imagePaths);

    // Update the pet in the database
    $sql = "UPDATE pet_management SET 
                name='$name', species='$species', breed='$breed', age='$age', gender='$gender', 
                weight='$weight', color='$color', category='$category', description='$description', price='$price', 
                images='$imagesJson' 
            WHERE id = $id";

    if ($conn->query($sql)) {
        echo "Pet updated successfully!";
        header("Location: pet_management.php"); // Redirect after successful update
        exit();
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
    <title>Update Pet</title>
    <link rel="stylesheet" href="css/addproduct.css">
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <img src="css/images/logo.png" alt="Logo">
            <h1>Update Pet Details</h1>
        </div>
        
        <!-- Form to Update Pet -->
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Pet Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($pet['name']); ?>" required>

            <label for="species">Species:</label>
            <input type="text" name="species" id="species" value="<?php echo htmlspecialchars($pet['species']); ?>" required>

            <label for="breed">Breed:</label>
            <input type="text" name="breed" id="breed" value="<?php echo htmlspecialchars($pet['breed']); ?>" required>

            <label for="age">Age (Years):</label>
            <input type="number" name="age" id="age" value="<?php echo htmlspecialchars($pet['age']); ?>" required>

            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="Male" <?php echo ($pet['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($pet['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
            </select>

            <label for="weight">Weight (Kg):</label>
            <input type="number" name="weight" id="weight" step="0.1" value="<?php echo htmlspecialchars($pet['weight']); ?>" required>

            <label for="color">Color (name):</label>
            <input type="text" name="color" id="color" value="<?php echo htmlspecialchars($pet['color']); ?>" required>

            <label for="category">Category:</label>
            <input type="text" name="category" id="category" value="<?php echo htmlspecialchars($pet['category']); ?>" required> <!-- Add this line for category -->

            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?php echo htmlspecialchars($pet['description']); ?></textarea>

            <label for="price">Price (INR):</label>
            <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($pet['price']); ?>" required>

            <label for="images">Upload New Images (optional):</label>
            <input type="file" name="images[]" id="images" multiple>

            <button type="submit">Update Pet</button>
        </form>
    </div>
</body>
</html>
