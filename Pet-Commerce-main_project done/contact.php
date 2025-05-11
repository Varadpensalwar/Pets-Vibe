<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us | Pet Haven</title>
  <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fdfcfb;
      color: #333;
    }

    .hero {
      background-color: orange;
      height: 25vh;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
      color: white;
      position: relative;
    }

    .hero::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.6);
      z-index: 1;
    }

    .hero h1,
    .hero p {
      z-index: 2;
      position: relative;
    }

    .hero h1 {
      font-size: 4rem;
      animation: fadeInDown 1.5s;
    }

    .hero p {
      font-size: 1.2rem;
      margin-top: 15px;
      animation: fadeInUp 2s;
    }

    .contact-section {
      padding: 80px 10%;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
    }

    .contact-info {
      display: flex;
      flex-direction: column;
      gap: 30px;
      background: #e4c9a4;

    }

    .contact-info div {
      display: flex;
      align-items: center;
      gap: 20px;
      font-size: 1.1rem;
    }

    .contact-info div i {
      font-size: 1.5rem;
      color: #e67e22;
    }

    form {
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    form input,
    form textarea {
      width: 100%;
      padding: 15px;
      margin: 15px 0;
      border: 1px solid #ddd;
      border-radius: 10px;
      font-size: 1rem;
    }

    form button {
      padding: 15px 30px;
      background: #e67e22;
      color: white;
      font-size: 1rem;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    form button:hover {
      background: #d35400;
    }

    .success-msg {
      margin-top: 20px;
      color: green;
      font-weight: 600;
    }

    @media(max-width: 900px) {
      .contact-section {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>

  <section class="hero">
    <h1>Contact Us</h1>
    <p>We're here for you and your pets. Reach out to us anytime!</p>
  </section>

  <section class="contact-section">
    <div class="contact-info animate__animated animate__fadeInLeft">
      <div><i class="fas fa-map-marker-alt"></i>  Sanjay Ghoadawat University Kolhapur</div>
      <div><i class="fas fa-phone-alt"></i> 90920030039</div>
      <div><i class="fas fa-envelope"></i> petsvibe@support.com</div>
      <div><i class="fas fa-clock"></i> Mon-Fri: 9am - 6pm</div>
    </div>

    <form method="POST" class="animate__animated animate__fadeInRight">
      <input type="text" name="name" placeholder="Your Name" required />
      <input type="email" name="email" placeholder="Your Email" required />
      <input type="text" name="subject" placeholder="Subject" />
      <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
      <button type="submit" name="submit">Send Message</button>

      <?php
    if (isset($_POST['submit'])) {
      include("database.php");
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $name = $conn->real_escape_string($_POST['name']);
      $email = $conn->real_escape_string($_POST['email']);
      $subject = $conn->real_escape_string($_POST['subject']);
      $message = $conn->real_escape_string($_POST['message']);

      $sql = "INSERT INTO user_concerns (name, email, subject, message, status)
              VALUES ('$name', '$email', '$subject', '$message', 'pending')";

      if ($conn->query($sql) === TRUE) {
        echo "<p class='success-msg'>Thank you! Your message has been sent.</p>";
      } else {
        echo "<p class='success-msg' style='color:red;'>Error: " . $conn->error . "</p>";
      }
      $conn->close();
    }
    ?>
    </form>
  </section>

</body>

</html>