<?php
session_start();
include 'database.php';

// Handle login
if (isset($_POST['login'])) {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    $query = "SELECT * FROM doctor_login WHERE username = '$input_username' AND password = '$input_password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $_SESSION['loggedin'] = true;
    } else {
        $error_message = "Invalid username or password.";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

// Display login popup if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "
    <script>
        window.onload = function() {
            document.getElementById('login-popup').style.display = 'flex';
        };
    </script>
    ";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/doctor.css"> <!-- External CSS -->
</head>
<body>

<div id="login-popup">
    <div class="popup-content">
        <h2>Login</h2>
        <?php
        if (isset($error_message)) {
            echo "<p style='color:red;'>$error_message</p>";
        }
        ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</div>

<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    echo "<div class='dashboard-container'>";
    echo "<div class='nav-bar'>
            <div class='logo'>
                <img src='css/images/logo.png' alt='Logo'>
            </div>
            <h3>Dashboard</h3>
            <a href='#'>Home</a>
            <a href='doctor.php'>Appointments</a>
        
        </div>";
    echo "<div class='content-area'>
            <h2>Welcome to your Dashboard!</h2>
            <p>This is where you can manage your tasks, appointments, and more.</p>
            <a href='?logout=true' class='logout-btn'>Logout</a>
        </div>";
    echo "</div>";
}
?>

</body>
</html>
