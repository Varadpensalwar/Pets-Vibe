<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/control.css">
    <title>Add Pet</title>
</head>
<body>

<h2>Add a New Pet</h2>

<form action="save_pet.php" method="post" enctype="multipart/form-data">
    <label for="title">Pet Title:</label>
    <input type="text" id="title" name="title" required><br><br>

    <label for="gender">Gender:</label>
    <select id="gender" name="gender" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select><br><br>

    <label for="age">Age:</label>
    <input type="text" id="age" name="age" required><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <label for="image">Pet Image:</label>
    <input type="file" id="image" name="image" accept="image/*" required><br><br>

    <input type="submit" value="Add Pet">
</form>

<a href="manage_pet.php">Manage Pets</a>

</body>
</html>
