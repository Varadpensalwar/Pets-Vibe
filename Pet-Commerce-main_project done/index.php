<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />

  <link rel="stylesheet" href="css/styles.css">
  <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">

  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    rel="stylesheet"
  />
  <title>Pets Vibe</title>
</head>

<body>

  <header class="header">
    
  <nav class="nav">
    <div class="container nav_container">
        
        <!-- Hamburger Button (Visible on Mobile) -->
        <div class="nav_hamburger" onclick="toggleMenu()">☰</div>

        <!-- Centered Logo -->
        <a href="#" class="nav_logo">
            <img src="css/images/logo.png" alt="Logo">
        </a>

        <!-- Navigation List -->
        <ul class="nav_list">
            <li class="nav_item"><a href="index.php" class="nav_link">Home</a></li>
            <li class="nav_item"><a href="productlisting.php" class="nav_link">Products</a></li>
            <li class="nav_item"><a href="AboutUs.html" class="nav_link">About</a></li>
            <li class="nav_item"><a href="petadoption.php" class="nav_link">Adopt</a></li>
        </ul>

        <!-- Login/Register Button (Hidden on Small Screens) -->
        <div class="nav_right">
            <button id="loginRegisterBtn" class="btn_bg btn">Login/Register</button>
        </div>
    </div>
</nav>

<script>
    function toggleMenu() {
        document.querySelector(".nav_list").classList.toggle("active");
    }
</script>



    <!-- Modal for Login/Register -->
<div id="modal" style="display: none;">
  <div id="modalContent">
      <span id="closeModal">&times;</span>
      <h2 id="modalTitle">Login</h2>
      <form id="authForm">
          <input type="email" id="email" placeholder="Email" required />
          <input type="password" id="password" placeholder="Password" required />
          <button type="submit">Login</button>
          <p>Don't have an account? <span id="toggleForm">Register</span></p>
          <a id="googleSignInBtn" class="google-signin" href="#">
              <img src="./css/images/google.png" alt="Sign in with Google" style="width: 40px; height: auto;" />
          </a>
      </form>
  </div>
</div>


      </div>
  </div>

<!-- Profile Panel -->
<div id="profilePanel" style="position: fixed; right: -300px; top: 0; width: 300px; height: 100%; background-color: #f7f7f7; box-shadow: -2px 0 5px rgba(0, 0, 0, 0.3); z-index: 9999; transition: right 0.3s;">
  <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background-color: #007BFF; color: white;">
    <h3 style="margin: 0;">Profile</h3>
    <button id="closeProfilePanel" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
  </div>
  <div style="padding: 20px; text-align: center;">
    <img id="profilePic" src="default-pic.jpg" alt="Profile Picture" style="width: 80px; height: 80px; border-radius: 50%; margin-bottom: 10px;">
    <h4 id="profileName" style="margin: 10px 0;">Name</h4>
    <p id="profileEmail" style="margin: 0; color: #555;">Email</p>
    <button id="logoutBtn" style="margin-top: 20px; padding: 10px 20px; background-color: #FF4D4D; border: none; color: white; cursor: pointer; border-radius: 5px;">Logout</button>
  </div>
</div>




    <div class="container header_wrapper_container">
      <div class="header_wrapper">
        <h1 class="header_main_title">Your Pet Our Responsiblity !</h1>
        <p class="header_p">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Iusto corporis quidem doloremque,
          error nostrum quae numquam.</p>
          <div class="header_btns">
            <a href="appointment.php" class="btn btn_outlined">Book Your Service</a>
            <a href="petadoption.php" class="btn btn_bg">Adopt-ME</a>
          </div>
      </div>
    </div>
    <!-- Slider Buttons (Only Visible on Mobile) -->
