-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2023 at 09:54 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(5) NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `client_phone` varchar(50) NOT NULL,
  `client_email` varchar(100) NOT NULL,
  `client_password` varchar(100) NOT NULL,
  `client_address` varchar(40) NOT NULL,
  `client_city` varchar(15) NOT NULL,
  `client_zipcode` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `client_name`, `client_phone`, `client_email`,`client_password`,`client_address`,`client_city`,`client_zipcode`) VALUES
(1, 'Vincent', '1234567890', 'vincent.pizza@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','1580  Boone Street','Corpus Christi','78476'),
(2, 'Client 2', '2345678901', 'client1@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','1450 Harry Street','Houston','70090'),
(3, 'Client 3', '3456789012', 'client2@ygmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','1435 Dragon Street','Austin','78979'),
(4, 'Client 4', '4567890123', 'client3@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','4567 Hilton Street','San Antonio','75435'),
(5, 'Client 5', '5678901234', 'client4@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','5328 Marriot Street','Corpus Christi','76576'),
(6, 'Client 6', '6789012345', 'client5@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','5432 Butter Street','Dallas','76548'),
(7, 'Client 7', '7890123456', 'client6@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','9828 Horns Street','San Antonio','71092'),
(8, 'Client 8', '8901234567', 'client7@ygmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','0987 Columbia Street','Dallas','78963'),
(9, 'Client 9', '9012345678', 'client8@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','1435 Argon Street','Austin','71298');

-- --------------------------------------------------------

--
-- Table structure for table `image_gallery`
--

