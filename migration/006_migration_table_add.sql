CREATE TABLE IF NOT EXISTS `migration` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


INSERT INTO `migration` (`id`, `file_name`) VALUES
(1, '001_database_dump.sql'),
(2, '002_add_traffic_source_and_affiliate_network_campaign.sql'),
(3, '003_add_user_id_field_in_traffic_affiliate.sql'),
(4, '004_new_fields_to_campaign.sql'),
(5, '005_traffic_source_and_network_fix.sql'),
(6, '006_migration_table_add.sql');
