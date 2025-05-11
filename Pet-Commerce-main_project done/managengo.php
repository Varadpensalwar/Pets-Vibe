<?php
include("database.php");

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM ngo_details WHERE id=$id");
    echo "<script>alert('NGO deleted successfully'); window.location.href='managengo.php';</script>";
}

$result = $conn->query("SELECT * FROM ngo_details");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage NGOs</title>
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
</head>
<body style="font-family: Arial; margin: 40px; background-color: #f2f2f2;">
    <div style="max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #333;">Manage NGOs</h2>
        <div style="text-align: right; margin-bottom: 20px;">
            <a href="add_ngo.php" style="background-color: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">+ Add New NGO</a>
        </div>

        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <tr style="background-color: #007bff; color: white;">
                <th>ID</th>
                <th>NGO Name</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Action</th>
            </tr>

            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['address'] ?></td>
                <td><?= $row['phone_number'] ?></td>
                <td>
                    <a href="managengo.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure to delete this NGO?')" style="color: red; text-decoration: none;">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
