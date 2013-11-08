ALTER TABLE  `iptracker` CHANGE  `is_converted`  `is_converted` SMALLINT( 1 ) UNSIGNED NULL DEFAULT  '0';

UPDATE iptracker SET is_converted = 0 WHERE is_converted IS NULL;
