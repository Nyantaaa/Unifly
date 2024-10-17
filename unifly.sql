-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2024 at 05:18 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unifly`
--

-- --------------------------------------------------------

--
-- Table structure for table `airlines`
--

CREATE TABLE `airlines` (
  `airline_id` int(11) NOT NULL,
  `airline_name` varchar(255) NOT NULL,
  `airline_code` varchar(255) NOT NULL,
  `total_seats` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `airlines`
--

INSERT INTO `airlines` (`airline_id`, `airline_name`, `airline_code`, `total_seats`) VALUES
(1, 'Universe Airlines', 'UVA', 9),
(2, 'Supernova Airlines', 'SNA', 9),
(3, 'MilkyWay Airlines', 'MWA', 9),
(4, 'Nebula Airlines', 'NBA', 9),
(5, 'Earth Airlines', 'ERA', 9),
(6, 'Celestial Airlines', 'CLA', 9),
(7, 'Lunar Airlines', 'LNA', 9),
(8, 'Solar Airlines', 'SLA', 9);

--
-- Triggers `airlines`
--
DELIMITER $$
CREATE TRIGGER `AutomaticAirlineStatusDeleteAirlines` AFTER DELETE ON `airlines` FOR EACH ROW BEGIN
    UPDATE airlines_info
    SET airline_status = "Deleted"
    WHERE airline_name = OLD.airline_name;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `airlines_info`
--

CREATE TABLE `airlines_info` (
  `info_id` int(11) NOT NULL COMMENT 'ID Info',
  `airline_name` varchar(255) NOT NULL COMMENT 'Airline Name',
  `airline_code` varchar(255) NOT NULL COMMENT 'Airline Code',
  `destination` varchar(255) NOT NULL COMMENT 'Destination',
  `info_created` datetime NOT NULL COMMENT 'Created Time',
  `airline_status` varchar(255) NOT NULL COMMENT 'Created / Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `airlines_info`
--
DELIMITER $$
CREATE TRIGGER `AutomaticAirlineNewAirlinesInfo` AFTER INSERT ON `airlines_info` FOR EACH ROW BEGIN
	DECLARE new_airline_name, new_airline_code, new_destination CHAR;
    
    SET @new_airline_name = NEW.airline_name;
    SET @new_airline_code = NEW.airline_code;
    SET @new_destination = NEW.destination;
    
    INSERT INTO airlines (airline_name, airline_code, total_seats) VALUES
    (@new_airline_name, @new_airline_code, '9');
    
    SELECT airline_id INTO @new_airline_id FROM airlines WHERE airline_name = @new_airline_name;
    
   	INSERT INTO flights (airline_id, origin, destination, departure_time, arrival_time, price) VALUES
    (@new_airline_id, 'Tangerang', @new_destination, CURRENT_TIMESTAMP(), DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 2 HOUR), '100');
    
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `seat_id` int(11) NOT NULL,
  `booking_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `bookings`
