-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2025 at 11:34 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `updationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'admin', 'f925916e2754e5e03f75dd58a5733251', '2025-03-13 11:18:49');

-- --------------------------------------------------------

--
-- Table structure for table `tblbooking`
--

CREATE TABLE `tblbooking` (
  `BookingId` int(11) NOT NULL,
  `PackageId` int(11) DEFAULT NULL,
  `UserEmail` varchar(100) DEFAULT NULL,
  `FromDate` varchar(100) DEFAULT NULL,
  `ToDate` varchar(100) DEFAULT NULL,
  `Comment` mediumtext DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT NULL,
  `CancelledBy` varchar(5) DEFAULT NULL,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblbooking`
--

INSERT INTO `tblbooking` (`BookingId`, `PackageId`, `UserEmail`, `FromDate`, `ToDate`, `Comment`, `RegDate`, `status`, `CancelledBy`, `UpdationDate`) VALUES
(13, 6, 'mmmm@admin.com', '2025-03-31', '14 March, 2025', 'nk', '2025-03-31 07:50:13', 0, NULL, NULL),
(14, 7, 'juju@gmail.com', '2025-04-14', '2025-04-21', 'jk;u', '2025-04-02 10:07:31', 2, 'u', '2025-04-02 10:17:52'),
(15, 7, 'juju@gmail.com', '14-04-2025', '28-04-2025', 'uuu', '2025-04-02 10:24:54', 2, 'u', '2025-04-02 10:34:01'),
(16, 9, 'juju@gmail.com', '2025-04-21', '2025-04-28', 'y', '2025-04-02 10:29:01', 2, 'u', '2025-04-02 10:34:06'),
(17, 8, 'juju@gmail.com', '14/02/2025', '2025-04-21', 'jk;u', '2025-04-02 10:33:25', 0, NULL, NULL),
(18, 12, 'juju@gmail.com', '2025-04-29', '2025-04-30', 'yy', '2025-04-02 10:34:57', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblenquiry`
--

CREATE TABLE `tblenquiry` (
  `id` int(11) NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `EmailId` varchar(100) DEFAULT NULL,
  `MobileNumber` char(10) DEFAULT NULL,
  `Subject` varchar(100) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `Status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblissues`
--

CREATE TABLE `tblissues` (
  `id` int(11) NOT NULL,
  `UserEmail` varchar(100) DEFAULT NULL,
  `Issue` varchar(100) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `AdminRemark` mediumtext DEFAULT NULL,
  `AdminremarkDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblissues`
--

INSERT INTO `tblissues` (`id`, `UserEmail`, `Issue`, `Description`, `PostingDate`, `AdminRemark`, `AdminremarkDate`) VALUES
(9, 'mmmm@admin.com', 'Refund', 'PRESIDENT', '2025-03-31 07:54:44', NULL, NULL),
(10, NULL, NULL, NULL, '2025-04-02 09:42:21', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblpages`
--

CREATE TABLE `tblpages` (
  `id` int(11) NOT NULL,
  `type` varchar(255) DEFAULT '',
  `detail` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblpages`
--

INSERT INTO `tblpages` (`id`, `type`, `detail`) VALUES
(1, 'terms', '										<p align=\"justify\"><font size=\"2\"><strong><font color=\"#990000\">(1) ACCEPTANCE OF TERMS</font><br></strong></font></p><p style=\"margin: calc(var(--ds-md-zoom)*12px)0; font-size: 16.002px; line-height: var(--ds-md-line-height); color: rgb(64, 64, 64); font-family: DeepSeek-CJK-patch, Inter, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Noto Sans&quot;, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, Oxygen, &quot;Open Sans&quot;, sans-serif;\">By accessing, registering, or using the&nbsp;<strong>Online Tourism Management System</strong>&nbsp;(the \"Service\"), you agree to comply with and be legally bound by these&nbsp;<strong>Terms and Conditions</strong>&nbsp;(\"Terms\"). If you do not agree to these Terms, you must not use the Service.</p><h4 style=\"font-weight: var(--ds-font-weight-strong); font-size: 16.002px; line-height: var(--ds-md-line-height); margin: calc(var(--ds-md-zoom)*16px)0 calc(var(--ds-md-zoom)*12px)0; color: rgb(64, 64, 64); font-family: DeepSeek-CJK-patch, Inter, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Noto Sans&quot;, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, Oxygen, &quot;Open Sans&quot;, sans-serif;\"><strong>Key Provisions:</strong></h4><ol start=\"1\" style=\"margin: calc(var(--ds-md-zoom)*12px)0; padding-left: calc(var(--ds-md-zoom)*24px); color: rgb(64, 64, 64); font-family: DeepSeek-CJK-patch, Inter, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Noto Sans&quot;, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, Oxygen, &quot;Open Sans&quot;, sans-serif; font-size: 16.002px;\"><li><p style=\"margin-bottom: 4px; font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height);\"><strong>Binding Agreement</strong>:</p><ul style=\"margin-top: 4px; padding-left: calc(var(--ds-md-zoom)*24px);\"><li><p style=\"font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height); margin-bottom: 0px !important;\">These Terms constitute a legally binding agreement between you (the \"User\") and [Your Company Name] (\"Provider\").</p></li><li style=\"margin-top: 4px;\"><p style=\"font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height); margin-bottom: 0px !important;\">By clicking \"I Agree,\" creating an account, or using the Service, you acknowledge acceptance.</p></li></ul></li><li style=\"margin-top: 4px;\"><p style=\"margin-bottom: 4px; font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height);\"><strong>Updates to Terms</strong>:</p><ul style=\"margin-top: 4px; padding-left: calc(var(--ds-md-zoom)*24px);\"><li><p style=\"font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height); margin-bottom: 0px !important;\">The Provider reserves the right to modify these Terms at any time. Continued use after changes constitutes acceptance.</p></li><li style=\"margin-top: 4px;\"><p style=\"font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height); margin-bottom: 0px !important;\">Users will be notified of material changes via email or system alerts.</p></li></ul></li><li style=\"margin-top: 4px;\"><p style=\"margin-bottom: 4px; font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height);\"><strong>Eligibility</strong>:</p><ul style=\"margin-top: 4px; padding-left: calc(var(--ds-md-zoom)*24px);\"><li><p style=\"font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height); margin-bottom: 0px !important;\">The Service is available only to individuals/businesses capable of forming binding contracts under applicable law.</p></li><li style=\"margin-top: 4px;\"><p style=\"font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height); margin-bottom: 0px !important;\">Minors or restricted users must obtain parental/guardian consent.</p></li></ul></li><li style=\"margin-top: 4px;\"><p style=\"margin-bottom: 4px; font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height);\"><strong>User Responsibilities</strong>:</p><ul style=\"margin-top: 4px; padding-left: calc(var(--ds-md-zoom)*24px);\"><li><p style=\"font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height); margin-bottom: 0px !important;\">Provide accurate registration information.</p></li><li style=\"margin-top: 4px;\"><p style=\"font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height); margin-bottom: 0px !important;\">Maintain confidentiality of account credentials.</p></li><li style=\"margin-top: 4px;\"><p style=\"font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height); margin-bottom: 0px !important;\">Comply with all applicable laws and third-party terms (e.g., payment processors).</p></li></ul></li><li style=\"margin-top: 4px;\"><p style=\"margin-bottom: 4px; font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height);\"><strong>Termination of Access</strong>:</p><ul style=\"margin-top: 4px; padding-left: calc(var(--ds-md-zoom)*24px);\"><li><p style=\"font-size: var(--ds-md-font-size); line-height: var(--ds-md-line-height); margin-bottom: 0px !important;\">The Provider may suspend or terminate accounts for violations of these Terms.</p></li><li><div><br></div></li></ul></li></ol><p align=\"justify\"><font size=\"2\"><br></font></p>\r\n										'),
(2, 'privacy', '																				<p class=\"MsoNormal\">PRIVACY POLICY&nbsp;</p>\r\n\r\n<p class=\"MsoNormal\">This Privacy Policy explains how our company collects, uses, shares,\r\nand protects your information when you use our Online Tourism Management System\r\n(the \"Service\"). By using the Service, you agree to this\r\npolicy.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><br></p>\r\n\r\n<p class=\"MsoNormal\">&nbsp;1. INFORMATION WE\r\nCOLLECT&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><o:p>&nbsp;</o:p></p>\r\n\r\n<p class=\"MsoNormal\">A. Personal Information&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Name, email, phone number, address, and government ID (for\r\nbookings).&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Payment details (mpesa).&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Travel preferences (destinations, accommodations, special\r\nrequests).&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><o:p>&nbsp;</o:p></p>\r\n\r\n<p class=\"MsoNormal\">B. Non-Personal Information&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- IP address, browser type, device info, and pages\r\nvisited.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Cookies (used to improve the Service; you can manage them\r\nin browser settings).&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><br></p>\r\n\r\n<p class=\"MsoNormal\">&nbsp;2. HOW WE USE YOUR\r\nDATA&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- To provide and personalize the Service (e.g., booking\r\nconfirmations, travel recommendations).&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- To process payments and prevent fraud.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- To send updates, promotions, or important notices (opt-out\r\navailable).&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- For analytics and legal compliance.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><br></p>\r\n\r\n<p class=\"MsoNormal\">&nbsp;3. SHARING YOUR\r\nINFORMATION&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">We may share data with:&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Travel partners (hotels, airlines, tour operators) to\r\ncomplete bookings.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Payment processors to handle transactions\r\nsecurely.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Legal authorities if required by law.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Analytics providers (e.g., Google Analytics) to improve\r\nour Service.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><o:p>&nbsp;</o:p></p>\r\n\r\n<p class=\"MsoNormal\">We do not sell your personal data.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><br></p>\r\n\r\n<p class=\"MsoNormal\">&nbsp;4. DATA SECURITY&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- We use encryption (SSL/TLS) to protect your\r\ninformation.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Payment details are not stored on our servers.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Regular security checks to prevent unauthorized\r\naccess.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><br></p>\r\n\r\n<p class=\"MsoNormal\">&nbsp;5. YOUR RIGHTS&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">Depending on where you live, you may:&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Access, correct, or delete your data through your\r\naccount.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Opt out of marketing emails.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Request a copy of your data (data\r\nportability).&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- File a complaint with a data protection authority (if\r\napplicable).&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><br></p>\r\n\r\n<p class=\"MsoNormal\">&nbsp;6. DATA RETENTION\r\n&amp; CHILDREN&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- We keep data only as long as necessary (e.g., for legal or\r\ntax reasons).&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Our Service is not for children under 13/16 (we delete\r\nsuch data if collected by mistake).&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><o:p>&nbsp;</o:p></p>\r\n\r\n<p class=\"MsoNormal\">7. CHANGES TO THIS POLICY&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- We may update this policy. Check the \"Last\r\nUpdated\" date for changes.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Major changes will be notified via email or the\r\nService.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><br></p>\r\n										\r\n										'),
(3, 'aboutus', '<div style=\"text-align: justify;\"><p class=\"MsoNormal\">About Us&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><o:p>&nbsp;</o:p></p>\r\n\r\n<p class=\"MsoNormal\">Welcome to our company, your reliable online tourism\r\nmanagement platform. We provide innovative solutions to simplify travel\r\nplanning and bookings for both travelers and businesses.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><o:p>&nbsp;</o:p></p>\r\n\r\n<p class=\"MsoNormal\">&nbsp;Our Mission&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">To make travel accessible, enjoyable, and hassle-free\r\nthrough technology-driven services.&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><o:p>&nbsp;</o:p></p>\r\n\r\n<p class=\"MsoNormal\">&nbsp;What We Offer&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Easy booking for flights, hotels, tours, and\r\nactivities&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Management tools for travel agencies and hospitality\r\nbusinesses&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- Secure payment processing&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">- 24/7 customer support&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><o:p>&nbsp;</o:p></p>\r\n\r\n<p class=\"MsoNormal\">&nbsp;Why Us?&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-family:&quot;Segoe UI Symbol&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Symbol&quot;\">?</span> User-friendly\r\nplatform&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-family:&quot;Segoe UI Symbol&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Symbol&quot;\">?</span> Competitive prices&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-family:&quot;Segoe UI Symbol&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Symbol&quot;\">?</span> Trusted by our customers\r\nworldwide&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-family:&quot;Segoe UI Symbol&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Symbol&quot;\">?</span> Secure and reliable\r\nservice&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><o:p>&nbsp;</o:p></p>\r\n\r\n<p class=\"MsoNormal\">&nbsp;</p>\r\n\r\n<p class=\"MsoNormal\">We\'re here to help you explore the world with\r\nease!&nbsp;&nbsp;<o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\"><o:p>&nbsp;<br></o:p></p></div>'),
(11, 'contact', '																				<span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">PHONE NUMBER:+254712345678</span><div><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">EMAIL: tourism@gmail.com</span></div>');

-- --------------------------------------------------------

--
-- Table structure for table `tbltourpackages`
--

CREATE TABLE `tbltourpackages` (
  `PackageId` int(11) NOT NULL,
  `PackageName` varchar(200) DEFAULT NULL,
  `PackageType` varchar(150) DEFAULT NULL,
  `PackageLocation` varchar(100) DEFAULT NULL,
  `PackagePrice` int(11) DEFAULT NULL,
  `PackageFetures` varchar(255) DEFAULT NULL,
  `PackageDetails` mediumtext DEFAULT NULL,
  `PackageImage` varchar(100) DEFAULT NULL,
  `Creationdate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbltourpackages`
--

INSERT INTO `tbltourpackages` (`PackageId`, `PackageName`, `PackageType`, `PackageLocation`, `PackagePrice`, `PackageFetures`, `PackageDetails`, `PackageImage`, `Creationdate`, `UpdationDate`) VALUES
(7, 'PIRATES BEACH', 'EVERYONE', 'MOMBASA', 10000, 'pickup point is nairobi afya centre', '5 days ', '0.jpeg', '2025-03-31 08:07:41', '2025-03-31 08:08:23'),
(8, 'ZANZIBAR TOUR', 'COUPLES', 'TANZANIA', 40000, 'BUS WITH WIFI,MUSIC AND COMFORTABLE SEATS', '7 DAYS', '1.jpeg', '2025-03-31 08:13:11', NULL),
(9, 'DIANI BEACH', 'FAMILY', 'South of Mombasa. ', 50000, 'FREE TRANSPORT ANYWHERE', 'Ideal for water sports enthusiasts, with opportunities for swimming, sunbathing, snorkeling, scuba diving, kite surfing, windsurfing, and deep-sea fishing. \r\nThings to Do:\r\nRelax on the beach: Enjoy the sun, sand, and sea. \r\nExplore the marine life: Go snorkeling or diving to see the coral reefs and diverse marine life. \r\nGo on a safari: Visit Shimba Hills National Reserve or the Mwaluganje Elephant Sanctuary. \r\nTry water sports: Kite surfing, windsurfing, or deep-sea fishing are popular activities. \r\nExplore the local culture: Visit Ukunda village or take a bike tour inland. \r\nEnjoy the nightlife: Diani Beach has a vibrant nightlife scene with a variety of restaurants, bars, and nightclubs', '2.jpeg', '2025-03-31 08:17:10', NULL),
(10, 'DUNGA BEACH', 'EVERYONE', 'LAKE VICTORIA KISUMU', 25000, 'FREE TRANSPORT TO TOURING PLACESAROUND THE LAKE', 'Dunga Beach is situated on the shores of Lake Victoria in Kisumu City.Its a buzz with activities including boat riding. There are a number of eateries serving local delicacy including fresh fish from the lake.', '3.jpeg', '2025-03-31 08:20:19', NULL),
(11, 'LAKE NAKURU', 'FAMILY', 'NAKURU COUNTY', 7000, 'pickup point is nairobi Westlands', 'Lake Nakuru National Park is famous for the large flocks of pink flamingos which inhabit the Lake Nakuru, making them the most sought after attraction in the park. Flamingos are abundant in Lake Nakuru for the presence of the blue-green algae which is food for them.', '4.jpeg', '2025-03-31 08:22:27', NULL),
(12, 'MAASAI MARA WILDLIFE', 'SPOUSES', 'NAROK COUNTY', 6000, 'pickup point is nairobi Westlands', 'Maasai Mara is one of the wildlife conservation and wilderness areas in Africa, with its populations of lions, leopards, cheetahs and African bush elephants.', '5.jpeg', '2025-03-31 09:03:33', '2025-03-31 10:16:56');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `MobileNumber` char(10) DEFAULT NULL,
  `EmailId` varchar(70) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`id`, `FullName`, `MobileNumber`, `EmailId`, `Password`, `RegDate`, `UpdationDate`) VALUES
(12, 'Manuuh Manuela', '0713121912', 'manuuhmanuela78@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2025-03-27 09:49:41', NULL),
(13, NULL, NULL, NULL, 'd41d8cd98f00b204e9800998ecf8427e', '2025-03-31 07:54:43', NULL),
(14, 'Jeneffer Jelagat', '0710624330', 'juju@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2025-04-02 09:42:19', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbooking`
--
ALTER TABLE `tblbooking`
  ADD PRIMARY KEY (`BookingId`);

--
-- Indexes for table `tblenquiry`
--
ALTER TABLE `tblenquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblissues`
--
ALTER TABLE `tblissues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblpages`
--
ALTER TABLE `tblpages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbltourpackages`
--
ALTER TABLE `tbltourpackages`
  ADD PRIMARY KEY (`PackageId`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `EmailId` (`EmailId`),
  ADD KEY `EmailId_2` (`EmailId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblbooking`
--
ALTER TABLE `tblbooking`
  MODIFY `BookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tblenquiry`
--
ALTER TABLE `tblenquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblissues`
--
ALTER TABLE `tblissues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblpages`
--
ALTER TABLE `tblpages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbltourpackages`
--
ALTER TABLE `tbltourpackages`
  MODIFY `PackageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
