-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 06 Wrz 2024, 12:31
-- Wersja serwera: 10.4.13-MariaDB
-- Wersja PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `restauracja`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `menu_items`
--

CREATE TABLE `menu_items` (
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) COLLATE utf8mb4_polish_ci NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text COLLATE utf8mb4_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `menu_items`
--

INSERT INTO `menu_items` (`item_id`, `category_id`, `item_name`, `price`, `description`) VALUES
(1, 1, 'Tatar wołowy', '40.00', NULL),
(2, 1, 'Carpaccio wołowe', '40.00', NULL),
(3, 1, 'Talerz serów', '30.00', NULL),
(4, 1, 'Tostowane pieczywo z awokado oraz jajkiem', '25.00', NULL),
(5, 1, 'Szparagi w sosie holenderskim', '25.00', NULL),
(6, 1, 'Sałatka z arbuzem i serem feta', '25.00', NULL),
(7, 1, 'Krewetki z czosnkiem i pieczywem', '35.00', NULL),
(8, 1, 'Ratatouille', '30.00', NULL),
(9, 2, 'Ketchup', '2.00', NULL),
(10, 2, 'Sour cream', '2.50', NULL),
(11, 2, 'Blue cheese', '2.50', NULL),
(12, 2, 'Sos czosnkowy', '2.00', NULL),
(13, 2, 'Różowy sos', '2.50', NULL),
(14, 3, 'Stek wieprzowy z ziemniakami i sałatką', '50.00', NULL),
(15, 3, 'Makaron zapiekany z serem', '40.00', NULL),
(16, 3, 'Makaron z krewetkami i chilli', '45.00', NULL),
(17, 3, 'Makaron z warzywami grillowanymi', '40.00', NULL),
(18, 3, 'Łosoś grillowany ze szparagami i ziemniakami pieczonymi', '75.00', NULL),
(19, 3, 'Żebra z frytkami i miksem sałat', '70.00', NULL),
(20, 3, 'Stek z antrykotu z frytkami i miksem sałat lub warzywami grillowanymi', '80.00', NULL),
(21, 3, 'Stek z polędwicy wołowej z frytkami i miksem sałat lub warzywami grillowanymi', '100.00', NULL),
(22, 4, 'Krem pomidorowy', '25.00', NULL),
(23, 4, 'Zupa cebulowa', '25.00', NULL),
(24, 4, 'Rosół wołowy', '30.00', NULL),
(25, 4, 'Krem ze szparagów', '30.00', NULL),
(26, 5, 'Sernik baskijski', '17.00', NULL),
(27, 5, 'Szarlotka', '15.00', NULL),
(28, 5, 'Tiramisu', '17.00', NULL),
(29, 5, 'Tarta z truskawką', '15.00', NULL),
(30, 6, 'Herbata czarna', '12.00', NULL),
(31, 6, 'Herbata zielona', '12.00', NULL),
(32, 6, 'Herbata miętowa', '12.00', NULL),
(33, 6, 'Herbata owocowa', '12.00', NULL),
(34, 6, 'Herbata biała', '12.00', NULL),
(35, 6, 'Herbata jaśminowa', '12.00', NULL),
(36, 6, 'Latte', '15.00', NULL),
(37, 6, 'Cappucino', '15.00', NULL),
(38, 6, 'Americano', '15.00', NULL),
(39, 6, 'Espresso', '10.00', NULL),
(40, 6, 'Woda', '8.00', NULL),
(41, 6, 'Sok jabłkowy', '12.00', NULL),
(42, 6, 'Sok pomarańczowy', '12.00', NULL),
(43, 6, 'Lemoniada domowa', '20.00', NULL),
(44, 6, 'Paulaner 0,0%', '18.00', NULL),
(45, 6, 'Kozel 0,0%', '18.00', NULL),
(46, 6, 'Paulaner Radler 0,0%', '18.00', NULL),
(47, 6, 'Coca-Cola', '17.00', NULL),
(48, 6, 'Coca-Cola Zero', '17.00', NULL),
(49, 6, 'Red Bull', '20.00', NULL),
(50, 6, 'Fanta', '17.00', NULL),
(51, 6, '7Up', '17.00', NULL),
(52, 6, 'San Pellegrino Aranciata', '20.00', NULL);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`item_id`);

CREATE TABLE `orders` (
  `order_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_address` TEXT NOT NULL,
  `total_amount` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

CREATE TABLE `order_items` (
  `order_item_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `price` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`order_id`),
  FOREIGN KEY (`item_id`) REFERENCES `menu_items`(`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

CREATE TABLE `customers` (
  `customer_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100),
  `phone` VARCHAR(15),
  `address` TEXT,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;


--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `item_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