--
DELIMITER $$
CREATE TRIGGER `AutomaticBookingStatusDeleteBookings` AFTER DELETE ON `bookings` FOR EACH ROW BEGIN
	DECLARE new_fullname, new_destination, new_seat_number CHAR;
    DECLARE old_user_id, old_flight_id, old_seat_id INT;
    
    SET @old_user_id = OLD.user_id;
    SET @old_flight_id = OLD.flight_id;
    SET @old_seat_id = OLD.seat_id;

	SELECT fullname INTO @new_fullname FROM users WHERE user_id = @old_user_id;
    
    SELECT destination INTO @new_destination FROM flights WHERE flight_id = @old_flight_id;
    
    SELECT seat_number INTO @new_seat_number FROM seats WHERE seat_id = @old_seat_id AND flight_id = @old_flight_id;

	UPDATE bookings_info SET booking_status = "Deleted" WHERE fullname = @new_fullname AND destination = @new_destination AND seat_number = @new_seat_number;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `AutomaticSeatAvailableDeleteBooking` AFTER DELETE ON `bookings` FOR EACH ROW BEGIN
    UPDATE seats
    SET is_available = 1
    WHERE seat_id = OLD.seat_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bookings_info`
--

CREATE TABLE `bookings_info` (
  `info_id` int(11) NOT NULL COMMENT 'ID Info',
  `fullname` varchar(255) NOT NULL COMMENT 'Fullname',
  `destination` varchar(255) NOT NULL COMMENT 'Destination',
  `seat_number` varchar(255) NOT NULL COMMENT 'Seat Number',
  `info_created` datetime NOT NULL COMMENT 'Created Time',
  `booking_status` varchar(255) NOT NULL COMMENT 'Created / Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `bookings_info`
--
DELIMITER $$
CREATE TRIGGER `AutomaticBookingNewBookingInfo` AFTER INSERT ON `bookings_info` FOR EACH ROW BEGIN
	DECLARE new_fullname, new_destination, new_seat_number CHAR;
    DECLARE new_user_id, new_flight_id, new_seat_id INT;
    
    SET @new_fullname = NEW.fullname;
    SET @new_destination = NEW.destination;
    SET @new_seat_number = NEW.seat_number;

	SELECT user_id INTO @new_user_id FROM users WHERE fullname = @new_fullname;
    
    SELECT flight_id INTO @new_flight_id FROM flights WHERE destination = @new_destination;
    
    SELECT seat_id INTO @new_seat_id FROM seats WHERE seat_number = @new_seat_number AND flight_id = @new_flight_id;
    
    UPDATE seats SET seats.is_available = 0 WHERE seats.seat_number = @new_seat_number AND seats.flight_id = @new_flight_id;
    
    INSERT INTO bookings (user_id, flight_id, seat_id, booking_date) VALUES
    (@new_user_id, @new_flight_id, @new_seat_id, CURRENT_TIMESTAMP());
    
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

CREATE TABLE `flights` (
  `flight_id` int(11) NOT NULL,
  `airline_id` int(11) NOT NULL,
  `origin` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `departure_time` datetime NOT NULL,
  `arrival_time` datetime NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`flight_id`, `airline_id`, `origin`, `destination`, `departure_time`, `arrival_time`, `price`) VALUES
(1, 1, 'Tangerang', 'Bandung', '2023-05-27 21:00:00', '2023-05-27 23:00:00', 825000),
(2, 2, 'Tangerang', 'Banjarbaru', '2023-05-27 14:00:00', '2023-05-27 16:00:00', 875000),
(3, 3, 'Tangerang', 'Padang', '2023-05-28 08:00:00', '2023-05-28 10:00:00', 800000),
(4, 4, 'Tangerang', 'Palembang', '2023-05-28 10:00:00', '2023-05-28 12:00:00', 850000),
(5, 5, 'Tangerang', 'Batam', '2023-05-28 13:00:00', '2023-05-28 15:00:00', 900000),
(6, 6, 'Tangerang', 'Balikpapan', '2023-05-29 09:00:00', '2023-05-29 11:00:00', 825000),
(7, 7, 'Tangerang', 'Manado', '2023-05-29 15:00:00', '2023-05-29 17:00:00', 925000),
(8, 8, 'Tangerang', 'Aceh', '2023-06-13 17:57:10', '2023-06-13 19:57:10', 900000);

--
-- Triggers `flights`
--
DELIMITER $$
CREATE TRIGGER `AutomaticSeatNewFlights` AFTER INSERT ON `flights` FOR EACH ROW BEGIN
	DECLARE new_flight_id INT;
    SET @new_flight_id = NEW.flight_id;

    INSERT INTO seats (seats.flight_id, seats.seat_number, seats.is_available) VALUES 
    (@new_flight_id, 'A1', '1' ),
    (@new_flight_id, 'A2', '1' ),
    (@new_flight_id, 'A3', '1' ),
    (@new_flight_id, 'B1', '1' ),
    (@new_flight_id, 'B2', '1' ),
    (@new_flight_id, 'B3', '1' ),
    (@new_flight_id, 'C1', '1' ),
    (@new_flight_id, 'C2', '1' ),
    (@new_flight_id, 'C3', '1' );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `seat_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `seat_number` varchar(255) NOT NULL,
  `is_available` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`seat_id`, `flight_id`, `seat_number`, `is_available`) VALUES
