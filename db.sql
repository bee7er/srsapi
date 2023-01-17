-- MySQL dump 10.13  Distrib 5.7.40, for Linux (x86_64)
--
-- Host: localhost    Database: srs
-- ------------------------------------------------------
-- Server version	5.7.40-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('2022_11_19_000000_create_users_table',1),('2022_11_19_100000_create_password_resets_table',1),('2022_11_19_200000_create_renders_table',1),('2022_11_19_300000_create_render_details_table',1),('2023_01_16_100000_add_api_token_users_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `render_details`
--

DROP TABLE IF EXISTS `render_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `render_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `render_id` int(10) unsigned NOT NULL,
  `allocated_to_user_id` int(10) unsigned NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `status` enum('ready','allocated','done','returned') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `render_details_render_id_index` (`render_id`),
  KEY `render_details_allocated_to_user_id_index` (`allocated_to_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `render_details`
--

LOCK TABLES `render_details` WRITE;
/*!40000 ALTER TABLE `render_details` DISABLE KEYS */;
INSERT INTO `render_details` VALUES (61,11,0,0,0,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(62,11,0,0,1,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(63,11,0,0,4,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(64,11,0,0,4,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(65,11,0,5,5,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(66,11,0,0,4,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(67,11,0,5,6,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(68,11,0,0,4,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(69,11,0,5,7,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(70,11,0,0,4,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(71,11,0,5,8,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(72,11,0,0,4,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(73,11,0,5,9,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(74,11,0,0,4,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(75,11,0,5,9,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(76,11,0,10,10,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(77,11,0,0,4,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(78,11,0,5,9,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(79,11,0,10,13,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(80,11,0,0,4,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(81,11,0,5,9,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(82,11,0,10,14,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(83,11,0,15,15,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(84,11,0,0,4,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(85,11,0,5,9,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(86,11,0,10,14,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(87,11,0,15,17,'ready','2022-12-25 13:45:30','2022-12-25 13:45:30'),(88,3,1,1,4,'returned','2022-12-25 13:45:30','2022-12-26 18:05:50'),(89,3,2,5,8,'done','2022-12-25 13:45:30','2023-01-09 14:18:38'),(90,3,2,14,18,'done','2022-12-25 13:45:30','2023-01-09 14:18:49'),(91,3,2,19,23,'done','2022-12-25 13:45:30','2022-12-26 18:05:50'),(92,11,0,0,0,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(93,11,0,0,1,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(94,11,0,0,4,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(95,11,0,0,4,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(96,11,0,5,5,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(97,11,0,0,4,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(98,11,0,5,6,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(99,11,0,0,4,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(100,11,0,5,7,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(101,11,0,0,4,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(102,11,0,5,8,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(103,11,0,0,4,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(104,11,0,5,9,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(105,11,0,0,4,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(106,11,0,5,9,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(107,11,0,10,10,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(108,11,0,0,4,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(109,11,0,5,9,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(110,11,0,10,13,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(111,11,0,0,4,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(112,11,0,5,9,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(113,11,0,10,14,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(114,11,0,15,15,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(115,11,0,0,4,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(116,11,0,5,9,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(117,11,0,10,14,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(118,11,0,15,17,'ready','2022-12-26 18:07:50','2022-12-26 18:07:50'),(119,4,2,45,49,'returned','2022-12-26 18:07:50','2022-12-26 18:08:06'),(120,4,2,50,50,'returned','2022-12-26 18:07:50','2022-12-26 18:08:06'),(121,11,0,0,0,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(122,11,0,0,1,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(123,11,0,0,4,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(124,11,0,0,4,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(125,11,0,5,5,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(126,11,0,0,4,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(127,11,0,5,6,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(128,11,0,0,4,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(129,11,0,5,7,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(130,11,0,0,4,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(131,11,0,5,8,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(132,11,0,0,4,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(133,11,0,5,9,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(134,11,0,0,4,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(135,11,0,5,9,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(136,11,0,10,10,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(137,11,0,0,4,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(138,11,0,5,9,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(139,11,0,10,13,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(140,11,0,0,4,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(141,11,0,5,9,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(142,11,0,10,14,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(143,11,0,15,15,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(144,11,0,0,4,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(145,11,0,5,9,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(146,11,0,10,14,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(147,11,0,15,17,'ready','2023-01-04 14:20:47','2023-01-04 14:20:47'),(148,5,2,45,49,'returned','2023-01-04 14:20:47','2023-01-16 17:29:27'),(149,5,2,50,50,'returned','2023-01-04 14:20:47','2023-01-16 17:29:27'),(150,11,0,0,0,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(151,11,0,0,1,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(152,11,0,0,4,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(153,11,0,0,4,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(154,11,0,5,5,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(155,11,0,0,4,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(156,11,0,5,6,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(157,11,0,0,4,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(158,11,0,5,7,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(159,11,0,0,4,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(160,11,0,5,8,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(161,11,0,0,4,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(162,11,0,5,9,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(163,11,0,0,4,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(164,11,0,5,9,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(165,11,0,10,10,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(166,11,0,0,4,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(167,11,0,5,9,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(168,11,0,10,13,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(169,11,0,0,4,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(170,11,0,5,9,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(171,11,0,10,14,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(172,11,0,15,15,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(173,11,0,0,4,'ready','2023-01-04 14:28:06','2023-01-04 14:28:06'),(174,11,0,5,9,'ready','2023-01-04 14:28:07','2023-01-04 14:28:07'),(175,11,0,10,14,'ready','2023-01-04 14:28:07','2023-01-04 14:28:07'),(176,11,0,15,17,'ready','2023-01-04 14:28:07','2023-01-04 14:28:07'),(177,6,2,45,49,'returned','2023-01-04 14:28:07','2023-01-16 17:29:27'),(178,6,2,50,50,'returned','2023-01-04 14:28:07','2023-01-16 17:29:27'),(179,6,2,15,15,'returned','2023-01-04 14:28:07','2023-01-16 17:29:27');
/*!40000 ALTER TABLE `render_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `renders`
--

DROP TABLE IF EXISTS `renders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `renders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `submitted_by_user_id` int(10) unsigned NOT NULL,
  `status` enum('open','ready','rendering','complete','returned') COLLATE utf8_unicode_ci NOT NULL,
  `c4dProjectWithAssets` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `outputFormat` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `overrideSettings` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customFrameRanges` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `renders_submitted_by_user_id_index` (`submitted_by_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `renders`
--

LOCK TABLES `renders` WRITE;
/*!40000 ALTER TABLE `renders` DISABLE KEYS */;
INSERT INTO `renders` VALUES (3,2,'rendering','RedshiftTestBeWA.c4d','PNG',0,0,'1','1-4,5-8,14-23','2022-12-26 18:05:49','2022-12-25 13:45:30','2023-01-09 14:18:31'),(4,2,'returned','RedshiftTestBeWA.c4d','PNG',0,90,'1','45-50','2022-12-26 18:08:06','2022-12-26 18:07:50','2022-12-26 18:08:06'),(5,2,'returned','RedshiftTestBeWA.c4d','PNG',0,90,'1','45-50','2023-01-16 17:23:25','2023-01-04 14:20:47','2023-01-16 17:29:31'),(6,2,'returned','RedshiftTestBeWA.c4d','PNG',0,90,'1','45-50,15-15','2023-01-16 17:28:28','2023-01-04 14:28:07','2023-01-16 17:29:27');
/*!40000 ALTER TABLE `renders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('available','unavailable','rendering') COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `api_token` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_api_token_unique` (`api_token`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'brian','etheridge','betheridge@gmail.com','admin','available','$2y$10$Kwc7u4h.9B.OJdxhlrtlMuCFHyl/tYV9d2ikvXLoySSY/JtWNiUSC','WxhtuADUQCA0LroDLF5OoFkPvXtQ9LEd8CosCnAvVcilB7ulxFqh5qiK1iMzmWrqCUwWzfSlNfSk1hRo',NULL,'2022-12-23 14:37:14','2023-01-04 15:34:09'),(2,'barry','fiddlestone','contact_bee@yahoo.com','admin','available','$2y$10$lUbAdQ8gDy5TlgEMGUDQVOYhurndCsDZCzXFD.tm0p3tG5mXG//hG','fl9ltqesXqPi4EkSj8M498ZBYYq3WOcCCZ1A9fDYQlbeNEmdzyyf2rGFpNR0gDGB7IswfX3pRSLuoDBF','iS01B85BrZJldu9OYkBASKeE7KG7WIpJ9QyNhXcGHCPEN39Dd0bMA7FmCVlW','2022-12-23 14:37:14','2023-01-09 14:18:26'),(3,'Fred','Flintstone','contact_bee+9098@yahoo.com','admin','available','Candoobly9','dgDMAmofu0NAkbY7S8oEicOupL8okT81wHPIapRObZGwsoH0hTLUMLtQaVVq4X58wVOSB94Bzy4hmpBk',NULL,'2023-01-16 17:13:18','2023-01-16 17:13:18');
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

-- Dump completed on 2023-01-16 19:49:13