<!-- Slider Navigation Buttons -->

  <div class="dots-container"></div>
  


  </header>
  <!-- second section design start here -->
  <main>
    <section class="section">
      <div class="container">
          <div class="section_header">
              <div class="section_header_left">
                  <p class="section_header_p">What's New?</p>
                  <h2 class="section_header_h2">Take a Look at Some of Our Products</h2>
              </div>
              <div class="section_header_right">
                  <a href="petadoption.php" class="btn btn_outlined">See More &rarr;</a>
              </div>
          </div>
          
          <div class="row">
          <div class="row">
    <?php
    include 'database.php';
    $query = "SELECT * FROM pet_management LIMIT 10"; // Limit the number of pets to 10
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        // Decode the JSON array of image paths if stored as JSON
        $images = json_decode($row['images'], true); // Decode the JSON into an array
        
        // Check if the path already contains "uploads/"
        $imagePath = $images[0]; // Get the first image path directly from the array
        if (strpos($imagePath, 'uploads/') === false) {
            $imagePath = 'uploads/' . $imagePath; // Add 'uploads/' only if it's missing
        }

        // Removed the debugging line:
        // echo "<p>Image Path: " . $imagePath . "</p>"; 

        echo "<div class='column'>
                <div class='card'>
                    <img src='" . $imagePath . "' alt='Pet Image'>
                    <h3 class='card_body_title'>" . $row['name'] . "</h3>
                    <div class='card_body_details'>
                        <div class='card_body_details_gender'>Gender: " . $row['gender'] . "</div>
                        <div class='card_body_details_age'>Age: " . $row['age'] . "</div>
                    </div>
                    <a href='petdetails.php?id=" . $row['id'] . "' class='card_body_price'>
                        &#8377; " . number_format($row['price'], 2) . "
                    </a>
                </div>
            </div>";
    }
    $conn->close();
    ?>
</div>


      </div>
      <!-- banner created -->
      <section class="section">
      <div class="container">
        <div class="banner">
         <div class="banner_wrapper">
          <img src="./css/images/model.png" alt="" class="banner_img">
          <div class="banner_content">
            <h1 class="banner_main_title">Your Pet Our Responsiblity !</h1>
        <p class="banner_p">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Iusto corporis quidem doloremque,
          error nostrum quae numquam.</p>
          <div class="header_btns">
            <a href="appointment.php" class="btn btn_outlined">Book Your Service</a>
            <a href="productlisting.php" class="btn btn_bg">Our-Products</a>
         </div>

          </div>
        </div>
      </div>
    </section>

  </section>
  <?php
// Include your database connection file
include 'database.php';

// Fetch products from the database
$sql = "SELECT * FROM products LIMIT 10";  // Limit the number of products to 10
$result = $conn->query($sql);
?>

<section class="section">
  <div class="container">
    <div class="section_header">
      <div class="section_header_left">
        <p class="section_header_p">NO Discount On Pets Grooming Products?</p>
        <h2 class="section_header_h3">Our Products</h2>
      </div>
      <div class="section_header_right">
        <a href="productlisting.php" class="btn btn_outlined">See More&rarr;</a>
      </div>
    </div>
    <div class="row">
      <?php
      // Check if products are available
      if ($result->num_rows > 0) {
        // Loop through each product and display it
        while ($row = $result->fetch_assoc()) {
          // Decode the JSON array of image paths if stored as JSON
          $images = json_decode($row['images'], true); // Decode the JSON into an array
          
          // Check if the image paths array is not empty and fetch the first image
          if (!empty($images)) {
            $imagePath = $images[0]; // Get the first image path from the array
            // Add 'uploads/' if the path doesn't already include it
            if (strpos($imagePath, 'uploads/') === false) {
              $imagePath = 'uploads/' . $imagePath; // Prepend 'uploads/' if missing
            }
          } else {
            $imagePath = 'uploads/default_image.jpg'; // Use a default image if no images exist
          }
      ?>
          <div class="column">
            <div class="card">
              <img src="<?php echo $imagePath; ?>" alt="Product Image" class="card_body_img"> <!-- Use the first image from the JSON array -->
              <h3 class="card_body_title"><?php echo $row['name']; ?></h3> <!-- Product name -->
              <div class="card_body_details">
                <div class="card_body_details_item">Brand: <?php echo $row['brand']; ?></div> <!-- Display brand -->
                <div class="card_body_details_quantity">Size: <?php echo $row['size']; ?></div> <!-- Display size -->
              </div>
              <!-- Link to productdetails.php with the product's ID -->
              <a href="productdetails.php?id=<?php echo $row['id']; ?>" class="card_body_price">&#8377; <?php echo number_format($row['price'], 2); ?></a> <!-- Product price -->
              <div class="card_body_gift">
                <img src="./css/images/gift.png" alt="" class="card_body_gift_icon">
                <p class="card_body_gift_p">Free toy & Free Shaker</p>
              </div>
            </div>
          </div>
      <?php
        }
      } else {
        echo "No products available.";
      }

      // Close the database connection
      $conn->close();
      ?>    
    </div>
  </div>