(1, 1, 'A1', 1),
(2, 1, 'A2', 1),
(3, 1, 'A3', 1),
(4, 1, 'B1', 1),
(5, 1, 'B2', 1),
(6, 1, 'B3', 1),
(7, 1, 'C1', 1),
(8, 1, 'C2', 1),
(9, 1, 'C3', 1),
(10, 2, 'A1', 1),
(11, 2, 'A2', 1),
(12, 2, 'A3', 1),
(13, 2, 'B1', 1),
(14, 2, 'B2', 1),
(15, 2, 'B3', 1),
(16, 2, 'C1', 1),
(17, 2, 'C2', 1),
(18, 2, 'C3', 1),
(19, 3, 'A1', 1),
(20, 3, 'A2', 1),
(21, 3, 'A3', 1),
(22, 3, 'B1', 1),
(23, 3, 'B2', 1),
(24, 3, 'B3', 1),
(25, 3, 'C1', 1),
(26, 3, 'C2', 1),
(27, 3, 'C3', 1),
(28, 4, 'A1', 1),
(29, 4, 'A2', 1),
(30, 4, 'A3', 1),
(31, 4, 'B1', 1),
(32, 4, 'B2', 1),
(33, 4, 'B3', 1),
(34, 4, 'C1', 1),
(35, 4, 'C2', 1),
(36, 4, 'C3', 1),
(37, 5, 'A1', 1),
(38, 5, 'A2', 1),
(39, 5, 'A3', 1),
(40, 5, 'B1', 1),
(41, 5, 'B2', 1),
(42, 5, 'B3', 1),
(43, 5, 'C1', 1),
(44, 5, 'C2', 1),
(45, 5, 'C3', 1),
(46, 6, 'A1', 1),
(47, 6, 'A2', 1),
(48, 6, 'A3', 1),
(49, 6, 'B1', 1),
(50, 6, 'B2', 1),
(51, 6, 'B3', 1),
(52, 6, 'C1', 1),
(53, 6, 'C2', 1),
(54, 6, 'C3', 1),
(55, 7, 'A1', 1),
(56, 7, 'A2', 1),
(57, 7, 'A3', 1),
(58, 7, 'B1', 1),
(59, 7, 'B2', 1),
(60, 7, 'B3', 1),
(61, 7, 'C1', 1),
(62, 7, 'C2', 1),
(63, 7, 'C3', 1),
(64, 8, 'A1', 1),
(65, 8, 'A2', 1),
(66, 8, 'A3', 1),
(67, 8, 'B1', 1),
(68, 8, 'B2', 1),
(69, 8, 'B3', 1),
(70, 8, 'C1', 1),
(71, 8, 'C2', 1),
(72, 8, 'C3', 1);

