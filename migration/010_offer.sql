CREATE TABLE IF NOT EXISTS `offer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `network_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `cloaked_url` int(11) NOT NULL,
  `cloaking_url` int(11) NOT NULL,
  `payout` decimal(10,4) NOT NULL,
  `campaign_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`),
  KEY `network_id` (`network_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
