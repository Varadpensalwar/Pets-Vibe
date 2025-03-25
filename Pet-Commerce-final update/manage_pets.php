<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>featured  Pets</title>
    <link rel="stylesheet" href="css/control.css.">
</head>
<body>

<header>
    <div class="logo img">
      <a href="index.php">
        <img src="css/images/logo.png" alt="Your Logo Here" />
      </a>
    </div>
<h4 class="title">Featured Pets</h4>
  
  </header>

<table border="1">
    <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Gender</th>
        <th>Age</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>

    <?php
    include 'database.php';

    // Fetch all pets from the database
    $query = "SELECT * FROM pets";
    $result = $conn->query($query);

    // Loop through each pet and create a table row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td><img src='" . htmlspecialchars($row['image_path']) . "' alt='Pet Image' width='100'></td>
                <td>" . htmlspecialchars($row['title']) . "</td>
                <td>" . htmlspecialchars($row['gender']) . "</td>
                <td>" . htmlspecialchars($row['age']) . "</td>
                <td>
                    <a href='billing.php?pet_id=" . $row['id'] . "&price=" . $row['price'] . "'>
                        &#8377; " . number_format($row['price'], 2) . "
                    </a>
                </td>
                <td>
                    <a href='edit_pet.php?id=" . $row['id'] . "'>Edit</a> | 
                    <a href='process_delete_pet.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this pet?\")'>Delete</a>
                </td>
              </tr>";
    }

    $conn->close();
    ?>
</table>

<a href="add_pet.php">Add New Pet</a>

</body>
</html>
