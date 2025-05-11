-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 09:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pets_vibe`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `petName` varchar(255) NOT NULL,
  `ownerName` varchar(255) NOT NULL,
  `contactNumber` varchar(15) NOT NULL,
  `appointmentDate` datetime NOT NULL,
  `petPicture` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `doctorDegree` varchar(255) DEFAULT NULL,
  `doctorName` varchar(255) DEFAULT NULL,
  `doctorPhone` varchar(20) DEFAULT NULL,
  `specialty` varchar(255) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `clinicAddress` varchar(255) DEFAULT NULL,
  `pdfFile` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `serviceType` varchar(255) NOT NULL,
  `petBreed` varchar(100) DEFAULT NULL,
  `petGender` varchar(10) DEFAULT NULL,
  `petAge` int(11) DEFAULT NULL,
  `petType` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `petName`, `ownerName`, `contactNumber`, `appointmentDate`, `petPicture`, `status`, `doctorDegree`, `doctorName`, `doctorPhone`, `specialty`, `experience`, `clinicAddress`, `pdfFile`, `email`, `address`, `serviceType`, `petBreed`, `petGender`, `petAge`, `petType`) VALUES
(92, 'pappu', 'shweta', '07588849009', '2025-05-08 10:22:00', 'uploads/image_6.png', 'rejected', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'karandharmoji12345@gmail.com', '774 kolgaon near maruti mandir', 'Grooming', 'labrodog', 'Male', 12, 'Dog'),
(93, 'pappu', 'shweta', '07588849009', '2025-05-08 10:22:00', 'uploads/image_6.png', 'rejected', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'karandharmoji12345@gmail.com', '774 kolgaon near maruti mandir', 'Grooming', 'labrodog', 'Male', 12, 'Dog'),
(94, 'pappu', 'shweta', '07588849009', '2025-05-08 10:22:00', 'uploads/image_6.png', 'approved', ': Secondary School Certificate (10th Grade)', 'pankaj  rathhee', '75857959', 'groomer', 12, 'dyanand hospital swd', 'uploads/Invoice-Format-in-Pdf-01.pdf', 'karandharmoji12345@gmail.com', '774 kolgaon near maruti mandir', 'Grooming', 'labrodog', 'Male', 12, 'Dog'),
(95, 'pappu', 'karan', '07588849009', '2025-05-04 10:37:00', 'uploads/Frame_7_(5).png', 'approved', 'mbbs', 'pankaj', '9832097320', 'groomer', 22, 'dynajand clinc', 'uploads/Invoice-Format-in-Pdf-01.pdf', 'karandharmoji123345@gmail.com', '774 kolgaon near maruti mandir', 'Grooming', 'labrodog', 'Male', 23, 'Dog');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `description`, `image_url`, `category`, `created_at`) VALUES
(9, 'meow 2', 'This blog covers everything you need to know about caring for your dog—feeding, exercise, grooming, and much more. Keep your dog healthy and happy with these expert tips. Learn how to create a nutritious diet plan, maintain an active lifestyle for your dog, and provide the best grooming routine to keep them looking their best.This blog covers everything you need to know about caring for your dog—feeding, exercise, grooming, and much more. Keep your dog healthy and happy with these expert tips. Learn how to create a nutritious diet plan, maintain an active lifestyle for your dog, and provide the best grooming routine to keep them looking their best.This blog covers everything you need to know about caring for your dog—feeding, exercise, grooming, and much more. Keep your dog healthy and happy with these expert tips. Learn how to create a nutritious diet plan, maintain an active lifestyle for your dog, and provide the best grooming routine to keep them looking their best.This blog covers everything you need to know about caring for your dog—feeding, exercise, grooming, and much more. Keep your dog healthy and happy with these expert tips. Learn how to create a nutritious diet plan, maintain an active lifestyle for your dog, and provide the best grooming routine to keep them looking their best.This blog covers everything you need to know about caring for your dog—feeding, exercise, grooming, and much more. Keep your dog healthy and happy with these expert tips. Learn how to create a nutritious diet plan, maintain an active lifestyle for your dog, and provide the best grooming routine to keep them looking their best.', 'uploads/1742298508.jpg', 'story of meow', '2024-12-11 15:24:23'),
(10, 'doggy ', 'This blog covers everything you need to know about caring for your dog—feeding, exercise, grooming, and much more. Keep your dog healthy and happy with these expert tips. Learn how to create a nutritious diet plan, maintain an active lifestyle for your dog, and provide the best grooming routine to keep them looking their best.This blog covers everything you need to know about caring for your dog—feeding, exercise, grooming, and much more. Keep your dog healthy and happy with these expert tips. Learn how to create a nutritious diet plan, maintain an active lifestyle for your dog, and provide the best grooming routine to keep them looking their best.This blog covers everything you need to know about caring for your dog—feeding, exercise, grooming, and much more. Keep your dog healthy and happy with these expert tips. Learn how to create a nutritious diet plan, maintain an active lifestyle for your dog, and provide the best grooming routine to keep them looking their best.This blog covers everything you need to know about caring for your dog—feeding, exercise, grooming, and much more. Keep your dog healthy and happy with these expert tips. Learn how to create a nutritious diet plan, maintain an active lifestyle for your dog, and provide the best grooming routine to keep them looking their best.', 'uploads/1742298474.png', 'story of puppy', '2024-12-11 15:25:20'),
(12, 'Doremon', 'give me decritption for pet care blog or write a blog\r\nHere\'s a blog post for your pet care blog:\r\n\r\nThe Ultimate Guide to Pet Care: Ensuring Your Furry Friend\'s Well-being\r\nWelcome to our pet care blog, where we aim to provide you with the best tips and advice for keeping your beloved pets happy, healthy, and thriving. Whether you\'re a new pet owner or a seasoned animal lover, there\'s always something new to learn about pet care.\r\n\r\nUnderstanding Your Pet\'s Needs\r\nEvery pet is unique, with its own personality, preferences, and needs. It\'s important to take the time to understand what makes your pet happy and comfortable. Regular visits to the vet, a balanced diet, and plenty of exercise are fundamental aspects of good pet care.\r\n\r\nNutrition and Diet\r\nA healthy diet is the cornerstone of your pet\'s well-being. Ensure that you\'re providing high-quality food that meets your pet\'s nutritional requirements. Avoid overfeeding and be mindful of any food allergies or sensitivities your pet might have. Fresh water should always be available.\r\n\r\nExercise and Playtime\r\nPhysical activity is crucial for your pet\'s health. Dogs, for instance, need daily walks and playtime to burn off energy and stay fit. Cats, on the other hand, enjoy interactive toys that stimulate their hunting instincts. Find activities that your pet enjoys and make them a regular part of your routine.\r\n\r\nGrooming and Hygiene\r\nRegular grooming is essential for maintaining your pet\'s hygiene and preventing health issues. Brush your pet\'s fur to remove dirt and reduce shedding. Keep their nails trimmed and ensure their ears are clean. Regular baths can help keep your pet\'s coat shiny and free of parasites.\r\n\r\nTraining and Socialization\r\nProper training and socialization are key to a well-behaved pet. Start training your pet early, using positive reinforcement techniques. Socialize your pet with other animals and people to build their confidence and reduce anxiety.\r\n\r\nHealthcare and Vet Visits\r\nRegular check-ups with a veterinarian are vital to your pet\'s health. Vaccinations, flea and tick prevention, and dental care should all be part of your pet\'s healthcare routine. Keep an eye out for any changes in behavior or health and consult your vet if you have any concerns.\r\n\r\nConclusion\r\nTaking care of a pet is a rewarding experience that requires dedication and love. By following these tips, you can ensure that your furry friend lives a happy and healthy life. Stay tuned to our blog for more pet care advice, tips, and tricks.\r\n\r\nI hope this helps! Let me know if there\'s anything else you\'d like to add or if you have any other questions.\r\n\r\n', 'uploads/1733938430.png', 'pet care', '2024-12-11 17:33:50');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_session_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_login`
--

