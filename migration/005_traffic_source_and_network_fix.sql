-- Remove bad fields from campaigns
ALTER TABLE campaigns DROP traffic_source_id;
ALTER TABLE campaigns DROP affiliate_campaign_id;

-- Add new field network_id to campaigns
ALTER TABLE campaigns ADD network_id INT UNSIGNED NULL DEFAULT NULL, ADD INDEX (network_id);

-- Rename affiliate_network to network
RENAME TABLE affiliate_network TO network;

-- Remove table affiliate_campaign. There will be only one campaign table
DROP TABLE affiliate_campaign;

-- New fields for iptracker
ALTER TABLE iptracker ADD traffic_source_id INT UNSIGNED NULL DEFAULT NULL, ADD INDEX (traffic_source_id);
ALTER TABLE iptracker ADD network_id        INT UNSIGNED NULL DEFAULT NULL, ADD INDEX (network_id);


-- New table "tracker" to track generate links
CREATE TABLE IF NOT EXISTS tracker (
    id int(10) unsigned NOT NULL AUTO_INCREMENT,
    campaign_id int(10) unsigned NOT NULL,
    traffic_source_id int(10) unsigned DEFAULT NULL,
    shortcode varchar(50) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY shortcode (shortcode),
    KEY campaign_id (campaign_id),
    KEY traffic_source_id (traffic_source_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- Insert existing shortcodes into "tracker"
INSERT INTO tracker (campaign_id, shortcode)
    SELECT id, shortcode FROM campaigns;

-- Drop campaigns.shortcode
ALTER TABLE campaigns DROP shortcode;