</section>





    <section class="section">
      <div class="container">
        <div class="section_header">
          <div class="section_header_left">
            <p class="section_header_p">They Are With Us</p>
            <h2 class="section_header_h3">Top Brads</h2>
          </div>
       
  
        </div>
        <div class="logos_container">
          <img src="./css/images/Brand 2.png" alt="" class="brand_logo">
          <img src="./css/images/Brand 1.png" alt="" class="brand_logo">
          <img src="./css/images/Brand 3.png" alt="" class="brand_logo">
          <img src="./css/images/Brand 4.png" alt="" class="brand_logo">
          <img src="./css/images/Brand 5.png" alt="" class="brand_logo">
          <img src="./css/images/Brand 7.png" alt="" class="brand_logo">
          <img src="./css/images/Brand 6.png" alt="" class="brand_logo">
        </div> </div>
    </section>
    <section class="section">
      <div class="container">
        <div class="banner2">
         <div class="banner_wrapper">
          <img src="./css/images/model.png" alt="" class="banner_img">
          <div class="banner_content">
            <h1 class="banner_main_title">Your Pet Our Responsiblity !</h1>
        <p class="banner_p">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Iusto corporis quidem doloremque,
          error nostrum quae numquam.</p>
          <div class="header_btns">
            <a href="appointment.php" class="btn btn_outlined">Book Your Service</a>
            <a href="blogs.php" class="btn btn_bg">Our-Blogs</a>
         </div>

          </div>
        </div>
      </div>
    </section>
    <section>
    <section class="section">

    <div class="container">
    <div class="section_header">
      <div class="section_header_left">
        <p class="section_header_p">what you know about Pets?</p>
        <h2 class="section_header_h3">Recent Blogs</h2>
      </div>
      <div class="section_header_right">
        <a href="blogs.php" class="btn btn_outlined">See More&rarr;</a>
      </div>
    </div>
   
   
          
          <?php
include 'database.php'; // Database connection

// Fetch the latest 4 blogs dynamically (changed limit from 3 to 4)
$query = "SELECT * FROM blogs ORDER BY created_at DESC LIMIT 6"; 
$result = $conn->query($query);

// Check if there are any blogs
if ($result && $result->num_rows > 0) {
    // Wrap the blog cards in a container with a unique class 'custom-blog-section'
    echo '<div class="custom-blog-section">';
    
    // Loop through each blog and display it
    while ($row = $result->fetch_assoc()):
        $image_url = !empty($row['image_url']) ? htmlspecialchars($row['image_url']) : 'default-image.jpg';
        $title = htmlspecialchars($row['title']);
        $category = htmlspecialchars($row['category']);
        $description = !empty($row['description']) ? htmlspecialchars(substr($row['description'], 0, 100)) . '...' : 'No description available.';
        $id = htmlspecialchars($row['id']);
?>
        <div class="custom-blog-card">
            <img src="<?php echo $image_url; ?>" alt="<?php echo $title; ?>" class="custom-blog-image">
            <div class="custom-blog-content">
                <h3 class="custom-blog-title"><?php echo $title; ?></h3>
                <p class="custom-blog-category"><?php echo $category; ?></p>
                <p class="custom-blog-description"><?php echo $description; ?></p>
                <a href="view_blog.php?id=<?php echo $id; ?>" class="custom-read-more-btn">Read More</a>
            </div>
        </div>
<?php
    endwhile;
    
    // Close the blog-section container
    echo '</div>';
} else {
    echo "<p>We couldn't find any blogs to show. Check back later!</p>";
}
?>


      </div>
    </section>
  </main>
  <footer>
    <div class="footer-container">
        <!-- About Section -->
        <div class="footer-section about">
            <h2>About Us</h2>
            <p>
                At PetCare, we are passionate about grooming and finding forever homes for pets. Our mission is to provide top-quality care for your furry friends.
            </p>
            <div class="social-icons">
  <a href="https://www.facebook.com/pet.vibes.0/" class="social-icon" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
  <a href="#" class="social-icon" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
  <a href="https://www.instagram.com/pet.vibes._/" class="social-icon" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
  <a href="#" class="social-icon" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
