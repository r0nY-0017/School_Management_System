-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 18, 2025 at 05:35 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `admin_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emergency_contact` varchar(20) DEFAULT NULL,
  `national_id` varchar(50) DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `name`, `email`, `phone`, `dob`, `gender`, `join_date`, `address`, `emergency_contact`, `national_id`, `qualification`, `blood_group`, `profile_image`, `password`) VALUES
('admin001', 'Test Admin 001', 'admin@school.edu', '+1234567890', '1990-01-01', 'Male', '2020-01-01', 'Ashulia, Savar, Dhaka', '+1234567890', '1234567890', 'Master\'s in Education', 'O+', NULL, '$2y$10$HAau47lqUbDBl7.xjnnAreCC7IasY8Zk/tg0cbxOD5Y8Y.elFu9dq');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

DROP TABLE IF EXISTS `parents`;
CREATE TABLE IF NOT EXISTS `parents` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `relation` enum('Father','Mother','Guardian') NOT NULL,
  `student_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_student` (`student_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `name`, `email`, `password`, `phone`, `address`, `occupation`, `relation`, `student_id`) VALUES
(101, 'Karim Uddin', 'karim.uddin@example.com', 'pass123', '01722223333', 'Dhaka, Bangladesh', 'Businessman', 'Father', 101),
(102, 'Nasima Begum', 'nasima.begum@example.com', 'pass456', '01833334444', 'Dhaka, Bangladesh', 'Teacher', 'Mother', 101),
(105, 'Kamal Ali', 'kamal@gmail.com', '$2y$10$MRzQeMEmaouj5yZnBowBCukFvtMEvGhwBasHknIHpsFACPzGimWL.', NULL, NULL, NULL, 'Father', 102);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `class` varchar(20) NOT NULL,
  `roll` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `guardian_phone` varchar(20) DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `class` (`class`,`roll`),
  UNIQUE KEY `class_2` (`class`,`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `class`, `roll`, `password`, `phone`, `address`, `dob`, `gender`, `guardian_name`, `guardian_phone`, `admission_date`, `status`) VALUES
(101, 'Rahim Uddin', 'rahim@example.com', '10', 5, '123456', '01712345678', 'Dhaka', '2008-03-15', 'Male', 'Karim Uddin', '01898765432', '2022-01-10', 'Active'),
(102, 'Karim Ali', 'karim@example.com', '10', 6, 'abcdef', '01711112222', 'Chittagong', '2008-05-10', 'Male', 'Aziz Ali', '01899998888', '2022-01-12', 'Active'),
(103, 'Md Mehedi Hasan Rony', 'mehedi@example.com', '10', 7, '$2y$10$bNcJFjJ7nPrzfhdyrbV3kurHfB3HO2WYlkMHTHQpMtj5Ra2VTy0Yq', '01764948871', 'Savar , Dhaka', '2001-06-12', 'Male', 'Md Nawsad Ali', '012345678', NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT 'default.jpg',
  `bio` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `status` enum('Active','Inactive','On Leave') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `email`, `password`, `subject`, `phone`, `address`, `gender`, `date_of_birth`, `joining_date`, `qualification`, `experience`, `profile_pic`, `bio`, `last_login`, `status`, `created_at`, `updated_at`) VALUES
('T001', 'Abdul Karim Mia', 'abdul@example.com', '$2y$10$TBjHkxJmVlCv7XEcQCvfuebBP3jgtGFmIH4gyancCQuUxHj5AlCSW', 'Mathematics', '+88012345678', '123/A, Dhanmondi, Dhaka', 'Male', '1985-08-12', '2015-06-15', 'M.Sc in Mathematics, B.Ed', '15+ years', 'fatima.jpg', 'Specialized in Algebra and Calculus. Passionate about making math fun for students.', NULL, 'Active', '2025-08-18 14:55:25', '2025-08-18 16:06:51'),
('T002', 'Abdul Karim', 'karim@example.com', '$2y$10$hashedpassword', 'Physics', '+8801812345678', '456/B, Mirpur, Dhaka', 'Male', '1982-11-25', '2012-03-10', 'M.Sc in Physics, PhD in Quantum Mechanics', '18+ years', 'abdul.jpg', 'Physics department head with research background.', NULL, 'Active', '2025-08-18 14:55:25', '2025-08-18 14:55:25');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_requests`
--

DROP TABLE IF EXISTS `teacher_requests`;
CREATE TABLE IF NOT EXISTS `teacher_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` varchar(50) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `teacher_id` varchar(50) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `student_id` (`student_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
