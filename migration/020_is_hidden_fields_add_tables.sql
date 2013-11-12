ALTER TABLE  `network` ADD  `is_hidden` TINYINT NOT NULL DEFAULT  '0' AFTER  `campaign_id`;

ALTER TABLE  `offer` ADD  `is_hidden` TINYINT NOT NULL DEFAULT  '0' AFTER  `campaign_id`;

ALTER TABLE  `traffic_source` ADD  `is_hidden` TINYINT NOT NULL DEFAULT  '0' AFTER  `campaign_id`;
