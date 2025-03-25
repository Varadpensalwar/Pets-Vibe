<?php
include 'database.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM pets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pet = $result->fetch_assoc();
} else {
    header("Location: manage_pet.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/control.css">
    <title>Edit Pet</title>
</head>
<body>

<h2>Edit Pet</h2>

<form action="update_pet.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $pet['id']; ?>">

    <label for="title">Pet Title:</label>
    <input type="text" id="title" name="title" value="<?php echo $pet['title']; ?>" required><br><br>

    <label for="gender">Gender:</label>
    <select id="gender" name="gender" required>
        <option value="Male" <?php if ($pet['gender'] == 'Male') echo 'selected'; ?>>Male</option>
        <option value="Female" <?php if ($pet['gender'] == 'Female') echo 'selected'; ?>>Female</option>
    </select><br><br>

    <label for="age">Age:</label>
    <input type="text" id="age" name="age" value="<?php echo $pet['age']; ?>" required><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" value="<?php echo $pet['price']; ?>" step="0.01" required><br><br>

    <label for="image">Pet Image:</label>
    <input type="file" id="image" name="image" accept="image/*"><br><br>

    <input type="submit" value="Update Pet">
</form>

<a href="manage_pet.php">Manage Pets</a>

</body>
</html>
