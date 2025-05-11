<?php
// Database connection
include 'database.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $petName = $_POST['petName'];
    $ownerName = $_POST['ownerName'];
    $contactNumber = $_POST['contactNumber'];
    $appointmentDate = $_POST['appointmentDate'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $serviceType = $_POST['serviceType'];
    $petBreed = $_POST['petBreed'];   
    $petGender = $_POST['petGender']; 
    $petAge = $_POST['petAge'];       
    $petType = $_POST['petType'];     

    // Handle file upload
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $fileName = basename($_FILES["petPicture"]["name"]);
    $fileName = preg_replace("/\s+/", "_", $fileName);
    $targetFile = $targetDir . $fileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($_FILES["petPicture"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["petPicture"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if upload is allowed
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["petPicture"]["tmp_name"], $targetFile)) {
            // Prepare and execute the SQL statement
            $stmt = $conn->prepare("INSERT INTO appointments (petName, ownerName, contactNumber, appointmentDate, petPicture, email, address, serviceType, petBreed, petGender, petAge, petType) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssss", $petName, $ownerName, $contactNumber, $appointmentDate, $targetFile, $email, $address, $serviceType, $petBreed, $petGender, $petAge, $petType);

            if ($stmt->execute()) {
                // Redirect to success page
                header("Location: success.html");
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Sorry, your file was not uploaded.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/appointment.css">
    <title>Pet Appointment Booking</title>
</head>
<body>
    <div class="form-container">
        <h1 class="booking-header">Book an Appointment</h1>
        <form class="appointment-form" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="petName" class="form-label">Pet Name:</label>
                <input type="text" id="petName" name="petName" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="ownerName" class="form-label">Owner Name:</label>
                <input type="text" id="ownerName" name="ownerName" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="contactNumber" class="form-label">Contact Number:</label>
                <input type="text" id="contactNumber" name="contactNumber" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="appointmentDate" class="form-label">Appointment Date:</label>
                <input type="datetime-local" id="appointmentDate" name="appointmentDate" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Address:</label>
                <input type="text" id="address" name="address" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="serviceType" class="form-label">Service Type:</label>
                <select id="serviceType" name="serviceType" class="form-select" required>
                    <option value="Grooming">Grooming</option>
                    <option value="Vaccination">Vaccination</option>
                    <option value="Check-up">Check-up</option>
                    <option value="Training">Training</option>
                </select>
            </div>

            <div class="form-group">
                <label for="petBreed" class="form-label">Pet Breed:</label>
                <input type="text" id="petBreed" name="petBreed" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="petGender" class="form-label">Pet Gender:</label>
                <select id="petGender" name="petGender" class="form-select" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="petAge" class="form-label">Pet Age:</label>
                <input type="number" id="petAge" name="petAge" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="petType" class="form-label">Pet Type:</label>
                <select id="petType" name="petType" class="form-select" required>
                    <option value="Dog">Dog</option>
                    <option value="Cat">Cat</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="petPicture" class="form-label">Upload Pet Picture:</label>
                <input type="file" id="petPicture" name="petPicture" class="form-input-file" accept="image/*" required>
            </div>

            <button type="submit" class="form-submit">Book Appointment</button>
        </form>
 

    <script>
        // JavaScript to set minimum date and time restrictions
        document.addEventListener("DOMContentLoaded", function() {
            const appointmentDateInput = document.getElementById("appointmentDate");

            // Calculate the minimum date (2 days from now)
            const today = new Date();
            today.setDate(today.getDate() + 3);
            const minDate = today.toISOString().slice(0, 10); // Format as YYYY-MM-DD

            // Set the minimum date and set default time to 9 am
            appointmentDateInput.min = `${minDate}T09:00`;

            // Add an event listener to validate time selection
            appointmentDateInput.addEventListener("input", function() {
                const selectedDate = new Date(appointmentDateInput.value);
                const selectedHours = selectedDate.getHours();

                // If time is outside 9 am - 6 pm, reset to 9 am
                if (selectedHours < 9 || selectedHours > 18) {
                    appointmentDateInput.setCustomValidity("Please select a time between 9 am and 6 pm.");
                } else {
                    appointmentDateInput.setCustomValidity(""); // Clear any previous error
                }
            });
        });
    </script>
</body>
</html>
