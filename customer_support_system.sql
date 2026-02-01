-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: customer_support_system
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `customer_acc`
--

DROP TABLE IF EXISTS `customer_acc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_acc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_acc`
--

LOCK TABLES `customer_acc` WRITE;
/*!40000 ALTER TABLE `customer_acc` DISABLE KEYS */;
INSERT INTO `customer_acc` VALUES (3,'sagar','sagar@123','$2y$10$GHwNdb7n1hqBM7MCBtLufOuDwA9SU7Bn0HpjQY2sp6QtNJAHVid0S','user'),(4,'rohan','opsagar0246@gmail.com','$2y$10$TfMwqyVursFPkqTP8AzyTOA00tlJSX6JpwDpsKKmpoUPHdr0GW4fO','user'),(5,'admin','admin@gmail.com','$2y$10$a7m5tB8yScinHvudYkGFKukmK9P4BDYxnfaa2rEVn1p/rYgmUhaJ2','admin'),(8,'sagar@1234','sagar@1234','$2y$10$UgXHyiyo5sL80pGGacBP1.j7lhvZvxwRDyH6DdAtPZv1yAYvDPHsy','user'),(10,'rohit','rohit@123','$2y$10$EdQ1KWFSCgvw9gi3fJLni.ngDKyUEnXA1DtwHx2XR.e1eLxucZvFW','user'),(12,'rohan','rohan@123','$2y$10$1zQkZkbhAneJ8NCRHGmyf.hgenECtLmhfZI4/zhXolbudKeejqf7u','user'),(13,'test','test@gmail.com','$2y$10$yKsebMBqMdmIhP5.ngyDdO7RirmMfVswrHvEXklUy.INjb1h5JJqm','user'),(14,'chaurasiya','charasiyasagar@123','$2y$10$6pZxyK/vzQtddepIxxStt.zvAmu6Yju5lIwRwjsxGRXnI6R6YxBom','user'),(15,'chaurasiya','chaurasiyasagar@123','$2y$10$eeDgBcwcTbkZlf25s58U2uF0KjoHgjz5agXTfRlAK7GsZmBt8HeAe','user'),(20,'kashif','kashif@gmail.com','$2y$10$lcT/1EWwRglRSBMmQLaRV.MFQBDd7diS0IMqZgI5vVK.Hl9ZNmMeC','user');
/*!40000 ALTER TABLE `customer_acc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `issue_type` varchar(100) DEFAULT NULL,
  `priority` enum('Low','Medium','High') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Open','In Progress','Closed') DEFAULT 'Open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES (1,3,'Login problem','Unable ot login into my account','High','shows error when I login ','Open','2026-01-23 12:57:51'),(3,3,'data','Unable to view my data','Low','sddfgsdf','Closed','2026-01-23 13:29:49'),(4,4,'website','website being laggy','High','When I view my data website becomes laggy','In Progress','2026-01-23 14:33:44'),(5,13,'Login problem','Unable to view my data','Medium','dwadawfdwa','In Progress','2026-01-27 07:54:22'),(7,20,'Login problem','Unable ot login into my account','Low','bvbnmm.,','In Progress','2026-01-31 17:39:35'),(8,20,'data','Unable ot login into my account','Low','jbjbj','Open','2026-02-01 04:29:22');
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-01 12:21:22
