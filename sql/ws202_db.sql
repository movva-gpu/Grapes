-- MySQL dump 10.19  Distrib 10.3.39-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ws202_db
-- ------------------------------------------------------
-- Server version	10.3.39-MariaDB

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
-- Table structure for table `gardens`
--

DROP TABLE IF EXISTS `gardens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gardens` (
  `garden_id` int(11) NOT NULL AUTO_INCREMENT,
  `garden_name` varchar(30) NOT NULL,
  `garden_lat` float NOT NULL,
  `garden_long` float NOT NULL,
  `garden_street_name` varchar(80) NOT NULL,
  `garden_street_number` char(6) NOT NULL,
  `garden_size` float NOT NULL,
  `garden_n_plots` int(11) NOT NULL,
  `_user_reserving` longtext DEFAULT NULL,
  `garden_is_added_by_user` tinyint(1) NOT NULL,
  `_user_id` int(11) DEFAULT NULL,
  `garden_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `garden_last_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`garden_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gardens`
--

LOCK TABLES `gardens` WRITE;
/*!40000 ALTER TABLE `gardens` DISABLE KEYS */;
INSERT INTO `gardens` VALUES (11,'Le jardin des Canards',48.2691,4.07972,'Rue Qu&eacute;bec','9',50,50,NULL,1,24,'2024-06-17 09:55:06','2024-06-17 09:55:06'),(10,'Le super jardin de Danyella',48.2833,4.06969,'Rue Alexander Fleming','9',24,10,',25:0',1,24,'2024-06-17 09:53:18','2024-06-18 09:33:41'),(13,'Le jardin de Gogo',48.285,4.05762,'La rue...','12',12,12,NULL,1,25,'2024-06-18 08:22:33','2024-06-18 09:39:09');
/*!40000 ALTER TABLE `gardens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_uuid` varchar(27) NOT NULL,
  `user_last_name` varchar(50) NOT NULL,
  `user_first_name` varchar(50) NOT NULL,
  `user_nickname` varchar(42) DEFAULT NULL,
  `user_display_name` tinyint(10) unsigned NOT NULL DEFAULT 0,
  `user_pronouns` varchar(40) DEFAULT NULL,
  `user_email` varchar(128) NOT NULL,
  `user_password_hash` varchar(60) NOT NULL,
  `user_gender` varchar(2) NOT NULL,
  `user_biography` tinytext DEFAULT NULL,
  `user_validated` tinyint(1) NOT NULL DEFAULT 0,
  `user_validation_token` varchar(60) DEFAULT NULL,
  `user_profile_picture_filename` varchar(60) NOT NULL DEFAULT '000',
  `user_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_last_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`),
  UNIQUE KEY `user_uuid` (`user_uuid`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (24,'usr_6670db00528b13.23760180','Strikann','Danyella','Allenyade',1,'','erathecuthead@gmail.com','$2y$10$FRBmjQSm9vef26PW.KDryetAKlyWwR1G3vkKIFKFB7mj08ii5rPS6','f',NULL,1,NULL,'pfp_20240617_83112','2024-06-17 08:02:10','2024-06-18 00:56:05'),(23,'usr_6670db1b3c4f30.84226575','Jardin','Nicolas','Nicos',0,NULL,'jardin@mmi-troyes.fr','$2y$10$0JGf7uhxE1Gdq.v1/xYFmeQ4RWgSvai4wE8QEYyZYyd1HcGGkcKAS','no',NULL,0,'3a3d0a2ec12d5d485a4f2bc98caf47d8a2ae0191f6f013cc9a1ac4d4f64f','000','2024-06-16 20:38:45','2024-06-18 00:56:08'),(25,'usr_667141b03fa942.96127576','DeGaulle','Charles','Gaugau',0,'il/lui','allenyade.pro+gogo@gmail.com','$2y$10$mskQ72Sd0djHNefzBZJekO4j7wc8mbnI.KwheDYWhXfTlkPqwRFPi','m',NULL,1,NULL,'pfp_20240618_82422','2024-06-18 08:13:36','2024-06-18 08:24:23');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-18 14:04:58
