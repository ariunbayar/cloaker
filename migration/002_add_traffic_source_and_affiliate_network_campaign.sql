--
-- Table structure for table `affiliate_campaign`
--
DROP TABLE IF EXISTS affiliate_campaign;
CREATE TABLE IF NOT EXISTS affiliate_campaign (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `affiliate_network_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `affiliate_network_id` (`affiliate_network_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `affiliate_network`
--
DROP TABLE IF EXISTS affiliate_network;
CREATE TABLE IF NOT EXISTS affiliate_network (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `traffic_source`
--
DROP TABLE IF EXISTS traffic_source;
CREATE TABLE IF NOT EXISTS `traffic_source` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
