ALTER TABLE  `traffic_source` ADD  `user_id` INT NOT NULL AFTER  `name`;
ALTER TABLE  `affiliate_network` ADD  `user_id` INT NOT NULL AFTER  `name`;
ALTER TABLE  `affiliate_campaign` ADD  `user_id` INT NOT NULL AFTER  `affiliate_network_id`;
