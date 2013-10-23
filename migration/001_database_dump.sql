CREATE DATABASE  IF NOT EXISTS `cloaker` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `cloaker`;
-- MySQL dump 10.13  Distrib 5.6.11, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: cloaker
-- ------------------------------------------------------
-- Server version	5.6.11

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
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ct_dt` datetime NOT NULL,
  `md_dt` datetime NOT NULL,
  `shortcode` char(7) COLLATE utf8_unicode_ci NOT NULL,
  `cloak_status` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `cloaked_url` int(10) unsigned NOT NULL,
  `cloaking_url` int(10) unsigned NOT NULL,
  `ref_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `googleurl` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `ad_status` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `deniedip_status` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `denyiprange_status` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `visit_count` int(10) NOT NULL,
  `visitcount_status` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `rdns` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `rdns_status` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `geolocation` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `geoloc_status` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `geoloc_mismatch_status` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `ua_strings` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `ua_status` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shortcode` (`shortcode`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns`
--

LOCK TABLES `campaigns` WRITE;
/*!40000 ALTER TABLE `campaigns` DISABLE KEYS */;
INSERT INTO `campaigns` VALUES (1,1,'Test Campaign','2013-02-07 13:59:11','2013-02-14 15:24:42','97a6594','on',1,2,'on','utm_source,utm_medium','on','on','on',5,'on','google.com,googlebot.com,microsoft.com,bing.com,google,msn,msn.com,msnbot','on','JAPAN,TOKYO,REDMOND','on','off','Firefox','on');
/*!40000 ALTER TABLE `campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `denied_gips`
--

DROP TABLE IF EXISTS `denied_gips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `denied_gips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(250) NOT NULL,
  `ct` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `denied_gips`
--

LOCK TABLES `denied_gips` WRITE;
/*!40000 ALTER TABLE `denied_gips` DISABLE KEYS */;
INSERT INTO `denied_gips` VALUES (1,'8.8.8.17','2013-10-15 21:44:35'),(2,'8.8.8.9','2013-10-15 21:44:35'),(3,'192.168.5.7','2013-10-15 21:44:35'),(4,'192.168.75.3','2013-10-15 21:44:35'),(5,'127.0.0.1','2013-10-15 21:44:35');
/*!40000 ALTER TABLE `denied_gips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `denied_ip_ranges`
--

DROP TABLE IF EXISTS `denied_ip_ranges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `denied_ip_ranges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(10) unsigned NOT NULL,
  `iprange` text COLLATE utf8_unicode_ci NOT NULL,
  `ct` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `denied_ip_ranges`
--

LOCK TABLES `denied_ip_ranges` WRITE;
/*!40000 ALTER TABLE `denied_ip_ranges` DISABLE KEYS */;
/*!40000 ALTER TABLE `denied_ip_ranges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `denied_ips`
--

DROP TABLE IF EXISTS `denied_ips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `denied_ips` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(10) unsigned NOT NULL,
  `ip` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `ct` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `denied_ips`
--

LOCK TABLES `denied_ips` WRITE;
/*!40000 ALTER TABLE `denied_ips` DISABLE KEYS */;
/*!40000 ALTER TABLE `denied_ips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destinations`
--

DROP TABLE IF EXISTS `destinations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) unsigned NOT NULL,
  `url` varchar(200) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destinations`
--

LOCK TABLES `destinations` WRITE;
/*!40000 ALTER TABLE `destinations` DISABLE KEYS */;
INSERT INTO `destinations` VALUES (1,1,'http://www.marinbezhanov.com/',''),(2,1,'http://www.znai.bg/','');
/*!40000 ALTER TABLE `destinations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iptracker`
--

DROP TABLE IF EXISTS `iptracker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iptracker` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(10) unsigned NOT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `session_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `referral_url` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `host` text COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `region` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `page_views` int(11) NOT NULL,
  `cloak` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `reasonforcloak` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `access_time` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ct_dt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iptracker`
--

LOCK TABLES `iptracker` WRITE;
/*!40000 ALTER TABLE `iptracker` DISABLE KEYS */;
INSERT INTO `iptracker` VALUES (1,1,'85.130.106.81','ddo8qp1kj29rgud53fcpa4a8k1','http://cellnet.marinbezhanov.com:8080/new-cloaker/admin/manage/1/','81.direct-130-106.bgcell.net','Bulgaria','Grad Sofiya','Sofia',1,'yes','Referral URL Not Empty','0 minute(s)','2013-02-07 13:59:57'),(2,1,'192.168.1.1','vu11qnecagh8i6b5p9prnq5hi0','','my.router','-','-','-',1,'yes','Unallowed User Agent','0 minute(s)','2013-02-14 15:30:08'),(3,1,'192.168.1.1','kngavo5js5v7ima3184mj1uvg6','http://net1.marinbezhanov.com:8080/admin/manage/1/','my.router','-','-','-',2,'yes','Unallowed User Agent','0.45 minute(s)','2013-04-05 18:03:43'),(4,1,'192.168.1.1','u2f02tnnjajuuadl2815dk9bt1','','my.router','','','',1,'yes','Unallowed User Agent','0 minute(s)','2013-04-05 18:04:55'),(5,1,'127.0.0.1','pvtq5ps2d3a20cseof46kh1u11','','localmy360.johnsonsauer.com','-','-','-',1,'yes','Unallowed User Agent','0 minute(s)','2013-10-14 18:50:19'),(6,1,'127.0.0.1','gglhm2aufn6p3ino56p1k8la85','','localmy360.johnsonsauer.com','','','',3,'yes','Unallowed User Agent','63.93 minute(s)','2013-10-15 16:48:16');
/*!40000 ALTER TABLE `iptracker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` char(32) NOT NULL,
  `user_level` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3','superadmin'),(2,'test','098f6bcd4621d373cade4e832627b4f6','admin');
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

-- Dump completed on 2013-10-15 21:45:33
