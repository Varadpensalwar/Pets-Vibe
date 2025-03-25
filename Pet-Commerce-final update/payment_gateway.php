<?php
session_start();
$formSubmitted = false;
$paymentStatus = "";
$paymentMethod = ""; // Define the variable to avoid undefined error

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $paymentMethod = $_POST['paymentMode']; // Now it gets value from POST data

    // Process the order based on payment method
    $formSubmitted = true;
    $orderSummary = [
        'Name' => htmlspecialchars($fullName),
        'Email' => htmlspecialchars($email),
        'Payment Method' => htmlspecialchars($paymentMethod),
    ];

    // Handle file upload for Bank Transfer
    if ($paymentMethod === 'bank_transfer') {
        $receiptFile = $_FILES['receipt']['name'];
        if ($receiptFile) {
            // Define the target directory for file uploads
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($receiptFile);

            // Move the uploaded file to the server
            if (move_uploaded_file($_FILES['receipt']['tmp_name'], $targetFile)) {
                $paymentStatus = "Bank transfer initiated. Receipt uploaded successfully!";
            } else {
                $paymentStatus = "Error uploading receipt file.";
            }
        }
    } else {
        // Online payment method (dummy, no actual payment process)
        $paymentStatus = "Online Payment initiated.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Payment Gateway</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 36px;
            color: #2d3e50;
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            color: #555;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            font-size: 16px;
            color: #555;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-top: 8px;
        }
        .form-select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-top: 8px;
        }
        .btn-primary {
            padding: 12px 20px;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .order-summary {
            margin-top: 30px;
        }
        .order-summary p {
            font-size: 18px;
            color: #333;
        }
        .order-summary ul {
            list-style-type: none;
            padding: 0;
        }
        .order-summary li {
            padding: 10px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
            border-radius: 8px;
        }
        .qr-code-container {
            margin-top: 30px;
            text-align: center;
        }
        .qr-code-container img {
            width: 200px;
            height: 200px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payment Gateway</h2>

        <!-- Display the form if it's not submitted yet -->
        <?php if (!$formSubmitted): ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="fullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullName" name="fullName" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="paymentMode" class="form-label">Payment Method</label>
                    <select class="form-select" id="paymentMode" name="paymentMode" required>
                        <option value="online">Online Payment</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
                <div class="form-group" id="receiptGroup" style="display: none;">
                    <label for="receipt" class="form-label">Upload Payment Receipt (For Bank Transfer)</label>
                    <input type="file" class="form-control" id="receipt" name="receipt">
                </div>
                <button type="submit" class="btn-primary">Place Order</button>
            </form>
        <?php else: ?>
            <!-- Order Summary after form submission -->
            <div class="order-summary">
                <h3>Order Summary</h3>
                <ul>
                    <li><strong>Name:</strong> <?php echo $orderSummary['Name']; ?></li>
                    <li><strong>Email:</strong> <?php echo $orderSummary['Email']; ?></li>
                    <li><strong>Payment Method:</strong> <?php echo $orderSummary['Payment Method']; ?></li>
                </ul>

                <!-- Payment Status -->
                <p><?php echo $paymentStatus; ?></p>
            </div>

            <!-- Payment QR Code (for Online Payment) -->
            <?php if ($paymentMethod === 'online'): ?>
                <div class="qr-code-container">
                    <p><strong>Scan QR Code to Pay</strong></p>
                    <img src="css/images/qr code.jpg" alt="QR Code">
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        // Show or hide receipt input based on payment method
        document.getElementById('paymentMode').addEventListener('change', function () {
            const receiptGroup = document.getElementById('receiptGroup');
            if (this.value === 'bank_transfer') {
                receiptGroup.style.display = 'block';
            } else {
                receiptGroup.style.display = 'none';
            }
        });
    </script>
</body>
</html>
