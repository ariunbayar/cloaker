-- Tracker can have multiple offers
CREATE TABLE IF NOT EXISTS tracker_offer (
  tracker_id int(11) NOT NULL,
  offer_id int(11) NOT NULL,
  UNIQUE KEY tracker_id (tracker_id, offer_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Move the data
INSERT INTO tracker_offer (tracker_id, offer_id)
    SELECT id, offer_id FROM tracker;

-- Remove tracker.offer_id
ALTER TABLE tracker DROP offer_id;

-- Landing page URL for tracker
ALTER TABLE tracker ADD landing_page_url VARCHAR(250) NULL DEFAULT NULL;