</div>

        </div>

        <!-- Quick Links -->
        <div class="footer-section links">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="service.html">Our Services</a></li>
                <li><a href="story.html">Our Story</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="blogs.php">Latest Blogs</a></li>
                <li><a href="petadoption.php">Adopt a Pet</a></li>
            </ul>
        </div>

        <!-- Contact Section -->
        <div class="footer-section contact">
            <h2>Contact Us</h2>
            <p><i class="fas fa-phone"></i> +1 234 567 890</p>
            <p><i class="fas fa-envelope"></i> support@petsvibe.com</p>
            <p><i class="fas fa-map-marker-alt"></i> Sanjay Ghoadawat University </p>
        </div>

        <!-- Newsletter Section -->
        <div class="footer-section newsletter">
            <h2>Stay Updated</h2>
            <p>Subscribe to our newsletter for exclusive updates, tips, and offers!</p>
            <form action="#" method="POST" class="newsletter-form">
                <input type="email" name="email" placeholder="Enter your email address" required>
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <p>© 2024 PetsVibe | All Rights Reserved. Designed with ❤️ by GSVK for pet lovers everywhere.</p>
    </div>
</footer>

  
  <script type="module" src="login.js"></script>
  <script type="module" src="firebaseConfig.js"></script>
  
    <script>
  const images = [
    "./css/images/mobilebackground.jpg",
    "./css/images/mobilebackground1.jpg",
    "./css/images/mobilebackgroud3.png",
    "./css/images/mobilebackground4.jpg",
    "./css/images/mobilebackground2.png",
  ];
  
  let index = 0;
  let autoSlideInterval; // Store auto-slide interval

  function createDots() {
    const dotsContainer = document.querySelector(".dots-container");
    dotsContainer.innerHTML = ""; // Clear previous dots

    images.forEach((_, i) => {
      const dot = document.createElement("span");
      dot.classList.add("dot");
      if (i === index) dot.classList.add("active");
      dot.setAttribute("onclick", `changeBackground(${i})`);
      dotsContainer.appendChild(dot);
    });
  }

  function changeBackground(newIndex) {
    if (typeof newIndex === "number") {
      index = newIndex; // Change via dot click
    } else {
      index += newIndex; // Change via prev/next
      if (index >= images.length) index = 0;
      if (index < 0) index = images.length - 1;
    }

    document.querySelector(".header").style.backgroundImage = `url(${images[index]})`;
    createDots();
    
    // Restart auto-slide when manually changed
    resetAutoSlide();
  }

  function autoSlide() {
    index = (index + 1) % images.length; // Loop through images
    document.querySelector(".header").style.backgroundImage = `url(${images[index]})`;
    createDots();
  }

  function startAutoSlide() {
    autoSlideInterval = setInterval(autoSlide, 5000); // Change every 5 seconds
  }

  function resetAutoSlide() {
    clearInterval(autoSlideInterval); // Stop auto-slide
    startAutoSlide(); // Restart auto-slide
  }

  // Initialize on page load (only for screens ≤ 480px)
  window.addEventListener("load", function () {
    if (window.innerWidth <= 480) {
      document.querySelector(".header").style.backgroundImage = `url(${images[0]})`;
      createDots();
      startAutoSlide(); // Start auto-slider
    }
  });
</script>
<!-- <script>
  if (window.innerWidth > 768) { 
    // Load chatbot scripts only on large screens
    var script1 = document.createElement("script");
    script1.src = "https://cdn.botpress.cloud/webchat/v2.3/inject.js";
    document.head.appendChild(script1);

    var script2 = document.createElement("script");
    script2.src = "https://files.bpcontent.cloud/2025/04/03/08/20250403082052-NUSJP7DJ.js";
    document.head.appendChild(script2);
  }
</script>       chatbot script  -->

</body>

</html>