--
-- Triggers `seats`
--
DELIMITER $$
CREATE TRIGGER `AutomaticAirlineUpdateTotalSeats` AFTER UPDATE ON `seats` FOR EACH ROW BEGIN
    -- Hanya dijalankan ketika is_available berubah dari 1 ke 0
    IF OLD.is_available = 1 AND NEW.is_available = 0 THEN
        
        -- Kurangi total_seats pada airlines
        UPDATE airlines
        SET total_seats = (
            SELECT COUNT(*) 
            FROM seats s
            JOIN flights f ON s.flight_id = f.flight_id
            WHERE s.is_available = 1 
            AND f.airline_id = (
                SELECT airline_id 
                FROM flights 
                WHERE flight_id = NEW.flight_id
            )
        )
        WHERE airline_id = (
            SELECT airline_id 
            FROM flights 
            WHERE flight_id = NEW.flight_id
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` bigint(20) NOT NULL COMMENT 'Session ID',
  `user_id` bigint(20) NOT NULL COMMENT 'User ID',
  `level` bigint(20) NOT NULL COMMENT 'Level',
  `accesstoken` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Access Token',
  `accesstokenexpiry` datetime NOT NULL COMMENT 'Access Token Expiry Date/Time',
  `refreshtoken` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Refresh Token',
  `refreshtokenexpiry` datetime NOT NULL COMMENT 'Refresh Token Expiry Date/Time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `level`, `accesstoken`, `accesstokenexpiry`, `refreshtoken`, `refreshtokenexpiry`) VALUES
(1, 1, 0, 'NWNmNTg1MWJjMDU3OGUyODQ4Y2I0NjUyMTQ2N2FlNjk0N2RkYjliMGM0NThiYzE4MTcyOTEwMDE0OQ==', '2024-10-17 00:55:49', 'OGY4ZTAxNmM4MDUwOGEzYTMzOGQzZGIyNjAxOTk5ZDRjODBmNjdiOGI5ZDhlOGMyMTcyOTEwMDE0OQ==', '2024-10-31 00:35:49'),
(2, 1, 0, 'MDA0MGJlZTI5NWI4N2Q2NzA0YTEwYzYyZWNjODg0ZTI3ODlhNGEwMTk4NDhmMDYyMTcyOTEzNDQ3OA==', '2025-03-05 07:27:58', 'ZTI2NjVhMGNlM2RhNzQwYTNkMzgwMTBmOWNmOTgyOTY3ODQzNDVjOGUwMWZiOTRkMTcyOTEzNDQ3OA==', '2024-10-31 10:07:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `fullname`, `address`, `telephone`, `level`) VALUES
(1, 'Jhin', 'Jhin', 'Jhin@gmail.com', 'Jhin Virtuoso', 'Jl. Summoner Rift No. 1', '081248675940', '0'),
(2, 'Nami', 'Nami', 'Nami@gmail.com', 'Nami Cosmic', 'Jl. Summoner Rift No. 2', '081248675941', '1'),
(3, 'johnDoe', 'password123', 'johndoe1@gmail.com', 'John Doe', 'Jalan Utama No. 123', '081234567890', '1'),
(4, 'janeSmith', 'abc123', 'janesmith2@gmail.com', 'Jane Smith', 'Jalan Elm No. 456', '085215432109', '1'),
(5, 'jamesBrown', 'qwerty', 'jamesbrown3@gmail.com', 'James Brown', 'Jalan Oak No. 789', '081215755514', '1'),
(6, 'ahmadRizky', 'r1zky!pass', 'ahmadrizky4@gmail.com', 'Ahmad Rizky', 'Jalan Merdeka No. 10', '081215972246', '1'),
(7, 'sitiAisyah', '123siti', 'sitiaisyah5@gmail.com', 'Siti Aisyah', 'Jalan Kenanga No. 7', '085678901234', '1'),
(8, 'budiSantoso', 'budi123', 'budisantoso6@gmail.com', 'Budi Santoso', 'Jalan Mawar No. 15', '081234567892', '1'),
(9, 'linaWijaya', 'lina456', 'linawijaya7@gmail.com', 'Lina Wijaya', 'Jalan Melati No. 20', '081334567893', '1'),
(10, 'agusPratama', 'agus789', 'aguspratama8@gmail.com', 'Agus Pratama', 'Jalan Surya No. 30', '081234567894', '1'),
(11, 'Willy', 'willy', 'willy@gmail.com', 'Willy Wijaya', 'Jl. Imam Bonjol No.41 Karawaci', '08202123123', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `airlines`
--
ALTER TABLE `airlines`
  ADD PRIMARY KEY (`airline_id`),
  ADD UNIQUE KEY `airline_name` (`airline_name`);

--
-- Indexes for table `airlines_info`
--
ALTER TABLE `airlines_info`
  ADD PRIMARY KEY (`info_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD UNIQUE KEY `seat_id` (`seat_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `flight_id` (`flight_id`);

--
-- Indexes for table `bookings_info`
--
ALTER TABLE `bookings_info`
  ADD PRIMARY KEY (`info_id`);

--
-- Indexes for table `flights`
--
ALTER TABLE `flights`
  ADD PRIMARY KEY (`flight_id`),
  ADD UNIQUE KEY `airline_id` (`airline_id`) USING BTREE,
  ADD UNIQUE KEY `destination` (`destination`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`seat_id`),
  ADD KEY `flight_id` (`flight_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accesstoken` (`accesstoken`),
  ADD UNIQUE KEY `refreshtoken` (`refreshtoken`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `airlines`
--
ALTER TABLE `airlines`
  MODIFY `airline_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `airlines_info`
--
ALTER TABLE `airlines_info`
  MODIFY `info_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Info', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings_info`
--
ALTER TABLE `bookings_info`
  MODIFY `info_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Info', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `flights`
--
ALTER TABLE `flights`
  MODIFY `flight_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Session ID', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`flight_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`seat_id`) ON DELETE CASCADE;

--
-- Constraints for table `flights`
--
ALTER TABLE `flights`
  ADD CONSTRAINT `flights_ibfk_1` FOREIGN KEY (`airline_id`) REFERENCES `airlines` (`airline_id`) ON DELETE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`flight_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
