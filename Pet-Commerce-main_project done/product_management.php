<?php
// Include the database connection file
require 'database.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Action for updating the order status
if (isset($_POST['cancel_order'])) {
    $orderId = $_POST['order_id'];

    // Query to fetch order details before cancellation for email
    $orderQuery = "SELECT * FROM orders WHERE id = ?";
    $stmt = $conn->prepare($orderQuery);
    if ($stmt === false) {
        die("Error preparing order query: " . $conn->error);
    }
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();

    // Send cancellation email
    sendCancellationEmail($order);

    // Query to delete the order
    $deleteQuery = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    if ($stmt === false) {
        die("Error preparing delete statement: " . $conn->error);
    }
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $stmt->close();

    // Redirect to prevent continuous form submission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['ship_order'])) {
    $orderId = $_POST['order_id'];
    $deliveryDate = $_POST['delivery_date'];
    $deliveryTime = $_POST['delivery_time'];
    $receiptFile = isset($_FILES['receipt_file']) ? $_FILES['receipt_file'] : null;

    if (!empty($deliveryDate) && !empty($deliveryTime)) {
        // Handle file upload if provided
        $fileName = null;
        if ($receiptFile && $receiptFile['error'] == 0) {
            $uploadDirectory = 'uploads/';
            $fileName = uniqid() . '-' . basename($receiptFile['name']);
            $uploadPath = $uploadDirectory . $fileName;
            move_uploaded_file($receiptFile['tmp_name'], $uploadPath);
        }

        // Update order with shipping details
        $updateQuery = "UPDATE orders SET status = 'Shipped', delivery_date = ?, delivery_time = ?, receipt_file = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        if ($stmt === false) {
            die("Error preparing update statement: " . $conn->error);
        }
        $stmt->bind_param("sssi", $deliveryDate, $deliveryTime, $fileName, $orderId);
        $stmt->execute();
        $stmt->close();

        // Send confirmation email
        sendOrderConfirmation($orderId);

        // Remove order after processing
        $deleteQuery = "DELETE FROM orders WHERE id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $stmt->close();

        // Redirect to prevent continuous form submission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "<script>alert('Please fill in the delivery date and time.'); window.location.reload();</script>";
    }
}

