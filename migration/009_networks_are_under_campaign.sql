-- move network_id from campaigns into tracker
ALTER TABLE campaigns DROP network_id;
ALTER TABLE tracker ADD network_id INT NULL DEFAULT NULL;

-- modify network table
TRUNCATE TABLE network;
ALTER TABLE network CHANGE user_id campaign_id INT(11) NOT NULL;
