<?php
$orderId = $_GET['order_id'] ?? 'Unknown';
$status = $_GET['status'] ?? 'Pending';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            text-align: center;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 32px;
            color: #2d3e50;
            margin-bottom: 15px;
        }
        p {
            font-size: 18px;
            color: #555;
        }
        .order-info {
            margin: 20px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .order-info strong {
            font-size: 20px;
            color: #2d3e50;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            border-radius: 30px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thank You for Your Order!</h2>
        <p>Your order has been placed successfully. We will process it shortly.</p>
        
        <div class="order-info">
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($orderId); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($status)); ?></p>
        </div>
        
        <a href="index.php" class="btn">Return to Home</a>
    </div>
</body>
</html>
