<?php
session_start();
require_once('database.php'); // Include your DB connection

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    die("Your cart is empty. Please add items to the cart before proceeding.");
}

// Check if payment method is selected
if (!isset($_POST['paymentMode'])) {
    die("Payment method is required.");
}

$paymentMethod = $_POST['paymentMode']; // Either 'Online Payment' or 'Cash on Delivery'
$fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$city = mysqli_real_escape_string($conn, $_POST['city']);
$state = mysqli_real_escape_string($conn, $_POST['state']);
$zip = mysqli_real_escape_string($conn, $_POST['zip']);
$address = mysqli_real_escape_string($conn, $_POST['address']);

// Prepare the SQL query to insert order details
$stmt = $conn->prepare("INSERT INTO orders (full_name, email, phone, city, state, zip, address, payment_method, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

if ($stmt === false) {
    die("Error preparing query: " . $conn->error); // Output MySQL error if query preparation fails
}

$status = 'Pending';
$stmt->bind_param("sssssssss", $fullName, $email, $phone, $city, $state, $zip, $address, $paymentMethod, $status);

if ($stmt->execute()) {
    $orderId = $stmt->insert_id; // Get the inserted order ID

    // Prepare the query to insert order items
    $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, item_name, price, quantity) VALUES (?, ?, ?, ?)");

    if ($itemStmt === false) {
        die("Error preparing order items query: " . $conn->error); // Output MySQL error if query preparation fails
    }

    // Insert each item in the cart into the order_items table
    foreach ($_SESSION['cart'] as $item) {
        $itemName = $item['name'];
        $itemPrice = $item['price'];
        $itemQuantity = $item['quantity'];

        $itemStmt->bind_param("isdi", $orderId, $itemName, $itemPrice, $itemQuantity);
        if (!$itemStmt->execute()) {
            die("Error inserting order item: " . $itemStmt->error); // Handle error for each item insertion
        }
    }

    // Empty the cart after the order is placed
    unset($_SESSION['cart']);

    // Redirect based on payment method
    if ($paymentMethod == "Online Payment") {
        // Redirect to the payment gateway page with the order ID
  // Redirect to "thank you" page after successful order placement
header("Location: thank_you.php?order_id=$orderId");
exit();

    } else {
        // For COD, immediately redirect to the "thank you" page
        header("Location: thank_you.php?order_id=$orderId");
        exit();
    }
} else {
    echo "Error executing query: " . $stmt->error; // Output MySQL error if query execution fails
}

$stmt->close();
$itemStmt->close();
$conn->close();
?>