CREATE TABLE `doctor_login` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'doctor',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_login`
--

INSERT INTO `doctor_login` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Doctor', 'karan8001#', 'doctor', '2024-12-19 09:52:55');

-- --------------------------------------------------------

--
-- Table structure for table `ngo_details`
--

CREATE TABLE `ngo_details` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ngo_details`
--

INSERT INTO `ngo_details` (`id`, `name`, `address`, `phone_number`) VALUES
(1, 'Vithal lalabai prasad Ngo', 'MHT sawantwadi singdhudurg', '7588163329');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `receipt_file` varchar(255) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `delivery_time` time DEFAULT NULL,
  `order_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`id`, `title`, `gender`, `age`, `price`, `image_path`) VALUES
(33, 'new', 'Male', '22', 5000.00, 'css/images/image 2.png'),
(34, 'king', 'Male', '22', 10000.00, 'css/images/image 5.png'),
(37, 'new', 'Male', '20', 500.00, 'css/images/image 6.png');

-- --------------------------------------------------------

--
-- Table structure for table `pet_management`
--

CREATE TABLE `pet_management` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `species` varchar(100) NOT NULL,
  `breed` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `weight` float NOT NULL,
  `color` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `price` float NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet_management`
--

INSERT INTO `pet_management` (`id`, `name`, `species`, `breed`, `age`, `gender`, `weight`, `color`, `description`, `images`, `price`, `created_at`, `category`) VALUES
(13, 'raja', 'empire dog', 'bull dog', 10, 'Male', 25, 'black', 'Unleash the joy of playtime with [Your Toy Name Here]! 🎉 This delightful toy is designed to bring endless fun and excitement to children of all ages. 🧒👧 Crafted from high-quality, safe materials, it\\\'s both durable and kid-friendly. The vibrant colors and engaging design 🌈 captivate young imaginations, encouraging creative play and exploration. 🚀 Whether it\\\'s for solo adventures or group play, [Your Toy Name Here] is the perfect companion for making cherished memories. 💖 Easy to clean and store, it ensures hassle-free enjoyment for parents and kids alike. 🧼🧸 Get ready for smiles and laughter with this must-have addition to your toy collection! 😄✨', '[\"uploads\\/image 6.png\",\"uploads\\/image 5.png\",\"uploads\\/image 4.png\"]', 7000, '2024-12-18 19:09:10', 'Ranti Dog'),
(20, 'pappu', 'labrodog', 'labrodog', 2, 'Male', 23, 'brown', 'Pet Type: Dog Name: Max Breed: Golden Retriever Age: 3 years old Description: Max is a friendly and playful Golden Retriever with a shiny golden coat and a wagging tail. He has large, expressive brown eyes that are always full of curiosity and warmth. Max loves to play fetch, go for long walks, and enjoys belly rubs. He\\\'s incredibly loyal and gentle, making him a perfect companion for families and children. Max is also very intelligent and enjoys learning new tricks.', '[\"uploads\\/image 5.png\",\"uploads\\/image 4.png\",\"uploads\\/image 3.png\"]', 4000, '2024-12-19 22:21:55', 'Dog'),
(21, 'tattu', 'labrodog', 'labrodog', 2, 'Male', 23, 'brown', 'Pet Type: Dog Name: Max Breed: Golden Retriever Age: 3 years old Description: Max is a friendly and playful Golden Retriever with a shiny golden coat and a wagging tail. He has large, expressive brown eyes that are always full of curiosity and warmth. Max loves to play fetch, go for long walks, and enjoys belly rubs. He\\\'s incredibly loyal and gentle, making him a perfect companion for families and children. Max is also very intelligent and enjoys learning new tricks.', '[\"uploads\\/image 7.png\",\"uploads\\/image 3.png\",\"uploads\\/image 2.png\"]', 4000, '2024-12-19 22:22:19', 'Dog'),
(22, 'sheru', 'labrodog', 'labrodog', 2, 'Male', 23, 'brown', 'Pet Type: Dog Name: Max Breed: Golden Retriever Age: 3 years old Description: Max is a friendly and playful Golden Retriever with a shiny golden coat and a wagging tail. He has large, expressive brown eyes that are always full of curiosity and warmth. Max loves to play fetch, go for long walks, and enjoys belly rubs. He\\\'s incredibly loyal and gentle, making him a perfect companion for families and children. Max is also very intelligent and enjoys learning new tricks.', '[\"uploads\\/image 7.png\",\"uploads\\/image 6.png\",\"uploads\\/image 5.png\",\"uploads\\/image 2.png\"]', 4000, '2024-12-19 22:23:09', 'Dog'),
(26, 'meow', 'labrodog', 'labrodog', 2, 'Male', 23, 'brown', 'something.................', '[\"uploads\\/images (1).jpg\"]', 4000, '2025-04-02 23:22:47', 'cat');

