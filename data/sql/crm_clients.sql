-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: crm
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `pasport` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `nomber_d` varchar(255) NOT NULL,
  `data_d` date NOT NULL,
  `trafic` enum('Base','Family') NOT NULL,
  `coments` varchar(1023) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `date_ad` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pasport` (`pasport`),
  KEY `nomber_d` (`nomber_d`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'Иванов Иван Иванович','1234 567890','1985-03-15','1001','2023-02-10','Family','Оплатил за год вперёд.','1','0000-00-00 00:00:00'),(2,'Петрова Ольга Николаевна','4567 123456','1990-07-25','1002','2022-05-05','Base','Поменяла адрес прописки.','1','0000-00-00 00:00:00'),(3,'Сидоров Алексей Петрович','7890 456123','1978-11-03','1003','2024-04-20','Family','Переехал в другой город.','1','0000-00-00 00:00:00'),(4,'Козлова Елена Владимировна','2345 678901','1982-09-12','1004','2022-03-15','Family','Установила дополнительные опции.','1','0000-00-00 00:00:00'),(5,'Михайлов Игорь Дмитриевич','5678 901234','1975-06-30','1005','2022-01-01','Base','Уточнить платежные реквизиты.','1','0000-00-00 00:00:00'),(6,'Новикова Анна Сергеевна','8901 234567','1995-12-18','1006','2023-07-03','Family','Заменила контактный телефон.','1','0000-00-00 00:00:00'),(7,'Григорьев Павел Васильевич','1231 234567','1989-04-05','1007','2022-09-12','Base','Пропустил платёжный срок.','1','0000-00-00 00:00:00'),(8,'Лебедева Екатерина Александровна','4567 891234','1987-11-22','1008','2022-08-08','Base','Запрос на смену электронной почты.','1','0000-00-00 00:00:00'),(9,'Федоров Дмитрий Владимирович','7890 123456','1970-05-10','1009','2023-12-19','Family','Запрос на выставление счета на e-mail.','1','0000-00-00 00:00:00'),(10,'Степанова Наталья Алексеевна','2345 678912','1983-08-28','1010','2024-05-22','Family','Отключение услуги до конца месяца.','1','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-14 23:27:15
