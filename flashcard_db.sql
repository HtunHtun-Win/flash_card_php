-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: flashcard_db
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `card_options`
--

DROP TABLE IF EXISTS `card_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `card_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `card_id` bigint unsigned NOT NULL,
  `option_text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`),
  CONSTRAINT `card_options_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=493 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `card_options`
--

LOCK TABLES `card_options` WRITE;
/*!40000 ALTER TABLE `card_options` DISABLE KEYS */;
INSERT INTO `card_options` VALUES (388,44,'true'),(389,44,'false'),(390,44,'none'),(391,45,'true'),(392,45,'false'),(393,45,'none'),(394,46,'low level'),(395,46,'high level'),(396,46,'god level'),(442,41,'apple'),(443,41,'orange'),(444,41,'mango'),(445,42,'glasses'),(446,42,'cup'),(447,42,'bottle'),(448,43,'facebook'),(449,43,'book'),(450,43,'box'),(451,38,'2'),(452,38,'3'),(453,38,'4'),(454,39,'4'),(455,39,'5'),(456,39,'6'),(457,40,'true'),(458,40,'false'),(459,40,'none'),(469,50,'1'),(470,50,'2'),(471,50,'3'),(472,51,'1'),(473,51,'2'),(474,51,'3'),(475,52,'1'),(476,52,'2'),(477,52,'3'),(478,53,'haha'),(479,53,'hoho'),(480,53,'hehe'),(487,54,'1'),(488,54,'2'),(489,54,'3'),(490,55,'3'),(491,55,'4'),(492,55,'none');
/*!40000 ALTER TABLE `card_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `card_sets`
--

DROP TABLE IF EXISTS `card_sets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `card_sets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL DEFAULT '1',
  `name` varchar(255) DEFAULT NULL,
  `desc` text,
  `visibility` enum('public','private') DEFAULT 'public',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `card_sets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `card_sets_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `card_sets`
--

LOCK TABLES `card_sets` WRITE;
/*!40000 ALTER TABLE `card_sets` DISABLE KEYS */;
INSERT INTO `card_sets` VALUES (10,2,5,'Math Basic','Basic Math','public','2026-02-01 14:18:25'),(11,7,4,'English','English Basic','public','2026-02-04 14:12:06'),(12,1,2,'Quiz1','Quiz','public','2026-02-04 14:22:06'),(14,1,1,'From Excel','From Excel','public','2026-02-19 03:01:25'),(15,1,3,'moe','haha','public','2026-02-19 03:03:14');
/*!40000 ALTER TABLE `card_sets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `set_id` int DEFAULT NULL,
  `question` text,
  `answer` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `fk_cards_set` (`set_id`),
  KEY `fk_answer_option` (`answer`),
  CONSTRAINT `fk_answer_option` FOREIGN KEY (`answer`) REFERENCES `card_options` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_cards_set` FOREIGN KEY (`set_id`) REFERENCES `card_sets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cards`
--

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
INSERT INTO `cards` VALUES (38,10,'1 + 1  = ?',451,'2026-02-01 14:18:25'),(39,10,'5+1',456,'2026-02-01 14:18:25'),(40,10,'8=9',458,'2026-02-01 14:18:25'),(41,11,'ပန်းသီး',442,'2026-02-04 14:12:06'),(42,11,'ခွက်',446,'2026-02-04 14:12:06'),(43,11,'စာအုပ်',449,'2026-02-04 14:12:06'),(44,12,'Java have pointer',389,'2026-02-04 14:22:06'),(45,12,'python have no compiler',391,'2026-02-04 14:22:06'),(46,12,'Python is _____ Language',395,'2026-02-04 14:22:06'),(50,14,'one',469,'2026-02-19 03:01:25'),(51,14,'two',473,'2026-02-19 03:01:25'),(52,14,'three',477,'2026-02-19 03:01:25'),(53,14,'haha',478,'2026-02-19 03:01:25'),(54,15,'Most use in AI',487,'2026-02-19 03:03:14'),(55,15,'2+2',491,'2026-02-19 03:03:14');
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Auto','2026-02-04 15:17:18'),(2,'Programming','2026-02-04 15:13:38'),(3,'Artificial Intelligence','2026-02-04 15:13:38'),(4,'English','2026-02-04 15:17:18'),(5,'Math','2026-02-04 15:13:38');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(100) NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@gmail.com','21232f297a57a5a743894a0e4a801fc3','admin','2026-01-24 07:55:22'),(2,'htun','htun@gmail.com','e170fba0a8e605a77e7c159ea852370c','user','2026-01-24 07:55:22'),(7,'moe','moe@gmail.com','7f33334d4c2f6dd6ffc701944cec2f1c','user','2026-02-04 13:47:35');
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

-- Dump completed on 2026-02-19 10:01:05