-- --------------------------------------------------------

--
-- Table structure for table `pet_products`
--

CREATE TABLE `pet_products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `item` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet_products`
--

INSERT INTO `pet_products` (`id`, `title`, `price`, `item`, `quantity`, `image_path`) VALUES
(3, 'toy 1', 5000.00, 'popye', '22', 'uploads/Frame 7 (2).png'),
(9, 'new', 5000.00, 'popye', '25 gm', 'uploads/Frame 7.png'),
(12, 'funky toys', 200.00, 'toy', '1', 'uploads/Frame 7 (4).png'),
(14, 'new', 200.00, 'toy', '1', 'uploads/Frame 7.png');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(255) NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `description` text NOT NULL,
  `images` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category`, `brand`, `size`, `color`, `description`, `images`, `created_at`) VALUES
(18, 'doremon ', 1000.00, 'pets scince', 'whiskas', '1QTY', 'blue', 'Unleash the joy of playtime with [Your Toy Name Here]! 🎉 This delightful toy is designed to bring endless fun and excitement to children of all ages. 🧒👧 Crafted from high-quality, safe materials, it\\\'s both durable and kid-friendly. The vibrant colors and engaging design 🌈 captivate young imaginations, encouraging creative play and exploration. 🚀 Whether it\\\'s for solo adventures or group play, [Your Toy Name Here] is the perfect companion for making cherished memories. 💖 Easy to clean and store, it ensures hassle-free enjoyment for parents and kids alike. 🧼🧸 Get ready for smiles and laughter with this must-have addition to your toy collection! 😄✨', '[\"uploads\\/Frame 7 (2).png\",\"uploads\\/Frame 7 (1).png\",\"uploads\\/Frame 7.png\"]', '2024-12-16 16:22:34'),
(24, 'shampoo', 1000.00, 'dog shampoo', 'shelba', '20ml', 'white', 'Unleash the joy of playtime with [Your Toy Name Here]! 🎉 This delightful toy is designed to bring endless fun and excitement to children of all ages. 🧒👧 Crafted from high-quality, safe materials, it\\\\\\\'s both durable and kid-friendly. The vibrant colors and engaging design 🌈 captivate young imaginations, encouraging creative play and exploration. 🚀 Whether it\\\\\\\'s for solo adventures or group play, [Your Toy Name Here] is the perfect companion for making cherished memories. 💖 Easy to clean and store, it ensures hassle-free enjoyment for parents and kids alike. 🧼🧸 Get ready for smiles and laughter with this must-have addition to your toy collection! 😄✨', '[\"uploads/OIP.jpg\",\"uploads/1734627849_e44722a9-78f8-4802-ab5d-ea8dc97f4983_1.e418a93f099d1cee6f3472729bb1715f.jpeg\",\"uploads/1734627849_ca9b8466-e4ea-486f-a3ce-88768c9b13ea_1.0044a05e52842199d67cdcc8a897afa1.jpeg\"]', '2024-12-19 17:03:56'),
(32, 'brush', 1000.00, 'dog shampoo', 'shelba', '20ml', 'white', 'somrting.........', '[\"uploads\\/619Z8hhzmPL._SX522_.jpg\",\"uploads\\/71JUoxPHyVL._SX522_.jpg\"]', '2025-04-01 15:48:47'),
(33, 'brush', 1000.00, 'dog shampoo', 'shelba', '20ml', 'white', 'something.......', '[\"uploads\\/images (2).jpeg\",\"uploads\\/LECHAT-ADULT-CHICKEN-400G.jpg\"]', '2025-04-01 16:23:53'),
(34, 'brush', 1000.00, 'dog shampoo', 'shelba', '20ml', 'white', 'something ', '[\"uploads\\/LECHAT-ADULT-CHICKEN-400G.jpg\"]', '2025-04-01 16:25:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', 'karan8001#');

-- --------------------------------------------------------

--
-- Table structure for table `user_concerns`
--

CREATE TABLE `user_concerns` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `doctor_login`
--
ALTER TABLE `doctor_login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `ngo_details`
--
ALTER TABLE `ngo_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pet_management`
--
ALTER TABLE `pet_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pet_products`
--
ALTER TABLE `pet_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_concerns`
--
ALTER TABLE `user_concerns`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_login`
--
ALTER TABLE `doctor_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ngo_details`
--
ALTER TABLE `ngo_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `pet_management`
--
ALTER TABLE `pet_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `pet_products`
--
ALTER TABLE `pet_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_concerns`
--
ALTER TABLE `user_concerns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
