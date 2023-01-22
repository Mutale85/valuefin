-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 22, 2023 at 10:28 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `valueFin`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` text NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(256) NOT NULL,
  `pass_w` text NOT NULL,
  `phonenumber` varchar(150) NOT NULL,
  `activate` enum('0','1') NOT NULL,
  `user_role` enum('superAdmin','admin','loanOfficer') NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `firstname`, `lastname`, `email`, `password`, `pass_w`, `phonenumber`, `activate`, `user_role`, `parent_id`) VALUES
(13, 'Mutale', 'Mutale', 'Mulenga', 'mutamuls@gmail.com', '$2y$10$TGpbkD.EBAzRTsDkUJ/CH.JlH4eN.vrxjfl/kCXCkL5j9g3ZfVLpC', 'RW1tYW51ZWwyMDE1', '+260977654619', '1', 'superAdmin', 6);

-- --------------------------------------------------------

--
-- Table structure for table `allowed_branches`
--

CREATE TABLE `allowed_branches` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `allowed_branches`
--

INSERT INTO `allowed_branches` (`id`, `staff_id`, `parent_id`, `branch_id`, `date_added`) VALUES
(1, 13, 6, 1, '2022-02-06 12:02:27');

-- --------------------------------------------------------

--
-- Table structure for table `borrowers_business_details`
--

CREATE TABLE `borrowers_business_details` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `loan_officers_id` int(11) NOT NULL,
  `borrower_id` text NOT NULL,
  `borrower_business` text DEFAULT NULL,
  `borrower_shop_number` text DEFAULT NULL,
  `borrower_products` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `borrowers_business_details`
--

INSERT INTO `borrowers_business_details` (`id`, `branch_id`, `parent_id`, `loan_officers_id`, `borrower_id`, `borrower_business`, `borrower_shop_number`, `borrower_products`) VALUES
(1, 1, 6, 13, '009988/09/1', 'OSABOX', ' 12 City market', 'Shoes, Duvets, Kids clothing');

-- --------------------------------------------------------

--
-- Table structure for table `borrowers_details`
--

CREATE TABLE `borrowers_details` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `loan_officer_id` int(11) NOT NULL,
  `borrower_photo` text NOT NULL,
  `borrower_title` text DEFAULT NULL,
  `borrower_firstname` text NOT NULL,
  `borrower_lastname` text NOT NULL,
  `borrower_gender` text NOT NULL,
  `borrower_id` text NOT NULL,
  `borrower_nrc_front` text DEFAULT NULL,
  `borrower_nrc_back` text DEFAULT NULL,
  `borrower_address` text DEFAULT NULL,
  `borrower_email` text NOT NULL,
  `borrower_phone` text NOT NULL,
  `borrower_dateofbirth` date NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `borrowers_details`
--

INSERT INTO `borrowers_details` (`id`, `branch_id`, `parent_id`, `loan_officer_id`, `borrower_photo`, `borrower_title`, `borrower_firstname`, `borrower_lastname`, `borrower_gender`, `borrower_id`, `borrower_nrc_front`, `borrower_nrc_back`, `borrower_address`, `borrower_email`, `borrower_phone`, `borrower_dateofbirth`, `date_added`) VALUES
(1, 1, 6, 13, 'namecheap_.png', 'Miss', 'Morin', 'simboni', 'Female', '009988/09/1', '', '', ' House number 34. Lusaka', 'lungowe@gmail.com', '976330091', '1985-07-09', '2023-01-20 02:09:29');

-- --------------------------------------------------------

--
-- Table structure for table `borrowers_files`
--

CREATE TABLE `borrowers_files` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `borrower_branches`
--

CREATE TABLE `borrower_branches` (
  `id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `borrower_next_of_kin_details`
--

CREATE TABLE `borrower_next_of_kin_details` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `loan_officers_id` int(11) NOT NULL,
  `borrower_id` text NOT NULL,
  `next_of_kin_fullnames` text DEFAULT NULL,
  `next_of_kin_nrc` text DEFAULT NULL,
  `next_of_kin_phone` text DEFAULT NULL,
  `next_of_kin_relationship` text DEFAULT NULL,
  `next_of_kin_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `borrower_next_of_kin_details`
--

INSERT INTO `borrower_next_of_kin_details` (`id`, `branch_id`, `parent_id`, `loan_officers_id`, `borrower_id`, `next_of_kin_fullnames`, `next_of_kin_nrc`, `next_of_kin_phone`, `next_of_kin_relationship`, `next_of_kin_address`) VALUES
(1, 1, 6, 13, '009988/09/1', 'Sinkala Katongo', '4356762/99/1', '0977322123', 'Brother', 'Lusaka');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `branch_unique_id` varchar(100) NOT NULL,
  `member_id` int(11) NOT NULL,
  `branch_name` text NOT NULL,
  `open_date` date NOT NULL,
  `address` text NOT NULL,
  `city` text NOT NULL,
  `country` int(11) NOT NULL,
  `phone_landline` text NOT NULL,
  `phone_mobile` text NOT NULL,
  `currency` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_unique_id`, `member_id`, `branch_name`, `open_date`, `address`, `city`, `country`, `phone_landline`, `phone_mobile`, `currency`) VALUES
(1, '1000', 6, 'Lusaka', '2022-02-01', 'Roma', 'Lusaka', 265, '0977644619', '', 'ZMW');

-- --------------------------------------------------------

--
-- Table structure for table `collaterals`
--