// Function to send order confirmation email
function sendOrderConfirmation($orderId) {
    global $conn;

    // Fetch order details from the database
    $orderQuery = "SELECT * FROM orders WHERE id = ?";
    $stmt = $conn->prepare($orderQuery);
    if ($stmt === false) {
        die("Error preparing order query: " . $conn->error);
    }

    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();

    // PHPMailer setup
    require 'vendor/autoload.php'; // Include PHPMailer's autoloader

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'petsvibe81@gmail.com'; // Sender's email
        $mail->Password = 'riph vfof zcyg vbru';  // Replace with app password if using Gmail with 2FA enabled
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient details
        $mail->setFrom('petsvibe81@gmail.com', 'Petsvibe');
        $mail->addAddress($order['email'], $order['full_name']); // Order's email and name

        // Email subject and body
        $mail->Subject = 'Order Shipped: ' . $order['id'];

        // Dynamically calculate total (assumes order_items table exists)
        $totalQuery = "SELECT SUM(price * quantity) AS total FROM order_items WHERE order_id = ?";
        $stmt = $conn->prepare($totalQuery);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $totalResult = $stmt->get_result();
        $totalRow = $totalResult->fetch_assoc();
        $total = $totalRow['total'] ?? 0;
        $stmt->close();

        // Prepare the email body
        $mail->Body = "Hello " . $order['full_name'] . ",\n\n" . 
                      "Your order with ID: " . $order['id'] . " has been shipped. " . 
                      "The expected delivery date is: " . $order['delivery_date'] . " at " . $order['delivery_time'] . ".\n\n" . 
                      "Order Details:\n" . 
                      "Order ID: " . $order['id'] . "\n" . 
                      "Customer: " . $order['full_name'] . "\n" . 
                      "Address: " . $order['address'] . ", " . $order['city'] . ", " . $order['state'] . " - " . $order['zip'] . "\n" . 
                      "Payment Method: " . $order['payment_method'] . "\n" . 
                      "Status: " . $order['status'] . "\n" . 
                      "Total: ₹" . number_format($total, 2) . "\n" . 
                      "Receipt: " . (empty($order['receipt_file']) ? "Not Available" : "View Receipt") . "\n" . 
                      "Created At: " . $order['created_at'] . "\n\n" . 
                      "Thank you for shopping with us! We appreciate your business.\n\n" . 
                      "Best regards,\nPetsvibe Team";

        // Attach the invoice file if it exists
        if (!empty($order['receipt_file'])) {
            $receiptPath = 'uploads/' . $order['receipt_file'];  // Path to the uploaded file
            if (file_exists($receiptPath)) {
                $mail->addAttachment($receiptPath);  // Attach the receipt file
            } else {
                echo "Error: Receipt file not found.";
            }
        }

        // Send email
        $mail->send();
        echo 'Confirmation email sent.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Function to send order cancellation email
function sendCancellationEmail($order) {
    global $conn;

    // PHPMailer setup
    require 'vendor/autoload.php'; // Include PHPMailer's autoloader

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'petsvibe81@gmail.com'; // Sender's email
        $mail->Password = 'riph vfof zcyg vbru';  // Replace with app password if using Gmail with 2FA enabled
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient details
        $mail->setFrom('petsvibe81@gmail.com', 'Petsvibe');
        $mail->addAddress($order['email'], $order['full_name']); // Order's email and name

        // Email subject and body
        $mail->Subject = 'Order Cancellation: ' . $order['id'];

        // Prepare the email body
        $mail->Body = "Hello " . $order['full_name'] . ",\n\n" . 
                      "We regret to inform you that your order with ID: " . $order['id'] . " has been cancelled. " . 
                      "We apologize for any inconvenience caused.\n\n" . 
                      "Order Details:\n" . 
                      "Order ID: " . $order['id'] . "\n" . 
                      "Customer: " . $order['full_name'] . "\n" . 
                      "Address: " . $order['address'] . ", " . $order['city'] . ", " . $order['state'] . " - " . $order['zip'] . "\n" . 
                      "Payment Method: " . $order['payment_method'] . "\n" . 
                      "Status: " . $order['status'] . "\n" . 
                      "Created At: " . $order['created_at'] . "\n\n" . 
                      "Thank you for your understanding.\n\n" . 
                      "Best regards,\nPetsvibe Team";

        // Send email
        $mail->send();
        echo 'Cancellation email sent.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!-- HTML content for Order Management -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Management</title>
    <link href="css/ordermanagement.css" rel="stylesheet">
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
</head>
<body>

<header>
    <div class="logo img">
      <a href="index.php">
        <img src="css/images/logo.png" alt="Your Logo Here" />
      </a>
    </div>
<h4 class="title">Order Management</h4>
  
  </header>
    <div class="container mt-5">
  
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Address</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT id, full_name, address, city, state, zip, payment_method, status, created_at, receipt_file, email FROM orders ORDER BY created_at DESC";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $totalQuery = "SELECT SUM(price * quantity) AS total FROM order_items WHERE order_id = ?";
                        $stmt = $conn->prepare($totalQuery);
                        $stmt->bind_param("i", $row['id']);
                        $stmt->execute();
                        $totalResult = $stmt->get_result();
                        $totalRow = $totalResult->fetch_assoc();
                        $total = $totalRow['total'] ?? 0;
                ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['address'] . ', ' . $row['city'] . ', ' . $row['state'] . ' - ' . $row['zip']; ?></td>
                        <td><?php echo $row['payment_method']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>₹<?php echo number_format($total, 2); ?></td>

                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                <div>
                                    <input type="text" name="delivery_date" class="form-control mb-2" placeholder="Delivery Date" required>
                                    <input type="time" name="delivery_time" class="form-control mb-2" required>
                                    <input type="file" name="receipt_file" class="form-control mb-2">
                                    <button type="submit" name="ship_order" class="btn btn-primary">Ship Order</button>
                                    <button type="submit" name="cancel_order" class="btn btn-danger mb-2">Cancel</button>
                                </div>
                              
                            </form>
                           
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='9' class='text-center'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>







