ALTER TABLE iptracker
    ADD tracker_id_for_lp INT NULL DEFAULT NULL COMMENT  'Only for landing page clicks',
    ADD INDEX (tracker_id_for_lp);
