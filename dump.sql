-- MySQL dump 10.13  Distrib 5.5.29, for osx10.6 (i386)
--
-- Host: 127.0.0.1    Database: opensource
-- ------------------------------------------------------
-- Server version	5.5.29

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
-- Table structure for table `CronJob`
--

DROP TABLE IF EXISTS `CronJob`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CronJob` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `command` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `job_interval` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `nextRun` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `mostRecentRun_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_522A03F614691BE8` (`mostRecentRun_id`),
  CONSTRAINT `FK_522A03F614691BE8` FOREIGN KEY (`mostRecentRun_id`) REFERENCES `CronJobResult` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CronJob`
--

LOCK TABLES `CronJob` WRITE;
/*!40000 ALTER TABLE `CronJob` DISABLE KEYS */;
/*!40000 ALTER TABLE `CronJob` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CronJobResult`
--

DROP TABLE IF EXISTS `CronJobResult`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CronJobResult` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `runAt` datetime NOT NULL,
  `runTime` double NOT NULL,
  `result` int(11) NOT NULL,
  `output` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_301AF175BE04EA9` (`job_id`),
  CONSTRAINT `FK_301AF175BE04EA9` FOREIGN KEY (`job_id`) REFERENCES `CronJob` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CronJobResult`
--

LOCK TABLES `CronJobResult` WRITE;
/*!40000 ALTER TABLE `CronJobResult` DISABLE KEYS */;
/*!40000 ALTER TABLE `CronJobResult` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_account_type` int(11) DEFAULT NULL,
  `street1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_country` int(11) DEFAULT NULL,
  `postcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `town` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `balance` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_transaction`
--

DROP TABLE IF EXISTS `account_transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_account` int(11) DEFAULT NULL,
  `id_direction` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_transaction`
--

LOCK TABLES `account_transaction` WRITE;
/*!40000 ALTER TABLE `account_transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hourly` decimal(10,0) DEFAULT NULL,
  `ip` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ipend` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_failed_login`
--

DROP TABLE IF EXISTS `admin_failed_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_failed_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_failed_login`
--

LOCK TABLES `admin_failed_login` WRITE;
/*!40000 ALTER TABLE `admin_failed_login` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_failed_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_permission`
--

DROP TABLE IF EXISTS `admin_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_admin` int(11) DEFAULT NULL,
  `id_page` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_permission`
--

LOCK TABLES `admin_permission` WRITE;
/*!40000 ALTER TABLE `admin_permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_session`
--

DROP TABLE IF EXISTS `admin_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_session`
--

LOCK TABLES `admin_session` WRITE;
/*!40000 ALTER TABLE `admin_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `automation_group`
--

DROP TABLE IF EXISTS `automation_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `automation_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `automation_group`
--

LOCK TABLES `automation_group` WRITE;
/*!40000 ALTER TABLE `automation_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `automation_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `automation_group_field`
--

DROP TABLE IF EXISTS `automation_group_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `automation_group_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_automation_group` int(11) DEFAULT NULL,
  `id_product_field` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `automation_group_field`
--

LOCK TABLES `automation_group_field` WRITE;
/*!40000 ALTER TABLE `automation_group_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `automation_group_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_contact`
--

DROP TABLE IF EXISTS `client_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contact`
--

LOCK TABLES `client_contact` WRITE;
/*!40000 ALTER TABLE `client_contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_contact_permission`
--

DROP TABLE IF EXISTS `client_contact_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_contact_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client_contact` int(11) DEFAULT NULL,
  `id_page` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contact_permission`
--

LOCK TABLES `client_contact_permission` WRITE;
/*!40000 ALTER TABLE `client_contact_permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_contact_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_credit_note`
--

DROP TABLE IF EXISTS `client_credit_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_credit_note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `note` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_credit_note`
--

LOCK TABLES `client_credit_note` WRITE;
/*!40000 ALTER TABLE `client_credit_note` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_credit_note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_email`
--

DROP TABLE IF EXISTS `client_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_email`
--

LOCK TABLES `client_email` WRITE;
/*!40000 ALTER TABLE `client_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_estimate`
--

DROP TABLE IF EXISTS `client_estimate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_estimate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `issue_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `hash` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount` decimal(10,0) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  `status` int(11) DEFAULT NULL,
  `invoice_status` int(11) DEFAULT NULL,
  `total_amount` decimal(10,0) DEFAULT NULL,
  `total_payment` decimal(10,0) DEFAULT NULL,
  `number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoiced` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_estimate`
--

LOCK TABLES `client_estimate` WRITE;
/*!40000 ALTER TABLE `client_estimate` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_estimate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_estimate_item`
--

DROP TABLE IF EXISTS `client_estimate_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_estimate_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_type` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` double NOT NULL,
  `unit_price` decimal(10,0) NOT NULL,
  `id_estimate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_estimate_item`
--

LOCK TABLES `client_estimate_item` WRITE;
/*!40000 ALTER TABLE `client_estimate_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_estimate_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_estimate_purchase`
--

DROP TABLE IF EXISTS `client_estimate_purchase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_estimate_purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_estimate` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_estimate_purchase`
--

LOCK TABLES `client_estimate_purchase` WRITE;
/*!40000 ALTER TABLE `client_estimate_purchase` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_estimate_purchase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_estimate_purchase_file`
--

DROP TABLE IF EXISTS `client_estimate_purchase_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_estimate_purchase_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_estimate_purchase` int(11) DEFAULT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` decimal(5,3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_estimate_purchase_file`
--

LOCK TABLES `client_estimate_purchase_file` WRITE;
/*!40000 ALTER TABLE `client_estimate_purchase_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_estimate_purchase_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_invoice`
--

DROP TABLE IF EXISTS `client_invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `issue_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `hash` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount` decimal(10,0) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  `status` int(11) DEFAULT NULL,
  `total_amount` decimal(11,2) DEFAULT NULL,
  `total_payment` decimal(11,2) DEFAULT NULL,
  `number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `access_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reminder_sent_at` datetime DEFAULT NULL,
  `viewed_by_client` tinyint(1) DEFAULT NULL,
  `reminders` tinyint(1) DEFAULT NULL,
  `overdue_notices` tinyint(1) DEFAULT NULL,
  `id_client_product` int(11) DEFAULT NULL,
  `id_recurring` int(11) DEFAULT NULL,
  `id_product` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_invoice`
--

LOCK TABLES `client_invoice` WRITE;
/*!40000 ALTER TABLE `client_invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_invoice_item`
--

DROP TABLE IF EXISTS `client_invoice_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_invoice_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_type` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` double NOT NULL,
  `unit_price` decimal(11,2) NOT NULL,
  `id_invoice` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_invoice_item`
--

LOCK TABLES `client_invoice_item` WRITE;
/*!40000 ALTER TABLE `client_invoice_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_invoice_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_invoice_purchase`
--

DROP TABLE IF EXISTS `client_invoice_purchase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_invoice_purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_invoice` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_invoice_purchase`
--

LOCK TABLES `client_invoice_purchase` WRITE;
/*!40000 ALTER TABLE `client_invoice_purchase` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_invoice_purchase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_invoice_purchase_file`
--

DROP TABLE IF EXISTS `client_invoice_purchase_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_invoice_purchase_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_invoice_purchase` int(11) DEFAULT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` decimal(5,3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_invoice_purchase_file`
--

LOCK TABLES `client_invoice_purchase_file` WRITE;
/*!40000 ALTER TABLE `client_invoice_purchase_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_invoice_purchase_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_log`
--

DROP TABLE IF EXISTS `client_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_type` int(11) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_log`
--

LOCK TABLES `client_log` WRITE;
/*!40000 ALTER TABLE `client_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_note`
--

DROP TABLE IF EXISTS `client_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` longtext COLLATE utf8_unicode_ci,
  `is_encrypted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_note`
--

LOCK TABLES `client_note` WRITE;
/*!40000 ALTER TABLE `client_note` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_payment`
--

DROP TABLE IF EXISTS `client_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_gateway` int(11) NOT NULL,
  `transaction` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pay_date` datetime NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `fee` decimal(11,2) NOT NULL,
  `id_estimate` int(11) DEFAULT NULL,
  `id_type` int(11) DEFAULT NULL,
  `id_account_transaction` int(11) DEFAULT NULL,
  `id_invoice` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_payment`
--

LOCK TABLES `client_payment` WRITE;
/*!40000 ALTER TABLE `client_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_product`
--

DROP TABLE IF EXISTS `client_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_payment_term` int(11) NOT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `encrypted_username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `encrypted_password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `id_server` int(11) NOT NULL,
  `cost` decimal(11,2) NOT NULL,
  `id_schedule` int(11) NOT NULL,
  `next_due` datetime NOT NULL,
  `tax_group` int(11) NOT NULL,
  `reminders` tinyint(1) NOT NULL,
  `overdue_notices` tinyint(1) NOT NULL,
  `id_product` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `domain` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_product`
--

LOCK TABLES `client_product` WRITE;
/*!40000 ALTER TABLE `client_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_product_item`
--

DROP TABLE IF EXISTS `client_product_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_product_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client_product` int(11) NOT NULL,
  `details` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_product_item`
--

LOCK TABLES `client_product_item` WRITE;
/*!40000 ALTER TABLE `client_product_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_product_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_project`
--

DROP TABLE IF EXISTS `client_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `id_client` int(11) NOT NULL,
  `code` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `budget` decimal(10,0) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `id_type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_project`
--

LOCK TABLES `client_project` WRITE;
/*!40000 ALTER TABLE `client_project` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_project_attachment`
--

DROP TABLE IF EXISTS `client_project_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_project_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_project` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_project_attachment`
--

LOCK TABLES `client_project_attachment` WRITE;
/*!40000 ALTER TABLE `client_project_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_project_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_project_attachment_file`
--

DROP TABLE IF EXISTS `client_project_attachment_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_project_attachment_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_project_attachment` int(11) DEFAULT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` decimal(5,3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_project_attachment_file`
--

LOCK TABLES `client_project_attachment_file` WRITE;
/*!40000 ALTER TABLE `client_project_attachment_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_project_attachment_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_project_task`
--

DROP TABLE IF EXISTS `client_project_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_project_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_project` int(11) DEFAULT NULL,
  `id_work_type` int(11) DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `quantity` decimal(10,0) DEFAULT NULL,
  `unit` int(11) DEFAULT NULL,
  `unit_price` decimal(10,0) DEFAULT NULL,
  `is_billable` tinyint(1) DEFAULT NULL,
  `invoiced` tinyint(1) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_project_task`
--

LOCK TABLES `client_project_task` WRITE;
/*!40000 ALTER TABLE `client_project_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_project_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_project_tracking`
--

DROP TABLE IF EXISTS `client_project_tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_project_tracking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_project` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `stop` datetime DEFAULT NULL,
  `staff` int(11) NOT NULL,
  `hourly` decimal(10,0) NOT NULL,
  `invoiced` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_project_tracking`
--

LOCK TABLES `client_project_tracking` WRITE;
/*!40000 ALTER TABLE `client_project_tracking` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_project_tracking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_recurring`
--

DROP TABLE IF EXISTS `client_recurring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_recurring` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_schedule` int(11) NOT NULL,
  `discount` decimal(10,0) NOT NULL,
  `tax` int(11) NOT NULL,
  `next_due` date NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  `is_invoiced` tinyint(1) DEFAULT NULL,
  `reminders` tinyint(1) DEFAULT NULL,
  `overdue_notices` tinyint(1) DEFAULT NULL,
  `id_product` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_recurring`
--

LOCK TABLES `client_recurring` WRITE;
/*!40000 ALTER TABLE `client_recurring` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_recurring` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_recurring_item`
--

DROP TABLE IF EXISTS `client_recurring_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_recurring_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_type` int(11) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` double NOT NULL,
  `unit_price` decimal(11,2) NOT NULL,
  `id_recurring` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_recurring_item`
--

LOCK TABLES `client_recurring_item` WRITE;
/*!40000 ALTER TABLE `client_recurring_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_recurring_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `install_directory` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_directory` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `licence_key` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_language` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_format` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_currency` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `staff_ip_verification` tinyint(1) DEFAULT NULL,
  `staff_multiple_logins` tinyint(1) DEFAULT NULL,
  `staff_timeout` int(11) DEFAULT NULL,
  `staff_login_notify` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `staff_login_fail_notify` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recaptcha_public` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recaptcha_private` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `staff_login_greylist` int(11) DEFAULT NULL,
  `business_address` longtext COLLATE utf8_unicode_ci,
  `invoice_notes` longtext COLLATE utf8_unicode_ci,
  `payment_success_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_failure_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `localkey` longtext COLLATE utf8_unicode_ci,
  `id_default_work_type` int(11) DEFAULT NULL,
  `default_tax` decimal(10,0) DEFAULT NULL,
  `default_discount` decimal(10,0) DEFAULT NULL,
  `version` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `culture` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ticketImapHost` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ticketImapUsername` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ticketImapPassword` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maxmindLicenseKey` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maxmindRiskScoreThreshold` decimal(10,0) DEFAULT NULL,
  `maxmind_enabled` tinyint(1) DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estimate_prefix` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_prefix` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `util_key` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cpanel_api_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cpanel_api_username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cpanel_api_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `solusvm_api_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `solusvm_api_username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `solusvm_api_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `loggly_enabled` tinyint(1) DEFAULT NULL,
  `loggly_consumer_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `loggly_consumer_secret` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `loggly_api_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_proforma_invoice_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `proforma_invoice_prefix` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'PI',
  `count_proforma_invoice` int(11) DEFAULT '1',
  `count_proforma_paid_invoice` int(11) DEFAULT '1',
  `is_enabled_drop_in` tinyint(1) NOT NULL DEFAULT '0',
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `current_invoices` int(11) DEFAULT NULL,
  `generate_invoice` int(11) DEFAULT NULL,
  `invoice_email` longtext COLLATE utf8_unicode_ci,
  `send_reminder` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `reminder_email` longtext COLLATE utf8_unicode_ci,
  `send_overdue` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `daily_summary` tinyint(1) DEFAULT NULL,
  `tos_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `privacy_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_header` longtext COLLATE utf8_unicode_ci,
  `client_footer` longtext COLLATE utf8_unicode_ci,
  `client_menus` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `order_email` longtext COLLATE utf8_unicode_ci,
  `suspend_after` int(11) DEFAULT NULL,
  `terminate_after` int(11) DEFAULT NULL,
  `overdue_email` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,NULL,NULL,'Test Company','test@changeme.com',NULL,NULL,'d/m/Y',NULL,'GBP',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'en-GB',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'PI',1,1,0,'change-me.com',NULL,7,NULL,'a:2:{i:0;i:2;i:1;i:4;}',NULL,'a:4:{i:0;i:1;i:1;i:3;i:2;i:5;i:3;i:7;}',0,NULL,NULL,NULL,'','','a:0:{}',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country_tax`
--

DROP TABLE IF EXISTS `country_tax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country_tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` int(11) DEFAULT NULL,
  `tax` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country_tax`
--

LOCK TABLES `country_tax` WRITE;
/*!40000 ALTER TABLE `country_tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `country_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email`
--

DROP TABLE IF EXISTS `email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email`
--

LOCK TABLES `email` WRITE;
/*!40000 ALTER TABLE `email` DISABLE KEYS */;
/*!40000 ALTER TABLE `email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_type` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,2,'2014-05-07 22:18:58','IP: 127.0.0.1'),(2,2,'2014-05-07 22:19:14','IP: 127.0.0.1'),(3,2,'2014-05-07 22:22:29','IP: 127.0.0.1'),(4,2,'2014-05-07 22:22:43','IP: 127.0.0.1'),(5,2,'2014-05-07 22:28:09','IP: 127.0.0.1'),(6,1,'2014-05-07 22:31:37','IP: 127.0.0.1 / Email: james@loadingdeck.com'),(7,1,'2014-05-07 22:43:20','IP: 127.0.0.1 / Email: james@loadingdeck.com');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_gateways`
--

DROP TABLE IF EXISTS `payment_gateways`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_gateways` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `safe_credentials` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_gateways`
--

LOCK TABLES `payment_gateways` WRITE;
/*!40000 ALTER TABLE `payment_gateways` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_gateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_group`
--

DROP TABLE IF EXISTS `permission_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_group`
--

LOCK TABLES `permission_group` WRITE;
/*!40000 ALTER TABLE `permission_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `permission_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `physical_item`
--

DROP TABLE IF EXISTS `physical_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `physical_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `physical_item`
--

LOCK TABLES `physical_item` WRITE;
/*!40000 ALTER TABLE `physical_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `physical_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `physical_item_purchase`
--

DROP TABLE IF EXISTS `physical_item_purchase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `physical_item_purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_physical_item` int(11) DEFAULT NULL,
  `id_supplier` int(11) DEFAULT NULL,
  `date_in` datetime DEFAULT NULL,
  `purchase_price` decimal(10,0) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_purchased` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `physical_item_purchase`
--

LOCK TABLES `physical_item_purchase` WRITE;
/*!40000 ALTER TABLE `physical_item_purchase` DISABLE KEYS */;
/*!40000 ALTER TABLE `physical_item_purchase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `physical_item_sold`
--

DROP TABLE IF EXISTS `physical_item_sold`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `physical_item_sold` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_physical_item` int(11) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `date_out` datetime DEFAULT NULL,
  `sell_price` decimal(10,0) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoiced` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `physical_item_sold`
--

LOCK TABLES `physical_item_sold` WRITE;
/*!40000 ALTER TABLE `physical_item_sold` DISABLE KEYS */;
/*!40000 ALTER TABLE `physical_item_sold` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `server_package` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_product_group` int(11) DEFAULT NULL,
  `server_group` int(11) DEFAULT NULL,
  `id_email` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `id_payment_type` int(11) DEFAULT NULL,
  `setup_fee_monthly` decimal(11,2) DEFAULT NULL,
  `setup_fee_quarterly` decimal(11,2) DEFAULT NULL,
  `setup_fee_semi_annually` decimal(11,2) DEFAULT NULL,
  `setup_fee_annually` decimal(11,2) DEFAULT NULL,
  `setup_fee_biennially` decimal(11,2) DEFAULT NULL,
  `setup_fee_triennially` decimal(11,2) DEFAULT NULL,
  `price_monthly` decimal(11,2) DEFAULT NULL,
  `price_quarterly` decimal(11,2) DEFAULT NULL,
  `price_semi_annually` decimal(11,2) DEFAULT NULL,
  `price_annually` decimal(11,2) DEFAULT NULL,
  `price_biennially` decimal(11,2) DEFAULT NULL,
  `price_triennially` decimal(11,2) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT NULL,
  `id_type` int(11) DEFAULT NULL,
  `is_redirect_unpaid_invoice` tinyint(1) DEFAULT NULL,
  `trigger_create` int(11) DEFAULT NULL,
  `tax_group` int(11) DEFAULT NULL,
  `color` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_settings` longtext COLLATE utf8_unicode_ci NOT NULL,
  `features` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_automation_group`
--

DROP TABLE IF EXISTS `product_automation_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_automation_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_product` int(11) DEFAULT NULL,
  `id_automation_group` int(11) DEFAULT NULL,
  `id_event` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_automation_group`
--

LOCK TABLES `product_automation_group` WRITE;
/*!40000 ALTER TABLE `product_automation_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_automation_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_group`
--

DROP TABLE IF EXISTS `product_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_group`
--

LOCK TABLES `product_group` WRITE;
/*!40000 ALTER TABLE `product_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_order`
--

DROP TABLE IF EXISTS `product_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_product` int(11) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `order_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_product` int(11) DEFAULT NULL,
  `maxmind_data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `ip_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_invoice` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_order`
--

LOCK TABLES `product_order` WRITE;
/*!40000 ALTER TABLE `product_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `server`
--

DROP TABLE IF EXISTS `server`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `encrypted_ip` longtext COLLATE utf8_unicode_ci,
  `encrypted_user` longtext COLLATE utf8_unicode_ci,
  `encrypted_pass` longtext COLLATE utf8_unicode_ci,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `server`
--

LOCK TABLES `server` WRITE;
/*!40000 ALTER TABLE `server` DISABLE KEYS */;
/*!40000 ALTER TABLE `server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `server_group`
--

DROP TABLE IF EXISTS `server_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `server_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `type` int(11) NOT NULL,
  `choice_logic` int(11) NOT NULL,
  `primary_server` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `server_group`
--

LOCK TABLES `server_group` WRITE;
/*!40000 ALTER TABLE `server_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `server_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_country` int(11) DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier`
--

LOCK TABLES `supplier` WRITE;
/*!40000 ALTER TABLE `supplier` DISABLE KEYS */;
/*!40000 ALTER TABLE `supplier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier_purchase`
--

DROP TABLE IF EXISTS `supplier_purchase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplier_purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_supplier` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `id_account_transaction` int(11) DEFAULT NULL,
  `amount` decimal(11,2) DEFAULT NULL,
  `nominal_code` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier_purchase`
--

LOCK TABLES `supplier_purchase` WRITE;
/*!40000 ALTER TABLE `supplier_purchase` DISABLE KEYS */;
/*!40000 ALTER TABLE `supplier_purchase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier_purchase_file`
--

DROP TABLE IF EXISTS `supplier_purchase_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplier_purchase_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_purchase` int(11) DEFAULT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` decimal(5,3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier_purchase_file`
--

LOCK TABLES `supplier_purchase_file` WRITE;
/*!40000 ALTER TABLE `supplier_purchase_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `supplier_purchase_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_group`
--

DROP TABLE IF EXISTS `tax_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` decimal(11,2) NOT NULL,
  `countries` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_group`
--

LOCK TABLES `tax_group` WRITE;
/*!40000 ALTER TABLE `tax_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `tax_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_widget`
--

DROP TABLE IF EXISTS `user_widget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_widget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_widget` int(11) NOT NULL,
  `state` longtext COLLATE utf8_unicode_ci,
  `sort_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_widget`
--

LOCK TABLES `user_widget` WRITE;
/*!40000 ALTER TABLE `user_widget` DISABLE KEYS */;
INSERT INTO `user_widget` VALUES (1,1,1,'1',6),(2,1,2,'2014',2),(3,1,3,'2014',4),(4,1,4,'1',8),(5,1,5,'1',10),(6,1,6,'2014',12),(7,1,7,'1',14);
/*!40000 ALTER TABLE `user_widget` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_country` int(11) DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL,
  `default_hourly_rate` decimal(10,0) DEFAULT NULL,
  `vat_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `has_admin` tinyint(1) NOT NULL,
  `amount` int(11) DEFAULT NULL,
  `permission_group` int(11) DEFAULT NULL,
  `api_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `number` int(11) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_1483A5E9A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin','admin','admin',1,'g5sx90y5udc0sosc00g0kwo8g4og8ws','ZQG3WkyXXfM85694LqpKvBkZ5TOXnxNKxTsujmShtQycso16bpWT9qIbJh+jcktpboVbg/wFhHE/DyW3bJeh2Q==','2014-05-07 22:43:20',0,0,NULL,NULL,NULL,'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,0,NULL,NULL,NULL,0,NULL,NULL);
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

-- Dump completed on 2014-05-07 23:40:30
