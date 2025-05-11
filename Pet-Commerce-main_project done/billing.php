<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Page</title>
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .billing-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .billing-container img {
            border-radius: 10px;
        }
        .btn {
            border-radius: 50px;
        }
        .btn_bg {
            background-color: #007bff;
            color: #fff;
        }
        .btn_bg:hover {
            background-color: #0056b3;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="billing-container">
        <h2>Billing Information</h2>
        <?php
        include 'database.php';

        if (isset($_GET['pet_id'])) {
            $pet_id = intval($_GET['pet_id']);
            $query = "SELECT * FROM pets WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $pet_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $pet = $result->fetch_assoc();
                echo "<div class='row align-items-center'>";
                echo "<div class='col-md-4 text-center'>";
                echo "<img src='" . htmlspecialchars($pet['image_path']) . "' alt='Pet Image' class='img-fluid'>";
                echo "</div>";
                echo "<div class='col-md-8'>";
                echo "<h3 class='mt-3'>" . htmlspecialchars($pet['title']) . "</h3>";
                echo "<p><strong>Gender:</strong> " . htmlspecialchars($pet['gender']) . "</p>";
                echo "<p><strong>Age:</strong> " . htmlspecialchars($pet['age']) . "</p>";
                echo "<p><strong>Price:</strong> ₹" . number_format($pet['price'], 2) . "</p>";
                echo "</div>";
                echo "</div>";
                echo "<hr>";

                // Pass pet data to the checkout page
                echo "<form action='checkout.php' method='POST'>";
                echo "<input type='hidden' name='pet_id' value='" . htmlspecialchars($pet['id']) . "'>";
                echo "<input type='hidden' name='name' value='" . htmlspecialchars($pet['title']) . "'>";
                echo "<input type='hidden' name='image' value='" . htmlspecialchars($pet['image_path']) . "'>";
                echo "<input type='hidden' name='price' value='" . htmlspecialchars($pet['price']) . "'>";
                echo "<button type='submit' class='btn btn_bg px-4 py-2'>Proceed to Checkout</button>";
                echo "</form>";
            } else {
                echo "<p class='text-danger text-center'>No pet found with the provided ID.</p>";
            }
            $stmt->close();
        } else {
            echo "<p class='text-danger text-center'>Invalid request. Please go back to manage pets.</p>";
        }
        $conn->close();
        ?> 

        <div class="text-center mt-4">
            <a href="manage_pets.php" class="btn btn-outline-primary px-4 py-2">Back to Manage Pets</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
