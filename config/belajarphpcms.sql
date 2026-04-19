-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: belajarphpcms
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `contact_leads`
--

DROP TABLE IF EXISTS `contact_leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_leads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_leads`
--

LOCK TABLES `contact_leads` WRITE;
/*!40000 ALTER TABLE `contact_leads` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galleries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries`
--

LOCK TABLES `galleries` WRITE;
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
INSERT INTO `galleries` VALUES (16,'Nature','uploads/gallery/placeholder_3.jpg','General',NULL,'2026-03-20 20:34:21'),(17,'Urban','uploads/gallery/placeholder_4.jpg','General',NULL,'2026-03-20 20:34:21'),(18,'Architecture','uploads/gallery/placeholder_5.jpg','General',NULL,'2026-03-20 20:34:21'),(19,'Travel','uploads/gallery/placeholder_3.jpg','General',NULL,'2026-03-20 20:34:21'),(20,'Events','uploads/gallery/placeholder_4.jpg','General',NULL,'2026-03-20 20:34:21'),(21,'Portrait','uploads/gallery/placeholder_5.jpg','General',NULL,'2026-03-20 20:34:21');
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author_id` int DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext,
  `featured_image` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `news_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (8,1,'Tech Innovation 2024','tech-innovation-2024','Sample content for article 1','uploads/news/placeholder_2.jpg',1,'2026-03-20 20:34:21'),(9,1,'Market Update','market-update','Sample content for article 2','uploads/news/placeholder_2.jpg',1,'2026-03-20 20:34:21'),(10,1,'Sustainability Trends','sustainability-trends','Sample content for article 3','uploads/news/placeholder_2.jpg',1,'2026-03-20 20:34:21'),(11,1,'Future of AI','future-of-ai','Sample content for article 4','uploads/news/placeholder_2.jpg',1,'2026-03-20 20:34:21'),(12,1,'Remote Work Culture','remote-work-culture','Sample content for article 5','uploads/news/placeholder_2.jpg',1,'2026-03-20 20:34:21'),(13,1,'Global Economy Insights','global-economy-insights','Sample content for article 6','uploads/news/placeholder_2.jpg',1,'2026-03-20 20:34:21');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `payment_method` enum('transfer','va') NOT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `order_status` enum('pending','paid','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolio`
--

DROP TABLE IF EXISTS `portfolio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `client_name` varchar(100) DEFAULT NULL,
  `project_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolio`
--

LOCK TABLES `portfolio` WRITE;
/*!40000 ALTER TABLE `portfolio` DISABLE KEYS */;
INSERT INTO `portfolio` VALUES (40,'Modern Villa','Proj desc 1','uploads/portfolio/placeholder_1.jpg','Design',NULL,NULL,'2026-03-20 20:34:21'),(41,'Corporate Branding','Proj desc 2','uploads/portfolio/placeholder_4.jpg','Architecture',NULL,NULL,'2026-03-20 20:34:21'),(42,'E-commerce App','Proj desc 3','uploads/portfolio/placeholder_6.jpg','Design',NULL,NULL,'2026-03-20 20:34:21'),(43,'Smart City Plan','Proj desc 4','uploads/portfolio/placeholder_1.jpg','Architecture',NULL,NULL,'2026-03-20 20:34:21'),(44,'Organic Food Logo','Proj desc 5','uploads/portfolio/placeholder_4.jpg','Design',NULL,NULL,'2026-03-20 20:34:21'),(45,'Wellness Spa Design','Proj desc 6','uploads/portfolio/placeholder_6.jpg','Architecture',NULL,NULL,'2026-03-20 20:34:21');
/*!40000 ALTER TABLE `portfolio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `image_url` varchar(255) DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `external_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (39,'Smart Watch Pro','Descr 1','uploads/products/placeholder_1.jpg',2010957.00,NULL,NULL,'2026-03-20 20:34:21'),(40,'Wireless Earbuds','Descr 2','uploads/products/placeholder_3.jpg',550996.00,NULL,NULL,'2026-03-20 20:34:21'),(41,'Mechanical Keyboard','Descr 3','uploads/products/placeholder_1.jpg',3126523.00,NULL,NULL,'2026-03-20 20:34:21'),(42,'4K Monitor','Descr 4','uploads/products/placeholder_3.jpg',3841532.00,NULL,NULL,'2026-03-20 20:34:21'),(43,'Ergonomic Chair','Descr 5','uploads/products/placeholder_1.jpg',1198317.00,NULL,NULL,'2026-03-20 20:34:21'),(44,'Laptop Stand','Descr 6','uploads/products/placeholder_3.jpg',3421596.00,NULL,NULL,'2026-03-20 20:34:21');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `permissions` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','{\"sections\":\"true\",\"services\":\"true\",\"portfolio\":\"true\",\"products\":\"true\",\"news\":\"true\",\"orders\":\"true\",\"inquiries\":\"true\",\"leads\":\"true\",\"users\":\"true\",\"roles\":\"true\"}','2026-03-20 17:52:39'),(2,'Admin','{\"sections\":\"true\",\"services\":\"true\",\"portfolio\":\"true\",\"products\":\"true\",\"news\":\"true\",\"orders\":\"true\",\"inquiries\":\"true\",\"leads\":\"true\",\"users\":\"true\",\"roles\":\"true\"}','2026-03-20 17:52:39'),(3,'Author','{\"news\":\"true\"}','2026-03-20 17:52:39');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `section_key` varchar(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` text,
  `content` longtext,
  `is_visible` tinyint(1) DEFAULT '1',
  `order_position` int DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_key` (`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,'hero','Innovating Beyond Limits','We design and build bespoke digital solutions that drive growth and efficiency for modern enterprises.','Complete custom digital transformation architecture.',1,1),(2,'about','Who We Are','A passionate team of developers, designers, and strategists dedicated to excellence.','Over 10 years of experience delivering high-quality software solutions globally.',1,2),(3,'services','Our Specialized Services','Comprehensive technology offerings from cloud to cross-platform mobile apps.','Offering everything from UI/UX design to advanced AI model integration.',1,3),(4,'portfolio','Our Recent Masterpieces','A showcase of our most impactful and technically challenging projects.','Exploring the intersection of design and robust engineering.',1,4),(5,'blog','Latest Tech Insights','Knowledge sharing from our industry-leading experts.','Covering AI trends, cybersecurity, and modern dev stacks.',1,5),(6,'contact','Get In Touch','Let\'s collaborate on your next big idea.','Ready to scale? Our team is standing by to help you succeed.',1,6);
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_inquiries`
--

DROP TABLE IF EXISTS `service_inquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_inquiries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `service_id` int DEFAULT NULL,
  `portfolio_id` int DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `inquiry_type` enum('service','portfolio','product') NOT NULL,
  `status` enum('new','responded','deal','rejected') DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `product_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`),
  KEY `portfolio_id` (`portfolio_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `service_inquiries_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  CONSTRAINT `service_inquiries_ibfk_2` FOREIGN KEY (`portfolio_id`) REFERENCES `portfolio` (`id`) ON DELETE SET NULL,
  CONSTRAINT `service_inquiries_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_inquiries`
--

LOCK TABLES `service_inquiries` WRITE;
/*!40000 ALTER TABLE `service_inquiries` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_inquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `icon` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (32,'Custom Software Development','We build scalable, robust, and secure custom software solutions tailored to your unique business requirements, ensuring seamless integration and optimal performance.','fa-solid fa-code','2026-03-20 20:48:35'),(33,'Cloud Infrastructure & DevOps','Our cloud experts architect, migrate, and manage your infrastructure on top cloud platforms. We implement DevOps practices to automate and streamline your deployments.','fa-solid fa-cloud','2026-03-20 20:48:35'),(34,'Mobile Application Development','Deliver exceptional user experiences with our native and cross-platform mobile application development services for iOS and Android devices.','fa-solid fa-mobile-screen-button','2026-03-20 20:48:35'),(35,'Data Analytics & Business Intelligence','Transform raw data into actionable insights with our comprehensive data analytics and visualization services to drive informed decision-making.','fa-solid fa-chart-pie','2026-03-20 20:48:35'),(36,'Cyber Security Operations Center','Protect your digital assets with our advanced cybersecurity services, including vulnerability assessments, threat detection, and continuous monitoring.','fa-solid fa-shield-halved','2026-03-20 20:48:35'),(37,'Artificial Intelligence & Machine Learning','Leverage AI and ML algorithms to automate processes, predict trends, and create intelligent systems that continuously learn and adapt.','fa-solid fa-brain','2026-03-20 20:48:35');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site_name','Tech Solutions','2026-03-28 13:00:21'),(2,'site_description','Premium Software Development & Digital Agency','2026-03-20 19:58:34'),(3,'contact_email','hello@softco.tech','2026-03-20 19:58:34'),(4,'contact_phone','+62 21 555 0123','2026-03-20 19:58:34'),(5,'address','Level 42, Equity Tower, Sudirman Central Business District, Jakarta','2026-03-20 19:58:34'),(6,'facebook_url','https://facebook.com/softco','2026-03-20 19:58:34'),(7,'twitter_url','https://twitter.com/softco','2026-03-20 19:58:34'),(8,'instagram_url','https://instagram.com/softco','2026-03-20 19:58:34'),(9,'linkedin_url','https://linkedin.com/company/softco','2026-03-20 19:58:34'),(10,'hero_title','We Build Future-Proof Digital Experiences','2026-03-20 19:58:34'),(11,'hero_subtitle','Leading software development agency specializing in AI, Cloud, and Premium Web Solutions.','2026-03-20 19:58:34'),(12,'footer_text','┬® 2024 SoftCo Tech Solutions. All Rights Reserved.','2026-03-20 19:58:34'),(14,'site_motto','Innovating Software Solutions','2026-03-20 19:58:56'),(15,'site_email','hello@softco.tech','2026-03-20 19:58:56'),(16,'site_phone','+62 812 3456 7890','2026-03-20 19:58:56'),(17,'site_address','Jakarta, Indonesia','2026-03-20 19:58:56'),(18,'primary_color','#0066ff','2026-03-20 19:58:56'),(24,'theme_mode','dark','2026-03-28 12:59:35');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_name` varchar(100) DEFAULT NULL,
  `client_company` varchar(100) DEFAULT NULL,
  `client_image` varchar(255) DEFAULT NULL,
  `content` text,
  `rating` int DEFAULT '5',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (16,'Alice Johnson','Company A','uploads/testimonials/placeholder_1.jpg','Great work!',5,'2026-03-20 20:34:21'),(17,'Bob Smith','Company B','uploads/testimonials/placeholder_3.jpg','Great work!',5,'2026-03-20 20:34:21'),(18,'Charlie Davis','Company C','uploads/testimonials/placeholder_5.jpg','Great work!',5,'2026-03-20 20:34:21'),(19,'Diana Prince','Company D','uploads/testimonials/placeholder_6.jpg','Great work!',5,'2026-03-20 20:34:21'),(20,'Ethan Hunt','Company E','uploads/testimonials/placeholder_1.jpg','Great work!',5,'2026-03-20 20:34:21'),(21,'Fiona Gallagher','Company F','uploads/testimonials/placeholder_3.jpg','Great work!',5,'2026-03-20 20:34:21');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'superadmin','admin@softco.tech','$2y$10$fTmTDJ/IuATRdzKG5BPQ5OK9.ZCw9PH9xUZ64XBgE5CP7EDFZMZ/W','John Doe - CEO','2026-03-20 17:52:39');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `visitors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visit_date` date NOT NULL,
  `hits` int DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_visit` (`ip_address`,`visit_date`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitors`
--

LOCK TABLES `visitors` WRITE;
/*!40000 ALTER TABLE `visitors` DISABLE KEYS */;
INSERT INTO `visitors` VALUES (1,'127.0.0.1','2026-03-28',5,'2026-03-28 22:22:26','2026-03-28 22:36:45'),(6,'127.0.0.1','2026-03-29',15,'2026-03-29 16:41:55','2026-03-29 17:14:09'),(21,'127.0.0.1','2026-03-31',35,'2026-03-31 12:00:12','2026-03-31 12:43:29'),(56,'127.0.0.1','2026-04-07',1,'2026-04-08 00:31:51','2026-04-08 00:31:51');
/*!40000 ALTER TABLE `visitors` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-08  0:33:33
