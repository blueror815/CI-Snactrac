--
-- truck info table
-- 
DROP TABLE IF EXISTS trucks;
CREATE TABLE trucks (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  created_at INT UNSIGNED NOT NULL,
  status SMALLINT UNSIGNED NOT NULL,
  business_id INT UNSIGNED REFERENCES business(id),
  name VARCHAR(128) NOT NULL,
  calendar_url VARCHAR(64),
  truck_type TINYINT(3),
  description VARCHAR(1024)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET utf8;
-- ALTER TABLE trucks ADD column truck_type TINYINT UNSIGNED NOT NULL DEFAULT 1;

--
-- truck keywords
-- 
DROP TABLE IF EXISTS truck_keywords;
CREATE TABLE truck_keywords (
  truck_id INT UNSIGNED NOT NULL REFERENCES truck(id),
  keyword VARCHAR(16) NOT NULL
)
ENGINE=InnoDB
DEFAULT CHARACTER SET utf8;

--
-- truck monitors - used for posting alerts/notifications
--
DROP TABLE IF EXISTS truck_monitors;
CREATE TABLE truck_monitors (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  created_at INT UNSIGNED NOT NULL,
  truck_id INT UNSIGNED NOT NULL REFERENCES trucks(id),
  user_id INT UNSIGNED REFERENCES membership_users(id),
  monitor_type SMALLINT UNSIGNED NOT NULL,
  
  UNIQUE(truck_id, user_id)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET utf8;