CREATE TABLE `image_gallery` (
  `image_id` int(2) NOT NULL,
  `image_name` varchar(30) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `image_gallery`
--

INSERT INTO `image_gallery` (`image_id`, `image_name`, `image`) VALUES
(1, 'Moroccan Tajine', '58146_Moroccan Chicken Tagine.jpeg'),
(2, 'Italian Pasta', 'img_1.jpg'),
(3, 'Cook', 'img_2.jpg'),
(4, 'Pizza', 'img_3.jpg'),
(5, 'Burger', 'burger.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `in_order`
--

CREATE TABLE `in_order` (
  `id` int(5) NOT NULL,
  `order_id` int(5) NOT NULL,
  `menu_id` int(5) NOT NULL,
  `client_id` int(5) NOT NULL,
  `quantity` int(3) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `in_order`
--

INSERT INTO `in_order` (`id`, `order_id`, `menu_id`,`client_id`, `quantity`) VALUES
(1, 10, 16,8, 1),
(2, 11, 12,9, 1),
(3, 11, 16,9, 1),
(4, 12, 11,7, 1),
(5, 12, 12,7, 1),
(6, 12, 16,7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `menu_id` int(5) NOT NULL,
  `menu_name` varchar(100) NOT NULL,
  `menu_description` varchar(255) NOT NULL,
  `menu_price` decimal(6,2) NOT NULL,
  `menu_image` varchar(255) NOT NULL,
  `category_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `menu_name`, `menu_description`, `menu_price`, `menu_image`, `category_id`) VALUES
(1, 'Moroccan Couscous', 'Moroccan couscous is a traditional dish consisting of fluffy semolina grains steamed to perfection, accompanied by a rich and aromatic stew of tender meat, vegetables, &amp; fragrant spices.', 14.00, '88737_couscous_meat.jpg', 8),
(2, 'Beef Hamburger', 'A hamburger, or better known as a burger, is a food consisting of a patty of ground meat, typically beef—placed inside a sliced bun or bread roll', 3.80, 'burger.jpeg', 1),
(3, 'Ice Cream', 'Ice cream is a frozen dessert typically made from milk or cream that has been flavoured with a sweetener and a spice, such as cocoa or vanilla, or with fruit, such as strawberries or peaches.', 7.50, 'summer-dessert-sweet-ice-cream.jpg', 2),
(5, 'Coffee', 'Coffee is a beverage brewed from roasted coffee beans.', 10.00, 'coffee.jpeg', 3),
(6, 'Ice Tea', 'Form of cold tea. Usually served with ice, maybe sweetened with sugar or syrup.', 3.20, '76643_ice_tea.jpg', 3),
(7, 'Bucatini', 'Bucatini, also known as perciatelli, is a thick spaghetti-like pasta with a hole running through the center.', 20.00, 'macaroni.jpeg', 4),
(8, 'Cannelloni', 'Cannelloni are a cylindrical type of egg-based stuffed pasta generally served baked with a filling and covered by a sauce in Italian cuisine', 10.00, 'cooked_pasta.jpeg', 4),
(9, 'Margherita', 'Pizza Margherita is a typical Neapolitan pizza, roundish in shape with a raised edge and garnished with hand-crushed peeled tomatoes, mozzarella , fresh basil leaves, and extra virgin olive oil.', 24.00, 'pizza.jpeg', 5),
(11, 'Moroccan Tajine', 'Moroccan tajine dishes are slow-cooked savory stews, typically made with sliced meat, poultry or fish together with vegetables or fruit', 20.00, '58146_Moroccan Chicken Tagine.jpeg', 8),
(12, 'Moroccan Bissara', 'Bissara is a traditional Moroccan dish made from dried split fava beans (also known as broad beans) that are cooked and blended into a smooth and flavorful soup.', 10.00, '61959_Bissara.jpg', 8),
(16, 'Couscous', 'Couscous is a traditional North African dish of small steamed granules of rolled semolina that is often served with a stew spooned on top.', 20.00, '76635_57738_w1024h768c1cx256cy192.jpg', 8);

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `category_id` int(3) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `menu_categories`
--

INSERT INTO `menu_categories` (`category_id`, `category_name`) VALUES
(1, 'burgers'),
(2, 'desserts'),
(3, 'drinks'),
(4, 'pasta'),
(5, 'pizzas'),
(6, 'salads'),
(8, 'Traditional Food');

-- --------------------------------------------------------

--
-- Table structure for table `placed_orders`
--

CREATE TABLE `placed_orders` (
  `order_id` int(5) NOT NULL,
  `order_time` datetime NOT NULL,
  `client_id` int(5) NOT NULL,
  `delivery_address` varchar(255) NOT NULL,
  `delivered` tinyint(1) NOT NULL DEFAULT 0,
  `canceled` tinyint(1) NOT NULL DEFAULT 0,
  `cancellation_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `placed_orders`
--

INSERT INTO `placed_orders` (`order_id`, `order_time`, `client_id`, `delivery_address`, `delivered`, `canceled`, `cancellation_reason`) VALUES
(7, '2020-06-22 12:01:00', 1, 'Vincent', 0, 1, 'Sorry! I changed my mind!'),
(8, '2020-06-23 06:07:00', 2, 'Chengdu, China', 0, 1, ''),
(9, '2020-06-24 16:40:00', 1, 'Vincent', 1, 0, NULL),
(10, '2023-07-01 04:02:00', 8, 'Bloc A', 0, 0, NULL),
(11, '2023-10-30 20:09:00', 9, 'Test testst asds', 0, 0, NULL),
(12, '2023-10-30 21:46:00', 7, 'tests sd', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(5) NOT NULL,
  `date_created` datetime NOT NULL,
  `client_id` int(5) NOT NULL,
  `selected_time` datetime NOT NULL,
  `nbr_guests` int(2) NOT NULL,
  `table_id` int(3) NOT NULL,
  `liberated` tinyint(1) NOT NULL DEFAULT 0,
  `canceled` tinyint(1) NOT NULL DEFAULT 0,
  `cancellation_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `date_created`, `client_id`, `selected_time`, `nbr_guests`, `table_id`, `liberated`, `canceled`, `cancellation_reason`) VALUES
(1, '2020-07-18 09:07:00', 1, '2020-07-30 09:07:00', 0, 1, 0, 0, NULL),
(2, '2020-07-18 09:11:00', 4, '2020-07-29 13:00:00', 4, 2, 0, 0, NULL),
(3, '2023-07-01 04:01:00', 5, '2023-07-02 05:00:00', 2, 3, 0, 0, NULL),
(4, '2023-10-30 20:03:00', 7, '2023-11-08 20:03:00', 1, 4, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `table_id` int(3) NOT NULL,
  `max_seating` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`table_id`,`max_seating`) VALUES
(1,4),
(2,4),
(3,2),
(4,6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(2) NOT NULL,
  `username` varchar(20) NOT NULL,
  `user_number` varchar(20),
  `email` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL DEFAULT 'f7c3bc1d808e04732adf679965ccc34ca7ae3441',
  `user_role` enum('manager','staff','admin') NOT NULL DEFAULT 'staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`,`user_number`, `email`, `password`,`user_role`) VALUES
(1, 'admin_name', '123456789', 'user_admin@gmail.com', '407c6798fe20fd5d75de4a233c156cc0fce510e3','admin'),
(2, 'manager_name', '234567891', 'manager@gmail.com', 'ea876aead0cd3fc00f5be18e88cea3883fc63315','manager'),
(3, 'staff_name', '345678900', 'staff@gmail.com', 'e8e4edec28bf86983273399a5c6c9065fc4104ee','staff');

-- --------------------------------------------------------

--
-- Table structure for table `website_settings`
--

CREATE TABLE `website_settings` (
  `option_id` int(5) NOT NULL,
  `option_name` varchar(255) NOT NULL,
  `option_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `website_settings`
--

INSERT INTO `website_settings` (`option_id`, `option_name`, `option_value`) VALUES
(1, 'restaurant_name', 'VINCENT PIZZA'),
(2, 'restaurant_email', 'vincent.pizza@gmail.com'),
(3, 'admin_email', 'admin_email@gmail.com'),
(4, 'restaurant_phonenumber', '088866777555'),
(5, 'restaurant_address', '1580  Boone Street, Corpus Christi, TX, 78476 - USA');

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(2) NOT NULL,
  `supplier_name` varchar(30) NOT NULL,
  `supplier_number` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `supplier_name`, `supplier_number`) VALUES
(1, 'Good and Gather', 123456789),
(2, 'Great Value', 234567890),
(3, 'Kirkland', 345678901),
(4, 'H-E-B', 456789012);

--
-- Table structure for table `supplier`
--

CREATE TABLE `inventory` (
  `grocery_id` int(2) NOT NULL,
  `grocery_name` varchar(30) NOT NULL,
  `grocery_price` decimal(10,2) NOT NULL,
  `quantity_left` decimal(10,2) NOT NULL,
  `supplier_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `inventory` (`grocery_id`, `grocery_name`, `grocery_price`, `quantity_left`, `supplier_id`) VALUES
(1, 'Cheese(lb)', 2.29, 4,1),
(2, 'Tomato Sauce(15oz)', 0.99,6,1),
(3, 'All Purpose Flour(lb)', 0.54,18,2),
(4, 'Pepperoni(6oz)', 2.59,7,4);


--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `review_id` int(2) NOT NULL,
  `menu_id` int(2) NOT NULL,
  `client_id` int(2) NOT NULL,
  `feedback` decimal(10,2) NOT NULL,
  `comments` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Table structure for table `feedback`
--

CREATE TABLE `payment` (
  `payment_id` int(2) NOT NULL,
  `time` int(2) NOT NULL,
  `amount` int(2) NOT NULL,
  `order_id` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `image_gallery`
--
ALTER TABLE `image_gallery`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `in_order`
--
ALTER TABLE `in_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu` (`menu_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `FK_menu_category_id` (`category_id`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `placed_orders`
--
ALTER TABLE `placed_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_client` (`client_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`table_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `website_settings`
--
ALTER TABLE `website_settings`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`grocery_id`),
  ADD KEY `fk_supplier` (`supplier_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_order` (`order_id`);


--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_client` (`client_id`),
  ADD KEY `fk_menu` (`menu_id`);
--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `image_gallery`
--
ALTER TABLE `image_gallery`
  MODIFY `image_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `in_order`
--
ALTER TABLE `in_order`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `menu_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `category_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `placed_orders`
--
ALTER TABLE `placed_orders`
  MODIFY `order_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `table_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `website_settings`
--
ALTER TABLE `website_settings`
  MODIFY `option_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `grocery_id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `review_id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(2) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `in_order`
--
ALTER TABLE `in_order`
  ADD CONSTRAINT `fk_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`);

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `FK_menu_category_id` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`category_id`);

--
-- Constraints for table `placed_orders`
--
ALTER TABLE `placed_orders`
  ADD CONSTRAINT `fk_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`),
  ADD CONSTRAINT `fk_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `placed_orders` (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
