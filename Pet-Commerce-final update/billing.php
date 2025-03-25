<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Page</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to your CSS file -->
</head>
<body>

<h2>Billing Information</h2>

<?php
include 'database.php';

// Check if pet_id is set in the URL
if (isset($_GET['pet_id'])) {
    $pet_id = intval($_GET['pet_id']);
    
    // Fetch pet details from the database
    $query = "SELECT * FROM pets WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $pet_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get the pet data
        $pet = $result->fetch_assoc();
        
        // Display pet details
        echo "<div class='billing_details'>";
        echo "<img src='" . htmlspecialchars($pet['image_path']) . "' alt='Pet Image' width='200'>";
        echo "<h3>Pet Name: " . htmlspecialchars($pet['title']) . "</h3>";
        echo "<p>Gender: " . htmlspecialchars($pet['gender']) . "</p>";
        echo "<p>Age: " . htmlspecialchars($pet['age']) . "</p>";
        echo "<p>Price: &#8377; " . number_format($pet['price'], 2) . "</p>";
        echo "</div>";
        
        // Provide a form for payment or confirmation
        echo "<form action='process_payment.php' method='POST'>";
        echo "<input type='hidden' name='pet_id' value='" . htmlspecialchars($pet['id']) . "'>";
        echo "<input type='hidden' name='price' value='" . htmlspecialchars($pet['price']) . "'>";
        echo "<button type='submit' class='btn btn_bg'>Proceed to Payment</button>";
        echo "</form>";
    } else {
        echo "<p>No pet found with the provided ID.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Invalid request. Please go back to manage pets.</p>";
}

$conn->close();
?>

<a href="manage_pets.php" class="btn btn_outlined">Back to Manage Pets</a>

</body>
</html>
