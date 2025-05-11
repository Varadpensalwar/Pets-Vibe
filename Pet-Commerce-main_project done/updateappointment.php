<?php
// Include Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
include 'database.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        // Update appointment status to approved
        $stmt = $conn->prepare("UPDATE appointments SET status = 'approved' WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo "Appointment approved successfully!<br>";
        } else {
            echo "Error approving appointment: " . $stmt->error;
        }
    } elseif ($action === 'saveDetails') {
        // Save doctor's details
        $doctorName = $_POST['doctorName'];
        $degree = $_POST['degree'];
        $doctorPhone = $_POST['doctorPhone'];
        $specialty = $_POST['specialty'];
        $experience = $_POST['experience'];
        $clinicAddress = $_POST['clinicAddress'];
        $pdfFile = $_FILES['pdfFile'];
        $pdfFilePath = 'uploads/' . basename($pdfFile['name']);

        if (move_uploaded_file($pdfFile['tmp_name'], $pdfFilePath)) {
            echo "PDF uploaded successfully.<br>";

            // Update appointment with doctor's details and PDF path
            $stmt = $conn->prepare("UPDATE appointments SET doctorName = ?, doctorDegree = ?, doctorPhone = ?, specialty = ?, experience = ?, clinicAddress = ?, pdfFile = ?, status = 'approved' WHERE id = ?");
            $stmt->bind_param("sssssssi", $doctorName, $degree, $doctorPhone, $specialty, $experience, $clinicAddress, $pdfFilePath, $id);

            if ($stmt->execute()) {
                echo "Doctor's details saved and appointment approved successfully!<br>";

                // Fetch appointment details for email
                $stmt = $conn->prepare("SELECT ownerName, contactNumber, petPicture, email, address, serviceType, petName FROM appointments WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $appointment = $result->fetch_assoc();

                // Send confirmation email
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'petsvibe81@gmail.com';
                    $mail->Password = 'riph vfof zcyg vbru';  // Replace with a secure method for storing passwords
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('petsvibe81@gmail.com', 'Petsvibe');
                    $mail->addAddress($appointment['email']);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Appointment Confirmation';
                    $mail->Body = "<div style='font-family: Arial, sans-serif; color: #333;'>
                                      <h2 style='color: #000;'>🐶Appointment Confirmation - Petsvibe</h2>
                                      <p>Dear " . htmlspecialchars($appointment['ownerName']) . ",</p>
                                      <p>Your appointment for <strong>" . htmlspecialchars($appointment['petName']) . "</strong> has been approved. Here are the details:</p>
                                      <h3>Appointment Details:</h3>
                                      <ul>
                                          <li><strong>Pet Name:</strong> " . htmlspecialchars($appointment['petName']) . "</li>
                                          <li><strong>Owner Name:</strong> " . htmlspecialchars($appointment['ownerName']) . "</li>
                                          <li><strong>Contact Number:</strong> " . htmlspecialchars($appointment['contactNumber']) . "</li>
                                          <li><strong>Service Type:</strong> " . htmlspecialchars($appointment['serviceType']) . "</li>
                                          <li><strong>House Address:</strong> " . htmlspecialchars($appointment['address']) . "</li>
                                      </ul>
                                      <h3>Doctor's Details:</h3>
                                      <ul>
                                          <li><strong>Doctor's Name:</strong> " . htmlspecialchars($doctorName) . "</li>
                                          <li><strong>Doctor's Degree:</strong> " . htmlspecialchars($degree) . "</li>
                                          <li><strong>Phone:</strong> " . htmlspecialchars($doctorPhone) . "</li>
                                          <li><strong>Specialty:</strong> " . htmlspecialchars($specialty) . "</li>
                                          <li><strong>Experience:</strong> " . htmlspecialchars($experience) . " years</li>
                                          <li><strong>Clinic Address:</strong> " . htmlspecialchars($clinicAddress) . "</li>
                                      </ul>
                                       <p>We look forward to seeing you and <strong>" . htmlspecialchars($appointment['petName']) . "</strong>!</p>
                                          <p>Please ensure your pet arrives 10-15 minutes before the scheduled time.</p>
                                          <p>Thank you for trusting us with your pet's grooming needs!</p>
                                          <p>Best Regards,<br>
                                            Pets-vibe🐾🐾<br>
                                           7588849009<br>
                                       </div>";
                                  

                    // Attach the PDF file
                    if (file_exists($pdfFilePath)) {
                        $mail->addAttachment($pdfFilePath); // Attach the PDF
                    } else {
                        echo "PDF file does not exist.";
                    }

                    $mail->send();
                    echo "Confirmation email sent.";

                    // Redirect to thank you page
                    header('Location: doctor.php');
                    exit();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                echo "Error saving doctor's details: " . $stmt->error;
            }
        } else {
            die("Failed to upload PDF file.");
        }
    } elseif ($action === 'reject') {
        // Update appointment status to rejected
        $stmt = $conn->prepare("UPDATE appointments SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Fetch client email for rejection email
            $stmt = $conn->prepare("SELECT ownerName, email FROM appointments WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $appointment = $result->fetch_assoc();

            // Send rejection email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'petsvibe81@gmail.com';
                $mail->Password = 'riph vfof zcyg vbru';  // Replace with a secure method for storing passwords
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('petsvibe81@gmail.com', 'Petsvibe');
                $mail->addAddress($appointment['email']);

                $mail->isHTML(true);
                $mail->Subject = 'Appointment Rejection';
                $mail->Body = "<div style='font-family: Arial, sans-serif; color: #333;'>
                                  <h4 style='color: #000;'>❌Appointment Rejection❌</h4>
                                  <p>Dear " . htmlspecialchars($appointment['ownerName']) . ",</p>
                                  <p>We regret to inform you that your pet grooming appointment request for <strong>" . htmlspecialchars($appointment['petName']) . "</strong> on <strong>" . htmlspecialchars($appointment['appointmentDate']) . "</strong> has been rejected. Unfortunately, we are unable to accommodate your appointment at this time.</p>
                                          <h3>Reason for Rejection:</h3>
                                          <p> Doctor Unavailable.</p>
                                          <p>We understand this may be disappointing, and we sincerely apologize for any inconvenience caused. We would love to help you reschedule your appointment or offer you alternative options.</p>
                                          <p>Please feel free to contact us at 7588849009 or reply to this email to discuss other available dates or services. We are happy to assist you with any other queries you may have.</p>
                                          <p>Thank you for your understanding, and we hope to serve you and <strong>" . htmlspecialchars($appointment['petName']) . "</strong> in the future.</p>
                                          <p>Best Regards,<br>
                                          pets-vibe <br>
                                          7588849009 <br>
                               </div>";

                $mail->send();
                echo "Rejection email sent.";
            
                header('Location:doctor.php');
                exit();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }

    $stmt->close();
}

$conn->close();
