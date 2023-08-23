-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2023 at 05:32 AM
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
-- Database: `sit_feedback`
--

-- --------------------------------------------------------

--
-- Table structure for table `cs_feedback`
--

CREATE TABLE `cs_feedback` (
  `id` int(11) NOT NULL,
  `prn` varchar(11) NOT NULL,
  `name` varchar(400) NOT NULL,
  `acad_year` varchar(4) NOT NULL,
  `branch` varchar(4) NOT NULL,
  `class` varchar(4) NOT NULL,
  `teacher` varchar(400) NOT NULL,
  `subject` varchar(400) NOT NULL,
  `q1` int(2) NOT NULL,
  `q2` int(2) NOT NULL,
  `q3` int(2) NOT NULL,
  `q4` int(2) NOT NULL,
  `q5` int(2) NOT NULL,
  `q6` int(2) NOT NULL,
  `q7` int(2) NOT NULL,
  `q8` int(2) NOT NULL,
  `q9` int(2) NOT NULL,
  `avg` decimal(5,2) NOT NULL,
  `valid_year` varchar(50) NOT NULL,
  `is_submitted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cs_feedback`
--

INSERT INTO `cs_feedback` (`id`, `prn`, `name`, `acad_year`, `branch`, `class`, `teacher`, `subject`, `q1`, `q2`, `q3`, `q4`, `q5`, `q6`, `q7`, `q8`, `q9`, `avg`, `valid_year`, `is_submitted`) VALUES
(6, '21070122134', 'Saksham Gupta', 'sy', 'cs', 'c2', 'Ram Kishan Lodhi', 'Introduction to Big Data', 5, 0, 0, 0, 0, 0, 0, 0, 0, 0.56, '2023', 1),
(7, '21070122134', 'Saksham Gupta', 'sy', 'cs', 'c2', 'Ram Kishan Lodhi', 'Introduction to Small Data', 5, 0, 0, 0, 0, 0, 0, 0, 0, 0.56, '2023', 1),
(8, '21070122134', 'Saksham Gupta', 'sy', 'cs', 'c2', 'Test General Elective', 'General Elective', 5, 0, 0, 0, 0, 0, 0, 0, 0, 0.56, '2023', 1),
(9, '21070122134', 'Saksham Gupta', 'sy', 'cs', 'c2', 'Dr Sonali Mahendra Kothari', 'Flexi JAVA', 5, 5, 5, 5, 5, 5, 1, 5, 5, 4.55, '2023', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cs_student`
--

CREATE TABLE `cs_student` (
  `id` int(11) NOT NULL,
  `prn` varchar(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `open` varchar(1000) NOT NULL,
  `general` varchar(1000) NOT NULL,
  `acad_year` varchar(4) NOT NULL,
  `branch` varchar(4) NOT NULL,
  `class` varchar(2) NOT NULL,
  `semester` int(1) NOT NULL,
  `crnt_year` year(4) NOT NULL,
  `is_valid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cs_student`
--

INSERT INTO `cs_student` (`id`, `prn`, `name`, `email`, `open`, `general`, `acad_year`, `branch`, `class`, `semester`, `crnt_year`, `is_valid`) VALUES
(1, '21070122193', 'Swayam Kiran Pendgaonkar', 'swayam.pendgaonkar.btech2021@sitpune.edu.in', 'Data Science', 'General Elective', 'sy', 'cs', 'c2', 4, '2023', 1),
(2, '21070122134', 'Saksham Gupta', 'saksham.gupta.btech2021@sitpune.edu.in', 'Data Science', 'General Elective', 'sy', 'cs', 'c2', 4, '2023', 1),
(3, '21070122191', 'Yashika Jaiswal', 'yashika.jaiswal.btech2021@sitpune.edu.in', 'Data Science', 'General Elective', 'sy', 'cs', 'c2', 4, '2023', 1),
(4, '21070122187', 'Bhaavesh Waykole', 'bhaavesh.waykole.btech2021@sitpune.edu.in', '', 'General Elective', 'sy', 'cs', 'c2', 5, '2023', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cs_teacher`
--

CREATE TABLE `cs_teacher` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `name` varchar(500) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `acad_year` varchar(10) NOT NULL,
  `branch` varchar(10) NOT NULL,
  `class` varchar(10) NOT NULL,
  `is_valid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cs_teacher`
--

INSERT INTO `cs_teacher` (`id`, `email`, `name`, `subject`, `acad_year`, `branch`, `class`, `is_valid`) VALUES
(3, 'abc@gmail.com', 'Test General Elective', 'General Elective', 'sy', 'cs', 'c', 1),
(4, 'sonali.kothari@sitpune.edu.in', 'Dr Sonali Mahendra Kothari', 'Flexi JAVA', 'sy', 'cs', 'a1', 1),
(6, 'sonali.kothari@sitpune.edu.in', 'Dr Sonali Mahendra Kothari', 'Flexi JAVA', 'sy', 'cs', 'a3', 1),
(7, 'sonali.kothari@sitpune.edu.in', 'Dr Sonali Mahendra Kothari', 'Flexi JAVA', 'sy', 'cs', 'c1', 1),
(8, 'sonali.kothari@sitpune.edu.in', 'Dr Sonali Mahendra Kothari', 'Flexi JAVA', 'sy', 'cs', 'c2', 1),
(9, 'sonali.kothari@sitpune.edu.in', 'Dr Sonali Mahendra Kothari', 'Flexi JAVA', 'sy', 'cs', 'c3', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fy_feedback`
--

CREATE TABLE `fy_feedback` (
  `id` int(11) NOT NULL DEFAULT 0,
  `prn` varchar(11) NOT NULL,
  `name` varchar(400) NOT NULL,
  `acad_year` varchar(4) NOT NULL,
  `branch` varchar(4) NOT NULL,
  `class` varchar(4) NOT NULL,
  `teacher` varchar(400) NOT NULL,
  `subject` varchar(400) NOT NULL,
  `q1` int(2) NOT NULL,
  `q2` int(2) NOT NULL,
  `q3` int(2) NOT NULL,
  `q4` int(2) NOT NULL,
  `q5` int(2) NOT NULL,
  `q6` int(2) NOT NULL,
  `q7` int(2) NOT NULL,
  `q8` int(2) NOT NULL,
  `q9` int(2) NOT NULL,
  `avg` decimal(5,2) NOT NULL,
  `valid_year` varchar(50) NOT NULL,
  `is_submitted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fy_student`
--

CREATE TABLE `fy_student` (
  `id` int(11) NOT NULL DEFAULT 0,
  `prn` varchar(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `acad_year` varchar(4) NOT NULL,
  `branch` varchar(4) NOT NULL,
  `class` varchar(2) NOT NULL,
  `semester` int(1) NOT NULL,
  `crnt_year` year(4) NOT NULL,
  `is_valid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fy_student`
--

INSERT INTO `fy_student` (`id`, `prn`, `name`, `email`, `acad_year`, `branch`, `class`, `semester`, `crnt_year`, `is_valid`) VALUES
(1, '22070126001', 'Aaradhya Badal', 'aaradhya.badal.btech2022@sitpune.edu.in', 'fy', 'ai', 'a1', 2, '2023', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fy_teacher`
--

CREATE TABLE `fy_teacher` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `name` varchar(500) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `acad_year` varchar(10) NOT NULL,
  `branch` varchar(10) NOT NULL,
  `class` varchar(10) NOT NULL,
  `is_valid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(15) NOT NULL,
  `acad_year` varchar(3) NOT NULL,
  `branch` varchar(20) NOT NULL,
  `class` varchar(20) NOT NULL,
  `semester` int(1) NOT NULL,
  `crnt_year` year(4) NOT NULL,
  `is_valid` tinyint(1) NOT NULL,
  `resettoken` varchar(1000) NOT NULL,
  `resettokenexp` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for storing login data';

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `email`, `password`, `role`, `acad_year`, `branch`, `class`, `semester`, `crnt_year`, `is_valid`, `resettoken`, `resettokenexp`) VALUES
(1, 'systemadmin@sitpune.edu.in', 'systemadmin@sitpune.edu.in', 'U3VwZXJBZG1pbkAxMjM=', 'superadmin', '', '', '', 0, '0000', 1, '2ddfb57c0268a0e27374f8d8374e104c', NULL),
(2, '21070122134', 'saksham.gupta.btech2021@sitpune.edu.in', 'MTIzNA==', 'student', 'sy', 'cs', 'c2', 4, '2023', 1, '', NULL),
(3, '21070122193', 'swayam.pendgaonkar.btech2021@sitpune.edu.in', 'MTIzNA==', 'student', 'sy', 'cs', 'C2', 4, '2023', 1, '', NULL),
(4, 'cscoordinator@sitpune.edu.in', 'cscoordinator@sitpune.edu.in', 'Y3Njb29yZGluYXRvckAxMjM=', 'coordinator', '', 'cs', '', 0, '2023', 1, '', NULL),
(5, '21070122191', 'yashika.jaiswal.btech2021@sitpune.edu.in', 'VEt2RA==', 'student', 'sy', 'cs', 'c2', 0, '2023', 1, '', NULL),
(6, '21070122187', 'bhaavesh.waykole.btech2021@sitpune.edu.in', 'U0F5bw==', 'student', 'sy', 'cs', 'c2', 5, '2023', 1, '', NULL),
(7, '21070122181', 'somebody@gmail.com', 'Wmt3dg==', 'student', 'sy', 'cs', 'c2', 8, '2023', 1, '', NULL),
(8, 'sonali.kothari@sitpune.edu.in', 'sonali.kothari@sitpune.edu.in', 'bDU5NA==', 'teacher', '', '', '', 0, '2023', 1, '', NULL),
(9, 'mechcoordinator@sitpune.edu.in', '', 'bWVjaGNvb3JkaW5hdG9yQDEyMw==', 'coordinator', '', 'mech', '', 0, '2023', 1, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mech_feedback`
--

CREATE TABLE `mech_feedback` (
  `id` int(11) NOT NULL,
  `prn` varchar(11) NOT NULL,
  `name` varchar(400) NOT NULL,
  `acad_year` varchar(4) NOT NULL,
  `branch` varchar(4) NOT NULL,
  `class` varchar(4) NOT NULL,
  `teacher` varchar(400) NOT NULL,
  `subject` varchar(400) NOT NULL,
  `q1` int(2) NOT NULL,
  `q2` int(2) NOT NULL,
  `q3` int(2) NOT NULL,
  `q4` int(2) NOT NULL,
  `q5` int(2) NOT NULL,
  `q6` int(2) NOT NULL,
  `q7` int(2) NOT NULL,
  `q8` int(2) NOT NULL,
  `q9` int(2) NOT NULL,
  `avg` decimal(5,2) NOT NULL,
  `valid_year` varchar(50) NOT NULL,
  `is_submitted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mech_student`
--

CREATE TABLE `mech_student` (
  `id` int(11) NOT NULL,
  `prn` varchar(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `open` varchar(1000) NOT NULL,
  `general` varchar(1000) NOT NULL,
  `acad_year` varchar(4) NOT NULL,
  `branch` varchar(4) NOT NULL,
  `class` varchar(2) NOT NULL,
  `semester` int(1) NOT NULL,
  `crnt_year` year(4) NOT NULL,
  `is_valid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mech_teacher`
--

CREATE TABLE `mech_teacher` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `name` varchar(500) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `acad_year` varchar(10) NOT NULL,
  `branch` varchar(10) NOT NULL,
  `class` varchar(10) NOT NULL,
  `is_valid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mech_teacher`
--

INSERT INTO `mech_teacher` (`id`, `email`, `name`, `subject`, `acad_year`, `branch`, `class`, `is_valid`) VALUES
(1, 'sonali.kothari@sitpune.edu.in', 'Dr Sonali Mahendra Kothari', 'Flexi JAVA', 'sy', 'mech', 'c', 1);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `questions` varchar(400) NOT NULL,
  `rating` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `is_valid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `questions`, `rating`, `type`, `is_valid`) VALUES
(2, 'Instructor was well prepared for the lectures?', '1-5', 'feedback', 1),
(3, 'Fundamental principles were well emphasized?', '1-5', 'feedback', 1),
(4, 'Peace of the instruction was given?', '1-5', 'feedback', 1),
(5, 'Course was fully covered?', '1-5', 'feedback', 1),
(6, 'Instructor could communicate effectively with the students?', '1-5', 'feedback', 1),
(7, 'Instructor encouraged questions and cleared doubts?', '1-5', 'feedback', 1),
(8, 'Instructor could be approached beyond normal lecture hours for assisting students?', '1-5', 'feedback', 1),
(9, 'All the allotted lectures were held till date?', '1-5', 'feedback', 1),
(10, 'Writing on the B/Board was visible?', '1-5', 'feedback', 1);

-- --------------------------------------------------------

--
-- Table structure for table `specialization`
--

CREATE TABLE `specialization` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `subject` varchar(2000) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `is_valid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specialization`
--

INSERT INTO `specialization` (`id`, `course_name`, `subject`, `name`, `email`, `branch`, `is_valid`) VALUES
(1, 'Data Science', 'Introduction to Big Data', 'Ram Kishan Lodhi', 'sakshamdev3@gmail.com', 'cs', 1),
(2, 'Data Science', 'Introduction to Small Data', 'Ram Kishan Lodhi', 'sakshamdev3@gmail.com', 'cs', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cs_feedback`
--
ALTER TABLE `cs_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cs_student`
--
ALTER TABLE `cs_student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cs_teacher`
--
ALTER TABLE `cs_teacher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fy_student`
--
ALTER TABLE `fy_student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fy_teacher`
--
ALTER TABLE `fy_teacher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `mech_feedback`
--
ALTER TABLE `mech_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mech_student`
--
ALTER TABLE `mech_student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mech_teacher`
--
ALTER TABLE `mech_teacher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `specialization`
--
ALTER TABLE `specialization`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cs_feedback`
--
ALTER TABLE `cs_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cs_student`
--
ALTER TABLE `cs_student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cs_teacher`
--
ALTER TABLE `cs_teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `fy_teacher`
--
ALTER TABLE `fy_teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mech_feedback`
--
ALTER TABLE `mech_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mech_student`
--
ALTER TABLE `mech_student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mech_teacher`
--
ALTER TABLE `mech_teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `specialization`
--
ALTER TABLE `specialization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
