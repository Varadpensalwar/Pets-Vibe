<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Calculate totals
$subtotal = 0;
$shipping = 5.00; // Flat shipping fee

foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal + $shipping;

// Razorpay API Configuration
// $keyId = "rzp_test_7HnljG4XaZVhXg"; // Replace with your Razorpay Key ID
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-7">
                <h2 class="mb-4">Checkout</h2>
                
                <h5>Choose Payment Mode</h5>
                <div class="btn-group mb-4" role="group">
                    <input type="radio" class="btn-check" name="paymentMode" id="online" value="Online Payment" required>
                    <label class="btn btn-outline-primary" for="online">Online Payment</label>

                    <input type="radio" class="btn-check" name="paymentMode" id="cod" value="Cash on Delivery" required>
                    <label class="btn btn-outline-primary" for="cod">Cash on Delivery</label>
                </div>

                <form action="process_checkout.php" method="POST" id="checkoutForm">
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="fullName" name="fullName" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-6">
                            <label for="state" class="form-label">State *</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="zip" class="form-label">ZIP Code *</label>
                        <input type="text" class="form-control" id="zip" name="zip" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Full Address *</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>

                    <input type="hidden" name="paymentMode" id="paymentMode" required>
                    <input type="hidden" name="payment_id" id="payment_id">
                    <button type="button" id="rzp-button" class="btn btn-primary w-100">Place Order</button>
                </form>
            </div>

            <div class="col-md-5">
                <h4 class="mb-4">Review Your Cart</h4>
                <ul class="list-group mb-3">
                    <?php if (!empty($cart)): ?>
                        <?php foreach ($cart as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="50" height="50" class="rounded">
                                    <div class="ms-3">
                                        <span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</span>
                                    </div>
                                </div>
                                <span>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-center">Your cart is empty</li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>₹<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Shipping:</span>
                    <span>₹<?php echo number_format($shipping, 2); ?></span>
                </div>
            
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong>₹<?php echo number_format($total, 2); ?></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Razorpay Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById('rzp-button').onclick = function(e) {
            e.preventDefault();

            const paymentMode = document.querySelector('input[name="paymentMode"]:checked');
            if (!paymentMode) {
                alert('Please select a payment method.');
                return;
            }

            document.getElementById('paymentMode').value = paymentMode.value;

            if (paymentMode.value === 'Online Payment') {
                var options = {
                    "key": "<?php echo $keyId; ?>",
                    "amount": "<?php echo $total * 100; ?>",
                    "currency": "INR",
                    "name": "Your Store Name",
                    "description": "Order Payment",
                    "handler": function (response) {
                        alert("Payment successful. Payment ID: " + response.razorpay_payment_id);
                        document.getElementById('payment_id').value = response.razorpay_payment_id;
                        document.getElementById('checkoutForm').submit();
                    },
                    "prefill": {
                        "name": document.getElementById('fullName').value,
                        "email": document.getElementById('email').value,
                        "contact": document.getElementById('phone').value
                    }
                };
                var rzp = new Razorpay(options);
                rzp.open();
            } else {
                document.getElementById('checkoutForm').submit();
            }
        }
    </script>
</body>
</html>
