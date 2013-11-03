-- Offers will be tracked in this table
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

-- Move default offer form campaigns table
INSERT INTO offer (name,campaign_id, cloaking_url, cloaked_url)
    SELECT 'Default offer', id, cloaking_url, cloaked_url FROM campaigns;

-- Campaign doesn't need to have cloaked/cloaking urls.
-- They are tracked in offers table
ALTER TABLE campaigns
  DROP cloaked_url,
  DROP cloaking_url;

-- No campaign id for destinations
ALTER TABLE destinations DROP campaign_id;

-- Tracker would include offer_id
ALTER TABLE tracker ADD offer_id INT NOT NULL;

-- Set tracker.offer_id
CREATE TEMPORARY TABLE t1 AS SELECT * FROM tracker;
TRUNCATE TABLE tracker;
INSERT INTO tracker (id, campaign_id, traffic_source_id, shortcode, is_landing_page, created_at, network_id, offer_id)
    SELECT t1.id, t1.campaign_id, t1.traffic_source_id, t1.shortcode, t1.is_landing_page, t1.created_at, t1.network_id, offer.id
    FROM t1 LEFT JOIN offer ON offer.campaign_id=t1.campaign_id
    WHERE offer.name='Default offer'
    GROUP BY t1.id;
DROP TABLE t1;
