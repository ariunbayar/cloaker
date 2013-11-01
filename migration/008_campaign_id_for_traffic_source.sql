TRUNCATE TABLE traffic_source;
ALTER TABLE traffic_source CHANGE user_id campaign_id INT(11) NOT NULL;