CREATE TABLE `collaterals` (
  `id` int(11) NOT NULL,
  `collateral_type` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `loan_number` text DEFAULT NULL,
  `borrower_id` int(11) NOT NULL,
  `product_name` text NOT NULL,
  `register_date` date NOT NULL,
  `product_value` decimal(10,2) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `product_location` text NOT NULL,
  `action_date` text DEFAULT NULL,
  `address` text NOT NULL,
  `serial_number` text DEFAULT NULL,
  `model_name` text DEFAULT NULL,
  `model_number` text DEFAULT NULL,
  `color` text DEFAULT NULL,
  `manufature_date` text DEFAULT NULL,
  `product_condition` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `files` text DEFAULT NULL,
  `vehicle_reg_number` text DEFAULT NULL,
  `millage` text DEFAULT NULL,
  `vehicle_engine_num` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `collaterals_files`
--

CREATE TABLE `collaterals_files` (
  `id` int(11) NOT NULL,
  `collateral_id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `filename` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `collected_funds`
--

CREATE TABLE `collected_funds` (
  `id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `loan_number` text NOT NULL,
  `currency` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `collected_by` text NOT NULL,
  `month` text NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_id` int(5) NOT NULL,
  `country_name` varchar(20) NOT NULL,
  `code` varchar(2) NOT NULL,
  `dial_code` varchar(5) NOT NULL,
  `currency_name` varchar(20) NOT NULL,
  `currency_symbol` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `currency_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_id`, `country_name`, `code`, `dial_code`, `currency_name`, `currency_symbol`, `currency_code`) VALUES
(1, 'Afghanistan', 'AF', '+93', 'Afghan afghani', '؋', 'AFN'),
(3, 'Albania', 'AL', '+355', 'Albanian lek', 'L', 'ALL'),
(4, 'Algeria', 'DZ', '+213', 'Algerian dinar', 'د.ج', 'DZD'),
(5, 'AmericanSamoa', 'AS', '+1684', 'afghani', '؋.', 'AFN'),
(6, 'Andorra', 'AD', '+376', 'Euro', '€', 'EUR'),
(7, 'Angola', 'AO', '+244', 'Angolan kwanza', 'Kz', 'AOA'),
(8, 'Anguilla', 'AI', '+1264', 'East Caribbean dolla', '$', 'XCD'),
(9, 'Antarctica', 'AQ', '+672', '', '', ''),
(10, 'Antigua and Barbuda', 'AG', '+1268', 'East Caribbean dolla', '$', 'XCD'),
(11, 'Argentina', 'AR', '+54', 'Argentine peso', '$', 'ARS'),
(12, 'Armenia', 'AM', '+374', 'Armenian dram', 'դր', 'AMD'),
(13, 'Aruba', 'AW', '+297', 'Aruban florin', 'ƒ', 'AWG'),
(14, 'Australia', 'AU', '+61', 'Australian dollar', '$', 'AUD'),
(15, 'Austria', 'AT', '+43', 'Euro', '€', 'EUR'),
(16, 'Azerbaijan', 'AZ', '+994', 'Azerbaijani manat', '₼', 'AZN'),
(17, 'Bahamas', 'BS', '+1242', 'Bahamian dollar', 'B$', 'BSD'),
(18, 'Bahrain', 'BH', '+973', 'Bahraini dinar', '.د.ب', 'BHD'),
(19, 'Bangladesh', 'BD', '+880', 'Bangladeshi taka', '৳', 'BDT'),
(20, 'Barbados', 'BB', '+1246', 'Barbadian dollar', '$', 'BBD'),
(21, 'Belarus', 'BY', '+375', 'Belarusian ruble', 'Br', 'BYR'),
(22, 'Belgium', 'BE', '+32', 'Euro', '€', 'EUR'),
(23, 'Belize', 'BZ', '+501', 'Belize dollar', '$', 'BZD'),
(24, 'Benin', 'BJ', '+229', 'West African CFA fra', 'Fr', 'XOF'),
(25, 'Bermuda', 'BM', '+1441', 'Bermudian dollar', '$', 'BMD'),
(26, 'Bhutan', 'BT', '+975', 'Bhutanese ngultrum', 'Nu.', 'BTN'),
(27, 'Bolivia, Plurination', 'BO', '+591', '', '', ''),
(28, 'Bosnia and Herzegovi', 'BA', '+387', '', '', ''),
(29, 'Botswana', 'BW', '+267', 'Botswana pula', 'P', 'BWP'),
(30, 'Brazil', 'BR', '+55', 'Brazilian real', 'R$', 'BRL'),
(31, 'British Indian Ocean', 'IO', '+246', '', '', ''),
(32, 'Brunei Darussalam', 'BN', '+673', '', '', ''),
(33, 'Bulgaria', 'BG', '+359', 'Bulgarian lev', 'лв', 'BGN'),
(34, 'Burkina Faso', 'BF', '+226', 'West African CFA fra', 'Fr', 'XOF'),
(35, 'Burundi', 'BI', '+257', 'Burundian franc', 'Fr', 'BIF'),
(36, 'Cambodia', 'KH', '+855', 'Cambodian riel', '៛', 'KHR'),
(37, 'Cameroon', 'CM', '+237', 'Central African CFA ', 'Fr', 'XAF'),
(38, 'Canada', 'CA', '+1', 'Canadian dollar', '$', 'CAD'),
(39, 'Cape Verde', 'CV', '+238', 'Cape Verdean escudo', 'Esc or $', 'CVE'),
(40, 'Cayman Islands', 'KY', '+ 345', 'Cayman Islands dolla', '$', 'KYD'),
(41, 'Central African Repu', 'CF', '+236', '', '', ''),
(42, 'Chad', 'TD', '+235', 'Central African CFA ', 'Fr', 'XAF'),
(43, 'Chile', 'CL', '+56', 'Chilean peso', '$', 'CLP'),
(44, 'China', 'CN', '+86', 'Chinese yuan', '¥ or 元', 'CNY'),
(45, 'Christmas Island', 'CX', '+61', '', '', ''),
(46, 'Cocos (Keeling) Isla', 'CC', '+61', '', '', ''),
(47, 'Colombia', 'CO', '+57', 'Colombian peso', '$', 'COP'),
(48, 'Comoros', 'KM', '+269', 'Comorian franc', 'Fr', 'KMF'),
(49, 'Congo', 'CG', '+242', '', '', ''),
(50, 'Congo, The Democrati', 'CD', '+243', '', '', ''),
(51, 'Cook Islands', 'CK', '+682', 'New Zealand dollar', '$', 'NZD'),
(52, 'Costa Rica', 'CR', '+506', 'Costa Rican colón', '₡', 'CRC'),
(53, 'Ivory Coast', 'CI', '+225', 'West African CFA fra', 'Fr', 'XOF'),
(54, 'Croatia', 'HR', '+385', 'Croatian kuna', 'kn', 'HRK'),
(55, 'Cuba', 'CU', '+53', 'Cuban convertible pe', '$', 'CUC'),
(56, 'Cyprus', 'CY', '+357', 'Euro', '€', 'EUR'),
(57, 'Czech Republic', 'CZ', '+420', 'Czech koruna', 'Kč', 'CZK'),
(58, 'Denmark', 'DK', '+45', 'Danish krone', 'kr', 'DKK'),
(59, 'Djibouti', 'DJ', '+253', 'Djiboutian franc', 'Fr', 'DJF'),
(60, 'Dominica', 'DM', '+1767', 'East Caribbean dolla', '$', 'XCD'),
(61, 'Dominican Republic', 'DO', '+1849', 'Dominican peso', '$', 'DOP'),
(62, 'Ecuador', 'EC', '+593', 'United States dollar', '$', 'USD'),
(63, 'Egypt', 'EG', '+20', 'Egyptian pound', '£ or ج.م', 'EGP'),
(64, 'El Salvador', 'SV', '+503', 'United States dollar', '$', 'USD'),
(65, 'Equatorial Guinea', 'GQ', '+240', 'Central African CFA ', 'Fr', 'XAF'),
(66, 'Eritrea', 'ER', '+291', 'Eritrean nakfa', 'Nfk', 'ERN'),
(67, 'Estonia', 'EE', '+372', 'Euro', '€', 'EUR'),
(68, 'Ethiopia', 'ET', '+251', 'Ethiopian birr', 'Br', 'ETB'),
(69, 'Falkland Islands (Ma', 'FK', '+500', '', '', ''),
(70, 'Faroe Islands', 'FO', '+298', 'Danish krone', 'kr', 'DKK'),
(71, 'Fiji', 'FJ', '+679', 'Fijian dollar', '$', 'FJD'),
(72, 'Finland', 'FI', '+358', 'Euro', '€', 'EUR'),
(73, 'France', 'FR', '+33', 'Euro', '€', 'EUR'),
(74, 'French Guiana', 'GF', '+594', '', '', ''),
(75, 'French Polynesia', 'PF', '+689', 'CFP franc', 'Fr', 'XPF'),
(76, 'Gabon', 'GA', '+241', 'Central African CFA ', 'Fr', 'XAF'),
(77, 'Gambia', 'GM', '+220', '', '', ''),
(78, 'Georgia', 'GE', '+995', 'Georgian lari', 'ლ', 'GEL'),
(79, 'Germany', 'DE', '+49', 'Euro', '€', 'EUR'),
(80, 'Ghana', 'GH', '+233', 'Ghana cedi', '₵', 'GHS'),
(81, 'Gibraltar', 'GI', '+350', 'Gibraltar pound', '£', 'GIP'),
(82, 'Greece', 'GR', '+30', 'Euro', '€', 'EUR'),
(83, 'Greenland', 'GL', '+299', '', '', ''),
(84, 'Grenada', 'GD', '+1473', 'East Caribbean dolla', '$', 'XCD'),
(85, 'Guadeloupe', 'GP', '+590', '', '', ''),
(86, 'Guam', 'GU', '+1671', '', '', ''),
(87, 'Guatemala', 'GT', '+502', 'Guatemalan quetzal', 'Q', 'GTQ'),
(88, 'Guernsey', 'GG', '+44', 'British pound', '£', 'GBP'),
(89, 'Guinea', 'GN', '+224', 'Guinean franc', 'Fr', 'GNF'),
(90, 'Guinea-Bissau', 'GW', '+245', 'West African CFA fra', 'Fr', 'XOF'),
(91, 'Guyana', 'GY', '+595', 'Guyanese dollar', '$', 'GYD'),
(92, 'Haiti', 'HT', '+509', 'Haitian gourde', 'G', 'HTG'),
(93, 'Holy See (Vatican Ci', 'VA', '+379', '', '', ''),
(94, 'Honduras', 'HN', '+504', 'Honduran lempira', 'L', 'HNL'),
(95, 'Hong Kong', 'HK', '+852', 'Hong Kong dollar', '$', 'HKD'),
(96, 'Hungary', 'HU', '+36', 'Hungarian forint', 'Ft', 'HUF'),
(97, 'Iceland', 'IS', '+354', 'Icelandic króna', 'kr', 'ISK'),
(98, 'India', 'IN', '+91', 'Indian rupee', '₹', 'INR'),
(99, 'Indonesia', 'ID', '+62', 'Indonesian rupiah', 'Rp', 'IDR'),
(100, 'Iran, Islamic Republ', 'IR', '+98', '', '', ''),
(101, 'Iraq', 'IQ', '+964', 'Iraqi dinar', 'ع.د', 'IQD'),
(102, 'Ireland', 'IE', '+353', 'Euro', '€', 'EUR'),
(103, 'Isle of Man', 'IM', '+44', 'British pound', '£', 'GBP'),
(104, 'Israel', 'IL', '+972', 'Israeli new shekel', '₪', 'ILS'),
(105, 'Italy', 'IT', '+39', 'Euro', '€', 'EUR'),
(106, 'Jamaica', 'JM', '+1876', 'Jamaican dollar', '$', 'JMD'),
(107, 'Japan', 'JP', '+81', 'Japanese yen', '¥', 'JPY'),
(108, 'Jersey', 'JE', '+44', 'British pound', '£', 'GBP'),
(109, 'Jordan', 'JO', '+962', 'Jordanian dinar', 'د.ا', 'JOD'),
(110, 'Kazakhstan', 'KZ', '+7 7', 'Kazakhstani tenge', '', 'KZT'),
(111, 'Kenya', 'KE', '+254', 'Kenyan shilling', 'Sh', 'KES'),
(112, 'Kiribati', 'KI', '+686', 'Australian dollar', '$', 'AUD'),
(113, 'Korea, Democratic Pe', 'KP', '+850', '', '', ''),
(114, 'Korea, Republic of S', 'KR', '+82', '', '', ''),
(115, 'Kuwait', 'KW', '+965', 'Kuwaiti dinar', 'د.ك', 'KWD'),
(116, 'Kyrgyzstan', 'KG', '+996', 'Kyrgyzstani som', 'лв', 'KGS'),
(117, 'Laos', 'LA', '+856', 'Lao kip', '₭', 'LAK'),
(118, 'Latvia', 'LV', '+371', 'Euro', '€', 'EUR'),
(119, 'Lebanon', 'LB', '+961', 'Lebanese pound', 'ل.ل', 'LBP'),
(120, 'Lesotho', 'LS', '+266', 'Lesotho loti', 'L', 'LSL'),
(121, 'Liberia', 'LR', '+231', 'Liberian dollar', '$', 'LRD'),
(122, 'Libyan Arab Jamahiri', 'LY', '+218', '', '', ''),
(123, 'Liechtenstein', 'LI', '+423', 'Swiss franc', 'Fr', 'CHF'),
(124, 'Lithuania', 'LT', '+370', 'Euro', '€', 'EUR'),
(125, 'Luxembourg', 'LU', '+352', 'Euro', '€', 'EUR'),
(126, 'Macao', 'MO', '+853', '', '', ''),
(127, 'Macedonia', 'MK', '+389', '', '', ''),
(128, 'Madagascar', 'MG', '+261', 'Malagasy ariary', 'Ar', 'MGA'),
(129, 'Malawi', 'MW', '+265', 'Malawian kwacha', 'MK', 'MWK'),
(130, 'Malaysia', 'MY', '+60', 'Malaysian ringgit', 'RM', 'MYR'),
(131, 'Maldives', 'MV', '+960', 'Maldivian rufiyaa', '.ރ', 'MVR'),
(132, 'Mali', 'ML', '+223', 'West African CFA fra', 'Fr', 'XOF'),
(133, 'Malta', 'MT', '+356', 'Euro', '€', 'EUR'),
(134, 'Marshall Islands', 'MH', '+692', 'United States dollar', '$', 'USD'),
(135, 'Martinique', 'MQ', '+596', '', '', ''),
(136, 'Mauritania', 'MR', '+222', 'Mauritanian ouguiya', 'UM', 'MRO'),
(137, 'Mauritius', 'MU', '+230', 'Mauritian rupee', '₨', 'MUR'),
(138, 'Mayotte', 'YT', '+262', '', '', ''),
(139, 'Mexico', 'MX', '+52', 'Mexican peso', '$', 'MXN'),
(140, 'Micronesia, Federate', 'FM', '+691', '', '', ''),
(141, 'Moldova', 'MD', '+373', 'Moldovan leu', 'L', 'MDL'),
(142, 'Monaco', 'MC', '+377', 'Euro', '€', 'EUR'),
(143, 'Mongolia', 'MN', '+976', 'Mongolian tögrög', '₮', 'MNT'),
(144, 'Montenegro', 'ME', '+382', 'Euro', '€', 'EUR'),
(145, 'Montserrat', 'MS', '+1664', 'East Caribbean dolla', '$', 'XCD'),
(146, 'Morocco', 'MA', '+212', 'Moroccan dirham', 'د.م.', 'MAD'),
(147, 'Mozambique', 'MZ', '+258', 'Mozambican metical', 'MT', 'MZN'),
(148, 'Myanmar', 'MM', '+95', 'Burmese kyat', 'Ks', 'MMK'),
(149, 'Namibia', 'NA', '+264', 'Namibian dollar', '$', 'NAD'),
(150, 'Nauru', 'NR', '+674', 'Australian dollar', '$', 'AUD'),
(151, 'Nepal', 'NP', '+977', 'Nepalese rupee', '₨', 'NPR'),
(152, 'Netherlands', 'NL', '+31', 'Euro', '€', 'EUR'),
(153, 'Netherlands Antilles', 'AN', '+599', '', '', ''),
(154, 'New Caledonia', 'NC', '+687', 'CFP franc', 'Fr', 'XPF'),
(155, 'New Zealand', 'NZ', '+64', 'New Zealand dollar', '$', 'NZD'),
(156, 'Nicaragua', 'NI', '+505', 'Nicaraguan córdoba', 'C$', 'NIO'),
(157, 'Niger', 'NE', '+227', 'West African CFA fra', 'Fr', 'XOF'),
(158, 'Nigeria', 'NG', '+234', 'Nigerian naira', '₦', 'NGN'),
(159, 'Niue', 'NU', '+683', 'New Zealand dollar', '$', 'NZD'),
(160, 'Norfolk Island', 'NF', '+672', '', '', ''),
(161, 'Northern Mariana Isl', 'MP', '+1670', '', '', ''),
(162, 'Norway', 'NO', '+47', 'Norwegian krone', 'kr', 'NOK'),
(163, 'Oman', 'OM', '+968', 'Omani rial', 'ر.ع.', 'OMR'),
(164, 'Pakistan', 'PK', '+92', 'Pakistani rupee', '₨', 'PKR'),
(165, 'Palau', 'PW', '+680', 'Palauan dollar', '$', ''),
(166, 'Palestinian Territor', 'PS', '+970', '', '', ''),
(167, 'Panama', 'PA', '+507', 'Panamanian balboa', 'B/.', 'PAB'),
(168, 'Papua New Guinea', 'PG', '+675', 'Papua New Guinean ki', 'K', 'PGK'),
(169, 'Paraguay', 'PY', '+595', 'Paraguayan guaraní', '₲', 'PYG'),
(170, 'Peru', 'PE', '+51', 'Peruvian nuevo sol', 'S/.', 'PEN'),
(171, 'Philippines', 'PH', '+63', 'Philippine peso', '₱', 'PHP'),
(172, 'Pitcairn', 'PN', '+872', '', '', ''),
(173, 'Poland', 'PL', '+48', 'Polish z?oty', 'zł', 'PLN'),
(174, 'Portugal', 'PT', '+351', 'Euro', '€', 'EUR'),
(175, 'Puerto Rico', 'PR', '+1939', '', '', ''),
(176, 'Qatar', 'QA', '+974', 'Qatari riyal', 'ر.ق', 'QAR'),
(177, 'Romania', 'RO', '+40', 'Romanian leu', 'lei', 'RON'),
(178, 'Russia', 'RU', '+7', 'Russian ruble', '', 'RUB'),
(179, 'Rwanda', 'RW', '+250', 'Rwandan franc', 'Fr', 'RWF'),
(180, 'Reunion', 'RE', '+262', '', '', ''),
(181, 'Saint Barthelemy', 'BL', '+590', '', '', ''),
(182, 'Saint Helena, Ascens', 'SH', '+290', '', '', ''),
(183, 'Saint Kitts and Nevi', 'KN', '+1869', '', '', ''),
(184, 'Saint Lucia', 'LC', '+1758', 'East Caribbean dolla', '$', 'XCD'),
(185, 'Saint Martin', 'MF', '+590', '', '', ''),
(186, 'Saint Pierre and Miq', 'PM', '+508', '', '', ''),
(187, 'Saint Vincent and th', 'VC', '+1784', '', '', ''),
(188, 'Samoa', 'WS', '+685', 'Samoan t?l?', 'T', 'WST'),
(189, 'San Marino', 'SM', '+378', 'Euro', '€', 'EUR'),
(190, 'Sao Tome and Princip', 'ST', '+239', '', '', ''),
(191, 'Saudi Arabia', 'SA', '+966', 'Saudi riyal', 'ر.س', 'SAR'),
(192, 'Senegal', 'SN', '+221', 'West African CFA fra', 'Fr', 'XOF'),
(193, 'Serbia', 'RS', '+381', 'Serbian dinar', 'дин. or din.', 'RSD'),
(194, 'Seychelles', 'SC', '+248', 'Seychellois rupee', '₨', 'SCR'),
(195, 'Sierra Leone', 'SL', '+232', 'Sierra Leonean leone', 'Le', 'SLL'),
(196, 'Singapore', 'SG', '+65', 'Brunei dollar', '$', 'BND'),
(197, 'Slovakia', 'SK', '+421', 'Euro', '€', 'EUR'),
(198, 'Slovenia', 'SI', '+386', 'Euro', '€', 'EUR'),
(199, 'Solomon Islands', 'SB', '+677', 'Solomon Islands doll', '$', 'SBD'),
(200, 'Somalia', 'SO', '+252', 'Somali shilling', 'Sh', 'SOS'),
(201, 'South Africa', 'ZA', '+27', 'South African rand', 'R', 'ZAR'),
(202, 'South Georgia and th', 'GS', '+500', '', '', ''),
(203, 'Spain', 'ES', '+34', 'Euro', '€', 'EUR'),
(204, 'Sri Lanka', 'LK', '+94', 'Sri Lankan rupee', 'Rs or රු', 'LKR'),
(205, 'Sudan', 'SD', '+249', 'Sudanese pound', 'ج.س.', 'SDG'),
(206, 'Suriname', 'SR', '+597', 'Surinamese dollar', '$', 'SRD'),
(207, 'Svalbard and Jan May', 'SJ', '+47', '', '', ''),
(208, 'Swaziland', 'SZ', '+268', 'Swazi lilangeni', 'L', 'SZL'),
(209, 'Sweden', 'SE', '+46', 'Swedish krona', 'kr', 'SEK'),
(210, 'Switzerland', 'CH', '+41', 'Swiss franc', 'Fr', 'CHF'),
(211, 'Syrian Arab Republic', 'SY', '+963', '', '', ''),
(212, 'Taiwan', 'TW', '+886', 'New Taiwan dollar', '$', 'TWD'),
(213, 'Tajikistan', 'TJ', '+992', 'Tajikistani somoni', 'ЅМ', 'TJS'),
(214, 'Tanzania, United Rep', 'TZ', '+255', '', '', ''),
(215, 'Thailand', 'TH', '+66', 'Thai baht', '฿', 'THB'),
(216, 'Timor-Leste', 'TL', '+670', '', '', ''),
(217, 'Togo', 'TG', '+228', 'West African CFA fra', 'Fr', 'XOF'),
(218, 'Tokelau', 'TK', '+690', '', '', ''),
(219, 'Tonga', 'TO', '+676', 'Tongan pa?anga', 'T$', 'TOP'),
(220, 'Trinidad and Tobago', 'TT', '+1868', 'Trinidad and Tobago ', '$', 'TTD'),
(221, 'Tunisia', 'TN', '+216', 'Tunisian dinar', 'د.ت', 'TND'),
(222, 'Turkey', 'TR', '+90', 'Turkish lira', '', 'TRY'),
(223, 'Turkmenistan', 'TM', '+993', 'Turkmenistan manat', 'm', 'TMT'),
(224, 'Turks and Caicos Isl', 'TC', '+1649', '', '', ''),
(225, 'Tuvalu', 'TV', '+688', 'Australian dollar', '$', 'AUD'),
(226, 'Uganda', 'UG', '+256', 'Ugandan shilling', 'Sh', 'UGX'),
(227, 'Ukraine', 'UA', '+380', 'Ukrainian hryvnia', '₴', 'UAH'),
(228, 'United Arab Emirates', 'AE', '+971', 'United Arab Emirates', 'د.إ', 'AED'),
(229, 'United Kingdom', 'GB', '+44', 'British pound', '£', 'GBP'),
(230, 'United States', 'US', '+1', 'United States dollar', '$', 'USD'),
(231, 'Uruguay', 'UY', '+598', 'Uruguayan peso', '$', 'UYU'),
(232, 'Uzbekistan', 'UZ', '+998', 'Uzbekistani som', '', 'UZS'),
(233, 'Vanuatu', 'VU', '+678', 'Vanuatu vatu', 'Vt', 'VUV'),
(234, 'Venezuela, Bolivaria', 'VE', '+58', '', '', ''),
(235, 'Vietnam', 'VN', '+84', 'Vietnamese ??ng', '₫', 'VND'),
(236, 'Virgin Islands, Brit', 'VG', '+1284', '', '', ''),
(237, 'Virgin Islands, U.S.', 'VI', '+1340', '', '', ''),
(238, 'Wallis and Futuna', 'WF', '+681', 'CFP franc', 'Fr', 'XPF'),
(239, 'Yemen', 'YE', '+967', 'Yemeni rial', '﷼', 'YER'),
(240, 'Zambia', 'ZM', '+260', 'Zambian kwacha', 'ZK', 'ZMW'),
(241, 'Zimbabwe', 'ZW', '+263', 'Botswana pula', 'P', 'BWP');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `currency` varchar(100) DEFAULT NULL,
  `code` varchar(4) DEFAULT NULL,
  `minor_unit` smallint(6) DEFAULT NULL,
  `symbol` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `country`, `currency`, `code`, `minor_unit`, `symbol`) VALUES
(1, 'Afghanistan', 'Afghani', 'AFN', 2, '؋'),
(2, 'Åland Islands', 'Euro', 'EUR', 2, '€'),
(3, 'Albania', 'Lek', 'ALL', 2, 'Lek'),
(4, 'Algeria', 'Algerian Dinar', 'DZD', 2, NULL),
(5, 'American Samoa', 'US Dollar', 'USD', 2, '$'),
(6, 'Andorra', 'Euro', 'EUR', 2, '€'),
(7, 'Angola', 'Kwanza', 'AOA', 2, NULL),
(8, 'Anguilla', 'East Caribbean Dollar', 'XCD', 2, NULL),
(9, 'Antigua And Barbuda', 'East Caribbean Dollar', 'XCD', 2, NULL),
(10, 'Argentina', 'Argentine Peso', 'ARS', 2, '$'),
(11, 'Armenia', 'Armenian Dram', 'AMD', 2, NULL),
(12, 'Aruba', 'Aruban Florin', 'AWG', 2, NULL),
(13, 'Australia', 'Australian Dollar', 'AUD', 2, '$'),
(14, 'Austria', 'Euro', 'EUR', 2, '€'),
(15, 'Azerbaijan', 'Azerbaijan Manat', 'AZN', 2, NULL),
(16, 'Bahamas', 'Bahamian Dollar', 'BSD', 2, '$'),
(17, 'Bahrain', 'Bahraini Dinar', 'BHD', 3, NULL),
(18, 'Bangladesh', 'Taka', 'BDT', 2, '৳'),
(19, 'Barbados', 'Barbados Dollar', 'BBD', 2, '$'),
(20, 'Belarus', 'Belarusian Ruble', 'BYN', 2, NULL),
(21, 'Belgium', 'Euro', 'EUR', 2, '€'),
(22, 'Belize', 'Belize Dollar', 'BZD', 2, 'BZ$'),
(23, 'Benin', 'CFA Franc BCEAO', 'XOF', 0, NULL),
(24, 'Bermuda', 'Bermudian Dollar', 'BMD', 2, NULL),
(25, 'Bhutan', 'Indian Rupee', 'INR', 2, '₹'),
(26, 'Bhutan', 'Ngultrum', 'BTN', 2, NULL),
(27, 'Bolivia', 'Boliviano', 'BOB', 2, NULL),
(28, 'Bolivia', 'Mvdol', 'BOV', 2, NULL),
(29, 'Bonaire, Sint Eustatius And Saba', 'US Dollar', 'USD', 2, '$'),
(30, 'Bosnia And Herzegovina', 'Convertible Mark', 'BAM', 2, NULL),
(31, 'Botswana', 'Pula', 'BWP', 2, NULL),
(32, 'Bouvet Island', 'Norwegian Krone', 'NOK', 2, NULL),
(33, 'Brazil', 'Brazilian Real', 'BRL', 2, 'R$'),
(34, 'British Indian Ocean Territory', 'US Dollar', 'USD', 2, '$'),
(35, 'Brunei Darussalam', 'Brunei Dollar', 'BND', 2, NULL),
(36, 'Bulgaria', 'Bulgarian Lev', 'BGN', 2, 'лв'),
(37, 'Burkina Faso', 'CFA Franc BCEAO', 'XOF', 0, NULL),
(38, 'Burundi', 'Burundi Franc', 'BIF', 0, NULL),
(39, 'Cabo Verde', 'Cabo Verde Escudo', 'CVE', 2, NULL),
(40, 'Cambodia', 'Riel', 'KHR', 2, '៛'),
(41, 'Cameroon', 'CFA Franc BEAC', 'XAF', 0, NULL),
(42, 'Canada', 'Canadian Dollar', 'CAD', 2, '$'),
(43, 'Cayman Islands', 'Cayman Islands Dollar', 'KYD', 2, NULL),
(44, 'Central African Republic', 'CFA Franc BEAC', 'XAF', 0, NULL),
(45, 'Chad', 'CFA Franc BEAC', 'XAF', 0, NULL),
(46, 'Chile', 'Chilean Peso', 'CLP', 0, '$'),
(47, 'Chile', 'Unidad de Fomento', 'CLF', 4, NULL),
(48, 'China', 'Yuan Renminbi', 'CNY', 2, '¥'),
(49, 'Christmas Island', 'Australian Dollar', 'AUD', 2, NULL),
(50, 'Cocos (keeling) Islands', 'Australian Dollar', 'AUD', 2, NULL),
(51, 'Colombia', 'Colombian Peso', 'COP', 2, '$'),
(52, 'Colombia', 'Unidad de Valor Real', 'COU', 2, NULL),
(53, 'Comoros', 'Comorian Franc ', 'KMF', 0, NULL),
(54, 'Congo (the Democratic Republic Of The)', 'Congolese Franc', 'CDF', 2, NULL),
(55, 'Congo', 'CFA Franc BEAC', 'XAF', 0, NULL),
(56, 'Cook Islands', 'New Zealand Dollar', 'NZD', 2, '$'),
(57, 'Costa Rica', 'Costa Rican Colon', 'CRC', 2, NULL),
(58, 'Côte D\'ivoire', 'CFA Franc BCEAO', 'XOF', 0, NULL),
(59, 'Croatia', 'Kuna', 'HRK', 2, 'kn'),
(60, 'Cuba', 'Cuban Peso', 'CUP', 2, NULL),
(61, 'Cuba', 'Peso Convertible', 'CUC', 2, NULL),
(62, 'Curaçao', 'Netherlands Antillean Guilder', 'ANG', 2, NULL),
(63, 'Cyprus', 'Euro', 'EUR', 2, '€'),
(64, 'Czechia', 'Czech Koruna', 'CZK', 2, 'Kč'),
(65, 'Denmark', 'Danish Krone', 'DKK', 2, 'kr'),
(66, 'Djibouti', 'Djibouti Franc', 'DJF', 0, NULL),
(67, 'Dominica', 'East Caribbean Dollar', 'XCD', 2, NULL),
(68, 'Dominican Republic', 'Dominican Peso', 'DOP', 2, NULL),
(69, 'Ecuador', 'US Dollar', 'USD', 2, '$'),
(70, 'Egypt', 'Egyptian Pound', 'EGP', 2, NULL),
(71, 'El Salvador', 'El Salvador Colon', 'SVC', 2, NULL),
(72, 'El Salvador', 'US Dollar', 'USD', 2, '$'),
(73, 'Equatorial Guinea', 'CFA Franc BEAC', 'XAF', 0, NULL),
(74, 'Eritrea', 'Nakfa', 'ERN', 2, NULL),
(75, 'Estonia', 'Euro', 'EUR', 2, '€'),
(76, 'Eswatini', 'Lilangeni', 'SZL', 2, NULL),
(77, 'Ethiopia', 'Ethiopian Birr', 'ETB', 2, NULL),
(78, 'European Union', 'Euro', 'EUR', 2, '€'),
(79, 'Falkland Islands [Malvinas]', 'Falkland Islands Pound', 'FKP', 2, NULL),
(80, 'Faroe Islands', 'Danish Krone', 'DKK', 2, NULL),
(81, 'Fiji', 'Fiji Dollar', 'FJD', 2, NULL),
(82, 'Finland', 'Euro', 'EUR', 2, '€'),
(83, 'France', 'Euro', 'EUR', 2, '€'),
(84, 'French Guiana', 'Euro', 'EUR', 2, '€'),
(85, 'French Polynesia', 'CFP Franc', 'XPF', 0, NULL),
(86, 'French Southern Territories', 'Euro', 'EUR', 2, '€'),
(87, 'Gabon', 'CFA Franc BEAC', 'XAF', 0, NULL),
(88, 'Gambia', 'Dalasi', 'GMD', 2, NULL),
(89, 'Georgia', 'Lari', 'GEL', 2, '₾'),
(90, 'Germany', 'Euro', 'EUR', 2, '€'),
(91, 'Ghana', 'Ghana Cedi', 'GHS', 2, NULL),
(92, 'Gibraltar', 'Gibraltar Pound', 'GIP', 2, NULL),
(93, 'Greece', 'Euro', 'EUR', 2, '€'),
(94, 'Greenland', 'Danish Krone', 'DKK', 2, NULL),
(95, 'Grenada', 'East Caribbean Dollar', 'XCD', 2, NULL),
(96, 'Guadeloupe', 'Euro', 'EUR', 2, '€'),
(97, 'Guam', 'US Dollar', 'USD', 2, '$'),
(98, 'Guatemala', 'Quetzal', 'GTQ', 2, NULL),
(99, 'Guernsey', 'Pound Sterling', 'GBP', 2, '£'),
(100, 'Guinea', 'Guinean Franc', 'GNF', 0, NULL),
(101, 'Guinea-bissau', 'CFA Franc BCEAO', 'XOF', 0, NULL),
(102, 'Guyana', 'Guyana Dollar', 'GYD', 2, NULL),
(103, 'Haiti', 'Gourde', 'HTG', 2, NULL),
(104, 'Haiti', 'US Dollar', 'USD', 2, '$'),
(105, 'Heard Island And Mcdonald Islands', 'Australian Dollar', 'AUD', 2, NULL),
(106, 'Holy See (Vatican)', 'Euro', 'EUR', 2, '€'),
(107, 'Honduras', 'Lempira', 'HNL', 2, NULL),
(108, 'Hong Kong', 'Hong Kong Dollar', 'HKD', 2, '$'),
(109, 'Hungary', 'Forint', 'HUF', 2, 'ft'),
(110, 'Iceland', 'Iceland Krona', 'ISK', 0, NULL),
(111, 'India', 'Indian Rupee', 'INR', 2, '₹'),
(112, 'Indonesia', 'Rupiah', 'IDR', 2, 'Rp'),
(113, 'International Monetary Fund (IMF)', 'SDR (Special Drawing Right)', 'XDR', 0, NULL),
(114, 'Iran', 'Iranian Rial', 'IRR', 2, NULL),
(115, 'Iraq', 'Iraqi Dinar', 'IQD', 3, NULL),
(116, 'Ireland', 'Euro', 'EUR', 2, '€'),
(117, 'Isle Of Man', 'Pound Sterling', 'GBP', 2, '£'),
(118, 'Israel', 'New Israeli Sheqel', 'ILS', 2, '₪'),
(119, 'Italy', 'Euro', 'EUR', 2, '€'),
(120, 'Jamaica', 'Jamaican Dollar', 'JMD', 2, NULL),
(121, 'Japan', 'Yen', 'JPY', 0, '¥'),
(122, 'Jersey', 'Pound Sterling', 'GBP', 2, '£'),
(123, 'Jordan', 'Jordanian Dinar', 'JOD', 3, NULL),
(124, 'Kazakhstan', 'Tenge', 'KZT', 2, NULL),
(125, 'Kenya', 'Kenyan Shilling', 'KES', 2, 'Ksh'),
(126, 'Kiribati', 'Australian Dollar', 'AUD', 2, NULL),
(127, 'Korea (the Democratic People’s Republic Of)', 'North Korean Won', 'KPW', 2, NULL),
(128, 'Korea (the Republic Of)', 'Won', 'KRW', 0, '₩'),
(129, 'Kuwait', 'Kuwaiti Dinar', 'KWD', 3, NULL),
(130, 'Kyrgyzstan', 'Som', 'KGS', 2, NULL),
(131, 'Lao People’s Democratic Republic', 'Lao Kip', 'LAK', 2, NULL),
(132, 'Latvia', 'Euro', 'EUR', 2, '€'),
(133, 'Lebanon', 'Lebanese Pound', 'LBP', 2, NULL),
(134, 'Lesotho', 'Loti', 'LSL', 2, NULL),
(135, 'Lesotho', 'Rand', 'ZAR', 2, NULL),
(136, 'Liberia', 'Liberian Dollar', 'LRD', 2, NULL),
(137, 'Libya', 'Libyan Dinar', 'LYD', 3, NULL),
(138, 'Liechtenstein', 'Swiss Franc', 'CHF', 2, NULL),
(139, 'Lithuania', 'Euro', 'EUR', 2, '€'),
(140, 'Luxembourg', 'Euro', 'EUR', 2, '€'),
(141, 'Macao', 'Pataca', 'MOP', 2, NULL),
(142, 'North Macedonia', 'Denar', 'MKD', 2, NULL),
(143, 'Madagascar', 'Malagasy Ariary', 'MGA', 2, NULL),
(144, 'Malawi', 'Malawi Kwacha', 'MWK', 2, NULL),
(145, 'Malaysia', 'Malaysian Ringgit', 'MYR', 2, 'RM'),
(146, 'Maldives', 'Rufiyaa', 'MVR', 2, NULL),
(147, 'Mali', 'CFA Franc BCEAO', 'XOF', 0, NULL),
(148, 'Malta', 'Euro', 'EUR', 2, '€'),
(149, 'Marshall Islands', 'US Dollar', 'USD', 2, '$'),
(150, 'Martinique', 'Euro', 'EUR', 2, '€'),
(151, 'Mauritania', 'Ouguiya', 'MRU', 2, NULL),
(152, 'Mauritius', 'Mauritius Rupee', 'MUR', 2, NULL),
(153, 'Mayotte', 'Euro', 'EUR', 2, '€'),
(154, 'Member Countries Of The African Development Bank Group', 'ADB Unit of Account', 'XUA', 0, NULL),
(155, 'Mexico', 'Mexican Peso', 'MXN', 2, '$'),
(156, 'Mexico', 'Mexican Unidad de Inversion (UDI)', 'MXV', 2, NULL),
(157, 'Micronesia', 'US Dollar', 'USD', 2, '$'),
(158, 'Moldova', 'Moldovan Leu', 'MDL', 2, NULL),
(159, 'Monaco', 'Euro', 'EUR', 2, '€'),
(160, 'Mongolia', 'Tugrik', 'MNT', 2, NULL),
(161, 'Montenegro', 'Euro', 'EUR', 2, '€'),
(162, 'Montserrat', 'East Caribbean Dollar', 'XCD', 2, NULL),
(163, 'Morocco', 'Moroccan Dirham', 'MAD', 2, ' .د.م '),
(164, 'Mozambique', 'Mozambique Metical', 'MZN', 2, NULL),
(165, 'Myanmar', 'Kyat', 'MMK', 2, NULL),
(166, 'Namibia', 'Namibia Dollar', 'NAD', 2, NULL),
(167, 'Namibia', 'Rand', 'ZAR', 2, NULL),
(168, 'Nauru', 'Australian Dollar', 'AUD', 2, NULL),
(169, 'Nepal', 'Nepalese Rupee', 'NPR', 2, NULL),
(170, 'Netherlands', 'Euro', 'EUR', 2, '€'),
(171, 'New Caledonia', 'CFP Franc', 'XPF', 0, NULL),
(172, 'New Zealand', 'New Zealand Dollar', 'NZD', 2, '$'),
(173, 'Nicaragua', 'Cordoba Oro', 'NIO', 2, NULL),
(174, 'Niger', 'CFA Franc BCEAO', 'XOF', 0, NULL),
(175, 'Nigeria', 'Naira', 'NGN', 2, '₦'),
(176, 'Niue', 'New Zealand Dollar', 'NZD', 2, '$'),
(177, 'Norfolk Island', 'Australian Dollar', 'AUD', 2, NULL),
(178, 'Northern Mariana Islands', 'US Dollar', 'USD', 2, '$'),
(179, 'Norway', 'Norwegian Krone', 'NOK', 2, 'kr'),
(180, 'Oman', 'Rial Omani', 'OMR', 3, NULL),
(181, 'Pakistan', 'Pakistan Rupee', 'PKR', 2, 'Rs'),
(182, 'Palau', 'US Dollar', 'USD', 2, '$'),
(183, 'Panama', 'Balboa', 'PAB', 2, NULL),
(184, 'Panama', 'US Dollar', 'USD', 2, '$'),
(185, 'Papua New Guinea', 'Kina', 'PGK', 2, NULL),
(186, 'Paraguay', 'Guarani', 'PYG', 0, NULL),
(187, 'Peru', 'Sol', 'PEN', 2, 'S'),
(188, 'Philippines', 'Philippine Peso', 'PHP', 2, '₱'),
(189, 'Pitcairn', 'New Zealand Dollar', 'NZD', 2, '$'),
(190, 'Poland', 'Zloty', 'PLN', 2, 'zł'),
(191, 'Portugal', 'Euro', 'EUR', 2, '€'),
(192, 'Puerto Rico', 'US Dollar', 'USD', 2, '$'),
(193, 'Qatar', 'Qatari Rial', 'QAR', 2, NULL),
(194, 'Réunion', 'Euro', 'EUR', 2, '€'),
(195, 'Romania', 'Romanian Leu', 'RON', 2, 'lei'),
(196, 'Russian Federation', 'Russian Ruble', 'RUB', 2, '₽'),
(197, 'Rwanda', 'Rwanda Franc', 'RWF', 0, NULL),
(198, 'Saint Barthélemy', 'Euro', 'EUR', 2, '€'),
(199, 'Saint Helena, Ascension And Tristan Da Cunha', 'Saint Helena Pound', 'SHP', 2, NULL),
(200, 'Saint Kitts And Nevis', 'East Caribbean Dollar', 'XCD', 2, NULL),
(201, 'Saint Lucia', 'East Caribbean Dollar', 'XCD', 2, NULL),
(202, 'Saint Martin (French Part)', 'Euro', 'EUR', 2, '€'),
(203, 'Saint Pierre And Miquelon', 'Euro', 'EUR', 2, '€'),
(204, 'Saint Vincent And The Grenadines', 'East Caribbean Dollar', 'XCD', 2, NULL),
(205, 'Samoa', 'Tala', 'WST', 2, NULL),
(206, 'San Marino', 'Euro', 'EUR', 2, '€'),
(207, 'Sao Tome And Principe', 'Dobra', 'STN', 2, NULL),
(208, 'Saudi Arabia', 'Saudi Riyal', 'SAR', 2, NULL),
(209, 'Senegal', 'CFA Franc BCEAO', 'XOF', 0, NULL),
(210, 'Serbia', 'Serbian Dinar', 'RSD', 2, NULL),
(211, 'Seychelles', 'Seychelles Rupee', 'SCR', 2, NULL),
(212, 'Sierra Leone', 'Leone', 'SLL', 2, NULL),
(213, 'Singapore', 'Singapore Dollar', 'SGD', 2, '$'),
(214, 'Sint Maarten (Dutch Part)', 'Netherlands Antillean Guilder', 'ANG', 2, NULL),
(215, 'Sistema Unitario De Compensacion Regional De Pagos \"sucre\"\"\"', 'Sucre', 'XSU', 0, NULL),
(216, 'Slovakia', 'Euro', 'EUR', 2, '€'),
(217, 'Slovenia', 'Euro', 'EUR', 2, '€'),
(218, 'Solomon Islands', 'Solomon Islands Dollar', 'SBD', 2, NULL),
(219, 'Somalia', 'Somali Shilling', 'SOS', 2, NULL),
(220, 'South Africa', 'Rand', 'ZAR', 2, 'R'),
(221, 'South Sudan', 'South Sudanese Pound', 'SSP', 2, NULL),
(222, 'Spain', 'Euro', 'EUR', 2, '€'),
(223, 'Sri Lanka', 'Sri Lanka Rupee', 'LKR', 2, 'Rs'),
(224, 'Sudan (the)', 'Sudanese Pound', 'SDG', 2, NULL),
(225, 'Suriname', 'Surinam Dollar', 'SRD', 2, NULL),
(226, 'Svalbard And Jan Mayen', 'Norwegian Krone', 'NOK', 2, NULL),
(227, 'Sweden', 'Swedish Krona', 'SEK', 2, 'kr'),
(228, 'Switzerland', 'Swiss Franc', 'CHF', 2, NULL),
(229, 'Switzerland', 'WIR Euro', 'CHE', 2, NULL),
(230, 'Switzerland', 'WIR Franc', 'CHW', 2, NULL),
(231, 'Syrian Arab Republic', 'Syrian Pound', 'SYP', 2, NULL),
(232, 'Taiwan', 'New Taiwan Dollar', 'TWD', 2, NULL),
(233, 'Tajikistan', 'Somoni', 'TJS', 2, NULL),
(234, 'Tanzania, United Republic Of', 'Tanzanian Shilling', 'TZS', 2, NULL),
(235, 'Thailand', 'Baht', 'THB', 2, '฿'),
(236, 'Timor-leste', 'US Dollar', 'USD', 2, '$'),
(237, 'Togo', 'CFA Franc BCEAO', 'XOF', 0, NULL),
(238, 'Tokelau', 'New Zealand Dollar', 'NZD', 2, '$'),
(239, 'Tonga', 'Pa’anga', 'TOP', 2, NULL),
(240, 'Trinidad And Tobago', 'Trinidad and Tobago Dollar', 'TTD', 2, NULL),
(241, 'Tunisia', 'Tunisian Dinar', 'TND', 3, NULL),
(242, 'Turkey', 'Turkish Lira', 'TRY', 2, '₺'),
(243, 'Turkmenistan', 'Turkmenistan New Manat', 'TMT', 2, NULL),
(244, 'Turks And Caicos Islands', 'US Dollar', 'USD', 2, '$'),
(245, 'Tuvalu', 'Australian Dollar', 'AUD', 2, NULL),
(246, 'Uganda', 'Uganda Shilling', 'UGX', 0, NULL),
(247, 'Ukraine', 'Hryvnia', 'UAH', 2, '₴'),
(248, 'United Arab Emirates', 'UAE Dirham', 'AED', 2, 'د.إ'),
(249, 'United Kingdom Of Great Britain And Northern Ireland', 'Pound Sterling', 'GBP', 2, '£'),
(250, 'United States Minor Outlying Islands', 'US Dollar', 'USD', 2, '$'),
(251, 'United States Of America', 'US Dollar', 'USD', 2, '$'),
(252, 'United States Of America', 'US Dollar (Next day)', 'USN', 2, NULL),
(253, 'Uruguay', 'Peso Uruguayo', 'UYU', 2, NULL),
(254, 'Uruguay', 'Uruguay Peso en Unidades Indexadas (UI)', 'UYI', 0, NULL),
(255, 'Uruguay', 'Unidad Previsional', 'UYW', 4, NULL),
(256, 'Uzbekistan', 'Uzbekistan Sum', 'UZS', 2, NULL),
(257, 'Vanuatu', 'Vatu', 'VUV', 0, NULL),
(258, 'Venezuela', 'Bolívar Soberano', 'VES', 2, NULL),
(259, 'Vietnam', 'Dong', 'VND', 0, '₫'),
(260, 'Virgin Islands (British)', 'US Dollar', 'USD', 2, '$'),
(261, 'Virgin Islands (U.S.)', 'US Dollar', 'USD', 2, '$'),
(262, 'Wallis And Futuna', 'CFP Franc', 'XPF', 0, NULL),
(263, 'Western Sahara', 'Moroccan Dirham', 'MAD', 2, NULL),
(264, 'Yemen', 'Yemeni Rial', 'YER', 2, NULL),
(265, 'Zambia', 'Zambian Kwacha', 'ZMW', 2, NULL),
(266, 'Zimbabwe', 'Zimbabwe Dollar', 'ZWL', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `disbursed_funds`
--

CREATE TABLE `disbursed_funds` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `currency` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `month` text NOT NULL,
  `date_disbursed` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `emailSettingForm`
--

CREATE TABLE `emailSettingForm` (
  `id` int(11) NOT NULL,
  `sender_name` text NOT NULL,
  `smtp_server` text NOT NULL,
  `smtp_port` text NOT NULL,
  `sender_email` text NOT NULL,
  `sender_password` text NOT NULL,
  `parent_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emailSettingForm`
--

INSERT INTO `emailSettingForm` (`id`, `sender_name`, `smtp_server`, `smtp_port`, `sender_email`, `sender_password`, `parent_id`, `branch_id`) VALUES
(4, 'Chuma Solutions', 'smtp.zoho.com', '465', 'info@chumasolutions.com', 'Chumasolutions@2022', 6, 11);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `expense_date` date NOT NULL,
  `expense_name` text NOT NULL,
  `expense_amount` decimal(10,2) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `receipt_no_1` text NOT NULL,
  `receipt_no_2` text NOT NULL,
  `expense_loan_linked_to` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `fully_paid_loans`
--

CREATE TABLE `fully_paid_loans` (
  `id` int(11) NOT NULL,
  `loan_number` text NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `currency` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_cleared` date NOT NULL,
  `month` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `guarantors`
--

CREATE TABLE `guarantors` (
  `id` int(11) NOT NULL,
  `borrower_id` varchar(50) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `gender` text NOT NULL,
  `identity_number` text NOT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `address` text NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `dateofbirth` date NOT NULL,
  `working_status` text NOT NULL,
  `photo` text NOT NULL,
  `files` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `guarantor_files`
--

CREATE TABLE `guarantor_files` (
  `id` int(11) NOT NULL,
  `guarantor_id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `file_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `income_table`
--

CREATE TABLE `income_table` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `income_date` date NOT NULL,
  `income_name` text NOT NULL,
  `income_amount` decimal(10,2) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `receipt_no_1` text NOT NULL,
  `receipt_no_2` text NOT NULL,
  `income_loan_linked_to` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `investors`
--

CREATE TABLE `investors` (
  `id` int(11) NOT NULL,
  `photo` text NOT NULL,
  `parent_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `working_status` text NOT NULL,
  `id_type` text NOT NULL,
  `id_number` text NOT NULL,
  `gender` text NOT NULL,
  `investor_country` int(11) NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `address` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `borrower_id` varchar(100) NOT NULL,
  `loan_number` varchar(100) NOT NULL,
  `principle_amount` decimal(10,2) NOT NULL,
  `release_method` varchar(50) NOT NULL,
  `release_date` date NOT NULL,
  `loan_interest_method` varchar(50) NOT NULL,
  `interest_type` varchar(100) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `loan_interest` varchar(50) NOT NULL,
  `loan_interest_period` text NOT NULL,
  `loan_duration` int(11) NOT NULL,
  `loan_payment_options` text NOT NULL,
  `loan__period` text NOT NULL,
  `processing_fee_type` text NOT NULL,
  `loan_processing_fee` text NOT NULL,
  `guarantor_id` int(11) DEFAULT NULL,
  `loan_purpose` text NOT NULL,
  `repayments` int(11) NOT NULL,
  `annual_p_rate` varchar(10) NOT NULL,
  `total_interest_amount` decimal(10,2) NOT NULL,
  `total_payable_amount` decimal(10,2) NOT NULL,
  `recurring_amount` decimal(10,2) NOT NULL,
  `monthly_interest` decimal(10,2) NOT NULL,
  `total_monthly_repayments` decimal(10,2) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `loan_status` enum('For_Approval','Approved','Released','Rejected','Completed') NOT NULL,
  `submitted_by` int(11) NOT NULL DEFAULT 0,
  `actioned_by` int(11) NOT NULL DEFAULT 0,
  `repayment_start_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loanStatus`
--

CREATE TABLE `loanStatus` (
  `id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `loan_officer_id` int(11) NOT NULL,
  `action_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loans_table`
--

CREATE TABLE `loans_table` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `borrower_id` int(100) NOT NULL,
  `photo` text NOT NULL,
  `title` text NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `identity_number` text NOT NULL,
  `gender` text NOT NULL,
  `phone_number` text NOT NULL,
  `loan_number` varchar(100) NOT NULL,
  `principle_amount` decimal(10,2) NOT NULL,
  `release_method` varchar(50) NOT NULL,
  `release_date` date DEFAULT NULL,
  `loan_interest_method` varchar(50) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `loan_interest` varchar(50) NOT NULL,
  `loan_interest_period` text NOT NULL,
  `loan_duration` text DEFAULT NULL,
  `loan_payment_options` text NOT NULL,
  `processing_fee_type` text NOT NULL,
  `loan_processing_fee` text NOT NULL,
  `guarantor_id` int(11) DEFAULT NULL,
  `loan_purpose` text NOT NULL,
  `repayments` int(11) NOT NULL,
  `total_interest_amount` decimal(10,2) NOT NULL,
  `total_payable_amount` decimal(10,2) NOT NULL,
  `recurring_amount` decimal(10,2) NOT NULL,
  `monthly_interest` decimal(10,2) NOT NULL,
  `total_monthly_repayments` decimal(10,2) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `loan_status` enum('Pending Approval','Approved','Released','Rejected','Completed') NOT NULL,
  `submitted_by` text NOT NULL,
  `actioned_by` int(11) NOT NULL DEFAULT 0,
  `repayment_start_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_fees`
--

CREATE TABLE `loan_fees` (
  `id` int(11) NOT NULL,
  `choice` enum('percentage_based','amount_based') NOT NULL,
  `loan_fees_name` text NOT NULL,
  `loan_fees` decimal(10,2) NOT NULL,
  `symbol` varchar(10) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_list`
--

CREATE TABLE `loan_list` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `ref_no` varchar(50) NOT NULL,
  `loan_type_id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `purpose` text NOT NULL,
  `amount` double NOT NULL,
  `plan_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0= request, 1= confrimed,2=released,3=complteted,4=denied\r\n',
  `date_released` datetime NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_payments`
--

CREATE TABLE `loan_payments` (
  `id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `loan_number` varchar(50) NOT NULL,
  `currency` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `paid_date` date NOT NULL,
  `payment_method` text NOT NULL,
  `collected_by` int(11) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_paymentss`
--

CREATE TABLE `loan_paymentss` (
  `id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `loan_number` varchar(50) NOT NULL,
  `borrower_id` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_date` date NOT NULL,
  `payment_method` text NOT NULL,
  `collected_by` int(11) NOT NULL,
  `comment` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_plans`
--

CREATE TABLE `loan_plans` (
  `id` int(11) NOT NULL,
  `loan_type` int(11) NOT NULL,
  `months` int(11) NOT NULL,
  `interest_percentage` float NOT NULL,
  `penalty_rate` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_schedules`
--

CREATE TABLE `loan_schedules` (
  `id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `loan_id` varchar(300) NOT NULL,
  `currency` text DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_due` date NOT NULL,
  `paid_status` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_type`
--

CREATE TABLE `loan_type` (
  `id` int(11) NOT NULL,
  `type_name` text NOT NULL,
  `interest_rate` text DEFAULT NULL,
  `period` text DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loan_type`
--

INSERT INTO `loan_type` (`id`, `type_name`, `interest_rate`, `period`, `parent_id`, `date_added`) VALUES
(1, 'Market Loan', '', '', 6, '2023-01-08');

-- --------------------------------------------------------

--
-- Table structure for table `login_table`
--

CREATE TABLE `login_table` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `time_login` datetime NOT NULL DEFAULT current_timestamp(),
  `user_ip` text NOT NULL,
  `user_country` text DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login_table`
--

INSERT INTO `login_table` (`id`, `parent_id`, `email`, `password`, `time_login`, `user_ip`, `user_country`, `logout_time`) VALUES
(1, 6, 'bwangachibesa@gmail.com', '$2y$10$p07b3CUQHg5x6b4yDOElzuuHTpLKyn65CyYfufOHffj4WYUl6doke', '2022-01-17 10:26:12', '102.148.234.38', 'Zambia', '2022-01-17 17:22:25'),
(2, 6, 'bwangachibesa@gmail.com', '$2y$10$p07b3CUQHg5x6b4yDOElzuuHTpLKyn65CyYfufOHffj4WYUl6doke', '2022-02-06 11:59:14', '102.148.137.34', 'Zambia', '2022-02-06 12:25:21'),
(3, 6, 'malangokapako@gmail.com', '$2y$10$TzQ6zSE04hJhJlJR1h7hNOmMfrm3K8qAxSUewsy8291jaRU6iTz0W', '2022-02-06 13:49:32', '102.150.166.246', 'Zambia', '2022-02-06 13:50:14'),
(4, 6, 'malangokapako@gmail.com', '$2y$10$TzQ6zSE04hJhJlJR1h7hNOmMfrm3K8qAxSUewsy8291jaRU6iTz0W', '2022-02-06 13:54:44', '41.60.184.178', 'Zambia', NULL),
(5, 6, 'malangokapako@gmail.com', '$2y$10$TzQ6zSE04hJhJlJR1h7hNOmMfrm3K8qAxSUewsy8291jaRU6iTz0W', '2022-02-06 13:59:30', '102.150.166.246', 'Zambia', '2022-02-06 14:06:20'),
(6, 6, 'bwangachibesa@gmail.com', '$2y$10$TGpbkD.EBAzRTsDkUJ/CH.JlH4eN.vrxjfl/kCXCkL5j9g3ZfVLpC', '2023-01-08 12:20:18', '41.174.32.98', 'Mauritius', NULL),
(7, 6, 'bwangachibesa@gmail.com', '$2y$10$TGpbkD.EBAzRTsDkUJ/CH.JlH4eN.vrxjfl/kCXCkL5j9g3ZfVLpC', '2023-01-08 12:48:54', '41.174.32.98', 'Mauritius', '2023-01-08 15:06:56');

-- --------------------------------------------------------

--
-- Table structure for table `organisations`
--

CREATE TABLE `organisations` (
  `id` int(11) NOT NULL,
  `org_logo` text NOT NULL,
  `organisation_name` text NOT NULL,
  `parent_id` int(11) NOT NULL,
  `admin_email` text NOT NULL,
  `hq_phone` text NOT NULL,
  `hq_address` text NOT NULL,
  `date_added` date NOT NULL,
  `admin_password` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `organisations`
--

INSERT INTO `organisations` (`id`, `org_logo`, `organisation_name`, `parent_id`, `admin_email`, `hq_phone`, `hq_address`, `date_added`, `admin_password`) VALUES
(4, 'ChumaLogo2.jpeg', 'Chuma Solutions', 6, 'info@chumasolutions.com', '0976330092', 'Chiyoli Road, Roma. Lusaka', '2022-01-01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports_arrear_loans`
--

CREATE TABLE `reports_arrear_loans` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `loan_number` text NOT NULL,
  `remarks` text NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `display` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reports_fully_paid_loans`
--

CREATE TABLE `reports_fully_paid_loans` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `loan_number` text NOT NULL,
  `remarks` text NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `display` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reports_issued_loans`
--

CREATE TABLE `reports_issued_loans` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `loan_number` text NOT NULL,
  `remarks` text NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `display` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE `sms` (
  `id` int(11) NOT NULL,
  `receiver` text NOT NULL,
  `sender_id` text NOT NULL,
  `parent_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_sent` datetime NOT NULL DEFAULT current_timestamp(),
  `responseText` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allowed_branches`
--
ALTER TABLE `allowed_branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowers_business_details`
--
ALTER TABLE `borrowers_business_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowers_details`
--
ALTER TABLE `borrowers_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowers_files`
--
ALTER TABLE `borrowers_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrower_branches`
--
ALTER TABLE `borrower_branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrower_next_of_kin_details`
--
ALTER TABLE `borrower_next_of_kin_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collaterals`
--
ALTER TABLE `collaterals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collaterals_files`
--
ALTER TABLE `collaterals_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collected_funds`
--
ALTER TABLE `collected_funds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disbursed_funds`
--
ALTER TABLE `disbursed_funds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emailSettingForm`
--
ALTER TABLE `emailSettingForm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fully_paid_loans`
--
ALTER TABLE `fully_paid_loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guarantors`
--
ALTER TABLE `guarantors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guarantor_files`
--
ALTER TABLE `guarantor_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_table`
--
ALTER TABLE `income_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investors`
--
ALTER TABLE `investors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loanStatus`
--
ALTER TABLE `loanStatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loans_table`
--
ALTER TABLE `loans_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_fees`
--
ALTER TABLE `loan_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_list`
--
ALTER TABLE `loan_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_paymentss`
--
ALTER TABLE `loan_paymentss`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_plans`
--
ALTER TABLE `loan_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_schedules`
--
ALTER TABLE `loan_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_type`
--
ALTER TABLE `loan_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_table`
--
ALTER TABLE `login_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisations`
--
ALTER TABLE `organisations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports_arrear_loans`
--
ALTER TABLE `reports_arrear_loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports_fully_paid_loans`
--
ALTER TABLE `reports_fully_paid_loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports_issued_loans`
--
ALTER TABLE `reports_issued_loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `allowed_branches`
--
ALTER TABLE `allowed_branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `borrowers_business_details`
--
ALTER TABLE `borrowers_business_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `borrowers_details`
--
ALTER TABLE `borrowers_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `borrowers_files`
--
ALTER TABLE `borrowers_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrower_branches`
--
ALTER TABLE `borrower_branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrower_next_of_kin_details`
--
ALTER TABLE `borrower_next_of_kin_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `collaterals`
--
ALTER TABLE `collaterals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collaterals_files`
--
ALTER TABLE `collaterals_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collected_funds`
--
ALTER TABLE `collected_funds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;

--
-- AUTO_INCREMENT for table `disbursed_funds`
--
ALTER TABLE `disbursed_funds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emailSettingForm`
--
ALTER TABLE `emailSettingForm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fully_paid_loans`
--
ALTER TABLE `fully_paid_loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guarantors`
--
ALTER TABLE `guarantors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guarantor_files`
--
ALTER TABLE `guarantor_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income_table`
--
ALTER TABLE `income_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `investors`
--
ALTER TABLE `investors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `loanStatus`
--
ALTER TABLE `loanStatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans_table`
--
ALTER TABLE `loans_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_fees`
--
ALTER TABLE `loan_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_list`
--
ALTER TABLE `loan_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_paymentss`
--
ALTER TABLE `loan_paymentss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `loan_plans`
--
ALTER TABLE `loan_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_schedules`
--
ALTER TABLE `loan_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_type`
--
ALTER TABLE `loan_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `login_table`
--
ALTER TABLE `login_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `organisations`
--
ALTER TABLE `organisations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reports_arrear_loans`
--
ALTER TABLE `reports_arrear_loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports_fully_paid_loans`
--
ALTER TABLE `reports_fully_paid_loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports_issued_loans`
--
ALTER TABLE `reports_issued_loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms`
--
ALTER TABLE `sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
