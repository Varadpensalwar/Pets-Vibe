<?php
// Database connection
include 'database.php';




// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch appointments
$result = $conn->query("SELECT * FROM appointments WHERE status = 'pending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href=" css\styles.css.">
    <title>Appointments</title>
    <script>
        function approveAppointment(id) {
            document.getElementById('appointmentId').value = id;
            document.getElementById('doctorDetailsModal').style.display = 'flex'; // Show modal
        }

        function closeModal() {
            document.getElementById('doctorDetailsModal').style.display = 'none'; // Close modal
        }
    </script>
</head>
<body>
    <header>
        <img src="css/images/logo.png" alt="Logo" class="logo">
        <h1 class="header-title">Pending Appointments</h1>
    </header>

    <div class="appointments-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='appointment-card'>";
                echo "<p class='appointment-detail'>Pet Name: " . htmlspecialchars($row['petName']) . "</p>";
                echo "<p class='appointment-detail'>Owner Name: " . htmlspecialchars($row['ownerName']) . "</p>";
                echo "<p class='appointment-detail'>Contact Number: " . htmlspecialchars($row['contactNumber']) . "</p>";
                echo "<p class='appointment-detail'>Appointment Date: " . htmlspecialchars($row['appointmentDate']) . "</p>";
                echo "<p class='appointment-detail'>Service Type: " . htmlspecialchars($row['serviceType']) . "</p>";
                echo "<p class='appointment-detail'>Pet Breed: " . htmlspecialchars($row['petBreed']) . "</p>";
                echo "<p class='appointment-detail'>Pet Gender: " . htmlspecialchars($row['petGender']) . "</p>";
                echo "<p class='appointment-detail'>Pet Age: " . htmlspecialchars($row['petAge']) . " years</p>";
                echo "<p class='appointment-detail'>Pet Type: " . htmlspecialchars($row['petType']) . "</p>";

                if (!empty($row['petPicture'])) {
                    echo "<img src='" . htmlspecialchars($row['petPicture']) . "' alt='Pet Picture' class='pet-picture'/>";
                }

                echo "<div class='button-group'>";
                echo "<form method='post' action='updateappointment.php' enctype='multipart/form-data' class='button-form'>";
                echo "<input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>";
                echo "<button type='button' class='approve-button' onclick='approveAppointment(" . htmlspecialchars($row['id']) . ")'>Approve</button>";
                echo "<button type='submit' class='reject-button' name='action' value='reject'>Reject</button>";
                echo "</form>";
                echo "</div></div><hr>";
            }
        } else {
            echo "<p>No pending appointments.</p>";
        }
        ?>
    </div>

    <!-- Doctor's Details Modal -->
    <div id="doctorDetailsModal" class="modal" style="display:none;">
        <div class="modal-content">
            <h2 class="modal-title">Doctor's Details</h2>
            <form method="post" action="updateappointment.php" enctype="multipart/form-data">
                <input type="hidden" name="id" id="appointmentId">
                <label for="doctorName">Doctor's Name:</label>
                <input type="text" id="doctorName" name="doctorName" required class="modal-input"><br>

                <label for="degree">Degree:</label>
                <input type="text" id="degree" name="degree" required class="modal-input"><br>

                <label for="doctorPhone">Phone Number:</label>
                <input type="text" id="doctorPhone" name="doctorPhone" required class="modal-input"><br>

                <label for="specialty">Specialty:</label>
                <input type="text" id="specialty" name="specialty" required class="modal-input"><br>

                <label for="experience">Years of Experience:</label>
                <input type="number" id="experience" name="experience" required class="modal-input"><br>

                <label for="clinicAddress">Clinic Address:</label>
                <input type="text" id="clinicAddress" name="clinicAddress" required class="modal-input"><br>

                <label for="pdfFile">Upload PDF:</label>
                <input type="file" id="pdfFile" name="pdfFile" accept=".pdf" required class="modal-input"><br>

                <button type="submit" name="action" value="saveDetails" class="modal-submit">Save Details</button>
                <button type="button" onclick="closeModal()" class="modal-cancel">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
