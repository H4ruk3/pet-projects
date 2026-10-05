-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: shop
-- ------------------------------------------------------
-- Server version	8.1.0

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
-- Temporary view structure for view `active_orders`
--

use shop;

DROP TABLE IF EXISTS `active_orders`;
/*!50001 DROP VIEW IF EXISTS `active_orders`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `active_orders` AS SELECT 
 1 AS `id_order`,
 1 AS `order date`,
 1 AS `name`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `id_category` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`id_category`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (3,'Канцелярские мелочи'),(4,'Конторское оборудование '),(2,'Папки и системы архивации'),(1,'Письменные принадлежности');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client` (
  `id_client` int NOT NULL AUTO_INCREMENT,
  `surname` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `otch` varchar(45) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=1236 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (1,'Агафонов','Иван','Федорович','+7(953)634-11-20','agafonov@mail.ru'),(2,'Куницына','Вероника','Игоревна','+7(922)356-23-98','kunizina@yandex.ru'),(3,'Логвинов','Андрей','Артёмович','+7(933)234-24-18','logvinov1988@gmail.com'),(4,'Плахов','Дмитрий','Васильевич','+7(999)856-11-25','plahov@inbox.ru'),(5,'Карлов','Виктор','Петрович','+7(910)311-23-15','karlov2022@gmail.com'),(1234,'Kuzhkov','Alexander^2','bebebe','phone','email'),(1235,'Kuzhkov','Alexander^2','bebebe','phone','email');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `client_orders`
--

DROP TABLE IF EXISTS `client_orders`;
/*!50001 DROP VIEW IF EXISTS `client_orders`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `client_orders` AS SELECT 
 1 AS `surname`,
 1 AS `name`,
 1 AS `ord_date`,
 1 AS `tovar`,
 1 AS `proizv`,
 1 AS `cost`,
 1 AS `kolvo`,
 1 AS `status`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!50001 DROP VIEW IF EXISTS `clients`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `clients` AS SELECT 
 1 AS `id_client`,
 1 AS `surname`,
 1 AS `name`,
 1 AS `otch`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `order_tovar`
--

DROP TABLE IF EXISTS `order_tovar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_tovar` (
  `artikul` int NOT NULL,
  `id_order` int NOT NULL,
  `kolvo` int DEFAULT NULL,
  `cost` float DEFAULT NULL,
  PRIMARY KEY (`artikul`,`id_order`),
  KEY `fk_order_tovar_tovar` (`id_order`),
  CONSTRAINT `fk_order_tovar_order` FOREIGN KEY (`artikul`) REFERENCES `tovar` (`artikul`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_order_tovar_tovar` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_tovar`
--

LOCK TABLES `order_tovar` WRITE;
/*!40000 ALTER TABLE `order_tovar` DISABLE KEYS */;
INSERT INTO `order_tovar` VALUES (152115,1002,50,NULL),(152115,1003,30,NULL),(152115,5051,1,NULL),(220949,1005,10,NULL),(224342,1004,10,NULL),(224610,1005,15,NULL),(227188,1005,20,NULL);
/*!40000 ALTER TABLE `order_tovar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id_order` int NOT NULL AUTO_INCREMENT,
  `ord_date` datetime NOT NULL,
  `id_client` int DEFAULT NULL,
  `id_status` int NOT NULL,
  PRIMARY KEY (`id_order`),
  KEY `fk_orders_client` (`id_client`),
  KEY `fk_orders_status` (`id_status`),
  CONSTRAINT `fk_orders_client` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_orders_status` FOREIGN KEY (`id_status`) REFERENCES `status` (`id_status`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5052 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1002,'2022-10-17 00:00:00',2,3),(1003,'2022-10-25 00:00:00',3,3),(1004,'2022-12-07 00:00:00',4,2),(1005,'2022-12-08 00:00:00',5,1),(5051,'2024-05-20 17:36:48',1235,3);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `shop_orders`
--

DROP TABLE IF EXISTS `shop_orders`;
/*!50001 DROP VIEW IF EXISTS `shop_orders`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `shop_orders` AS SELECT 
 1 AS `client`,
 1 AS `order`,
 1 AS `ord_date`,
 1 AS `tovar`,
 1 AS `proizv`,
 1 AS `cost`,
 1 AS `kolvo`,
 1 AS `status`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status` (
  `id_status` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id_status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (1,'Заказан'),(2,'Собран'),(3,'Выдан');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tovar`
--

DROP TABLE IF EXISTS `tovar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tovar` (
  `artikul` int NOT NULL,
  `name` varchar(200) NOT NULL,
  `proizv` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `cost` float NOT NULL,
  `ost` int NOT NULL,
  `id_category` int DEFAULT NULL,
  PRIMARY KEY (`artikul`),
  KEY `fk_tovar_category` (`id_category`),
  CONSTRAINT `fk_tovar_category` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tovar`
--

LOCK TABLES `tovar` WRITE;
/*!40000 ALTER TABLE `tovar` DISABLE KEYS */;
INSERT INTO `tovar` VALUES (143562,'Ручка шариковая BRAUBERG «ULTRA ORANGE», СИНЯЯ','BRAUBERG','Россия',7.44,9,1),(152115,'Маркер стираемый для белой доски СИНИЙ, BRAUBERG «CLASSIC»','BRAUBERG','Россия',35.09,4,1),(220949,'Скобы для степлера №10, 1000 штук','BRAUBERG','Россия',16.94,12,4),(224342,'Дырокол металлический BRAUBERG «Metallic», до 40 листов','BRAUBERG','Россия',872.31,7,4),(224610,'Зажимы для бумаг большие STAFF «EVERYDAY», КОМПЛЕКТ 12 шт., 51 мм, на 230','STAFF','Россия',110.39,100,3),(227188,'Папка-регистратор BRAUBERG, усиленный корешок, мраморное покрытие, 80 мм','BRAUBERG','Россия',184.46,20,2),(229083,'Степлер №10 BRAUBERG «Extra», до 20 листов','BRAUBERG','Россия',93.56,3,4),(322322,'name','pr','werwe',123123,3214,1),(1515415,'Карандаш «СОН-I-NOR», простой, мягкий','COH-I-NOR','чехия',15.44,10,1),(1521225,'Карандаш «СОН-I-NOR», простой, твердый','COH-I-NOR','чехия',18.09,15,1),(2243142,'Ручка подарочная PARKER, перьевая','PARKER','США',1272.31,3,1);
/*!40000 ALTER TABLE `tovar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'shop'
--

--
-- Dumping routines for database 'shop'
--
/*!50003 DROP PROCEDURE IF EXISTS `insert_tovar` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_tovar`(IN id_insertorder INT, insertartikul varchar(10), cnt int)
begin
	insert into order_tovar (artikul, id_order, kolvo) values (insertartikul, id_insertorder, cnt);
    update tovar set ost = ost-cnt where artikul = insertartikul;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `active_orders`
--

/*!50001 DROP VIEW IF EXISTS `active_orders`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `active_orders` AS select `o`.`id_order` AS `id_order`,cast(`o`.`ord_date` as date) AS `order date`,`s`.`name` AS `name` from (`orders` `o` join `status` `s` on((`o`.`id_status` = `s`.`id_status`))) where (`s`.`name` <> 'Выдан') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `client_orders`
--

/*!50001 DROP VIEW IF EXISTS `client_orders`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `client_orders` AS select `c`.`surname` AS `surname`,`c`.`name` AS `name`,`so`.`ord_date` AS `ord_date`,`so`.`tovar` AS `tovar`,`so`.`proizv` AS `proizv`,`so`.`cost` AS `cost`,`so`.`kolvo` AS `kolvo`,`so`.`status` AS `status` from (`client` `c` join `shop_orders` `so`) where (`c`.`id_client` = `so`.`client`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `clients`
--

/*!50001 DROP VIEW IF EXISTS `clients`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `clients` AS select `client`.`id_client` AS `id_client`,`client`.`surname` AS `surname`,`client`.`name` AS `name`,`client`.`otch` AS `otch` from `client` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `shop_orders`
--

/*!50001 DROP VIEW IF EXISTS `shop_orders`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `shop_orders` AS select `o`.`id_client` AS `client`,`o`.`id_order` AS `order`,`o`.`ord_date` AS `ord_date`,`t`.`name` AS `tovar`,`t`.`proizv` AS `proizv`,`t`.`cost` AS `cost`,`ot`.`kolvo` AS `kolvo`,`s`.`name` AS `status` from (((`orders` `o` join `order_tovar` `ot`) join `tovar` `t`) join `status` `s`) where ((`t`.`artikul` = `ot`.`artikul`) and (`o`.`id_order` = `ot`.`id_order`) and (`o`.`id_status` = `s`.`id_status`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-20 18:04:15
