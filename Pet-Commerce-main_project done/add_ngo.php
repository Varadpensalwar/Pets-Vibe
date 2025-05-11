<?php
include("database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO ngo_details (name, address, phone_number) VALUES ('$name', '$address', '$phone')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('NGO added successfully'); window.location.href='managengo.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add NGO</title>
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
</head>
<body style="font-family: Arial; margin: 40px; background-color: #f9f9f9;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #333;">Add New NGO</h2>
        <form method="POST" action="">
            <label style="display: block; margin-bottom: 8px;">NGO Name:</label>
            <input type="text" name="name" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #ccc;">

            <label style="display: block; margin-bottom: 8px;">Address:</label>
            <textarea name="address" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #ccc;"></textarea>

            <label style="display: block; margin-bottom: 8px;">Phone Number:</label>
            <input type="text" name="phone" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #ccc;">

            <input type="submit" value="Add NGO" style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
        </form>
        <br>
        <a href="managengo.php" style="display: inline-block; margin-top: 10px; text-decoration: none; color: #007bff;">← Back to Manage NGOs</a>
    </div>
</body>
</html>
