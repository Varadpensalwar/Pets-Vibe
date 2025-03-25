<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/control.css">
    <title>Add Pet Product</title>
</head>
<body>
<h2>Add a New Pet Product</h2>

<form action="save_pet_product.php" method="post" enctype="multipart/form-data">
    <label for="title">Product Title:</label>
    <input type="text" id="title" name="title" required><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <label for="item">Item:</label>
    <input type="text" id="item" name="item" required><br><br>

    <label for="quantity">Qty:</label>
    <input type="text" id="quantity" name="quantity" required><br><br>

    <label for="image">Product Image:</label>
    <input type="file" id="image" name="image" accept="image/*" required><br><br>

    <input type="submit" value="Add Product">
</form>

<a href="manage_products.php">Manage Pet Products</a>

</body>
</html>
