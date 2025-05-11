<?php
include 'database.php'; // Ensure database connection is included

// Handle form submission for adding a new pet
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $species = mysqli_real_escape_string($conn, $_POST['species']);
    $breed = mysqli_real_escape_string($conn, $_POST['breed']);
    $age = (int)$_POST['age'];
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $weight = (float)$_POST['weight'];
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $category = mysqli_real_escape_string($conn, $_POST['category']); // Get the category input
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float)$_POST['price'];

    // Handle new images if uploaded
    $uploadedImages = [];
    if (!empty($_FILES['images']['name'][0])) {
        $imageDir = 'uploads/';
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $imagePath = $imageDir . basename($_FILES['images']['name'][$key]);
            if (move_uploaded_file($tmpName, $imagePath)) {
                $uploadedImages[] = $imagePath;
            }
        }
    }

    // Insert the new pet into the database
    $images = !empty($uploadedImages) ? json_encode($uploadedImages) : null;
    $insertQuery = "INSERT INTO pet_management (name, species, breed, age, gender, weight, color, category, description, images, price) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($insertQuery);

    // Correct the bind_param based on the number of parameters
    $stmt->bind_param('sssissssssd', $name, $species, $breed, $age, $gender, $weight, $color, $category, $description, $images, $price);

    if ($stmt->execute()) {
//product added sucessfully
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
  <title>Add New Pet</title>
  
  <link rel="stylesheet" href="css/addproduct.css"> <!-- Link to your external stylesheet -->
</head>

<body>
  <div class="container">
    <div class="header">
        <img src="css/images/logo.png" alt="Logo">
        <h1>Add New Pet</h1>
    </div>
    
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="name">Pet Name:</label>
        <input type="text" name="name" id="name" required>
      </div>
      
      <div class="form-group">
        <label for="species">Species:</label>
        <input type="text" name="species" id="species" required>
      </div>

      <div class="form-group">
        <label for="breed">Breed:</label>
        <input type="text" name="breed" id="breed" required>
      </div>

      <div class="form-group">
        <label for="age">Age (Years):</label>
        <input type="number" name="age" id="age" required>
      </div>

      <div class="form-group">
        <label for="gender">Gender:</label>
        <select name="gender" id="gender" required>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>

      <div class="form-group">
        <label for="weight">Weight (Kg):</label>
        <input type="number" step="0.1" name="weight" id="weight" required>
      </div>

      <div class="form-group">
        <label for="color">Color:</label>
        <input type="text" name="color" id="color" required>
      </div>

      <!-- Category Field as Manual Input -->
      <div class="form-group">
        <label for="category">Category:</label>
        <input type="text" name="category" id="category" required placeholder="Enter category (e.g., Adopt, Sale)">
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>
      </div>

      <div class="form-group">
        <label for="price">Price (INR):</label>
        <input type="number" name="price" id="price" required>
      </div>

      <div class="form-group">
        <label for="images">Pet Images:</label>
        <input type="file" name="images[]" id="images" multiple required>
      </div>

      <button type="submit" class="submit-btn">Add Pet</button>
    </form>
  </div>
</body>

</html>
