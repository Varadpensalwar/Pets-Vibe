<?php
session_start();
include 'database.php'; // Include your database connection file

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();  // Destroy the session
    header("Location: index.php");  // Redirect to index.php
    exit();  // Stop further execution
}

// Login functionality
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);  // Sanitize input
    $password = trim($_POST['password']);  // Sanitize input

    // Prepare the query to fetch user by username
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // Bind the username to the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check if plain text passwords match
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id']; // Set session
            header("Location: dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            $error = "Invalid username or password.";
            header("Location: dashboard.php?error=" . urlencode($error)); // Redirect with error
            exit();
        }
    } else {
        $error = "Invalid username or password.";
        header("Location: dashboard.php?error=" . urlencode($error)); // Redirect with error
        exit();
    }

    $stmt->close();  // Close the statement
    $conn->close();  // Close the database connection
}
?>
