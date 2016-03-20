--
-- truck schedule table
-- 
DROP TABLE IF EXISTS truck_schedules;
CREATE TABLE truck_schedules(
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  created_at INT UNSIGNED NOT NULL,
  truck_id INT UNSIGNED NOT NULL REFERENCES trucks(id),
  day_of_week TINYINT UNSIGNED NOT NULL,
  start_time_hour SMALLINT UNSIGNED NOT NULL,
  start_time_min SMALLINT UNSIGNED NOT NULL,
  end_time_hour SMALLINT UNSIGNED NOT NULL,
  end_time_min SMALLINT UNSIGNED NOT NULL,
  location_id INT UNSIGNED NOT NULL REFERENCES locations(id),
  notes VARCHAR(512),
  
  KEY(day_of_week)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET utf8;
