--
-- locations table can store anything
-- 
DROP TABLE IF EXISTS locations;
CREATE TABLE locations (

    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    street VARCHAR(256),
    street2 VARCHAR(256),
    city VARCHAR(128) NOT NULL,
    state VARCHAR(16) NOT NULL,
    zip VARCHAR(16),
    country VARCHAR(2) NOT NULL,
    metro INT UNSIGNED NOT NULL DEFAULT 1, 
    latitude VARCHAR(16),
    longitude VARCHAR(16)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET utf8;

--
-- User locations
--
DROP TABLE IF EXISTS user_locations;
CREATE TABLE user_locations(
  user_id INT UNSIGNED NOT NULL REFERENCES users(id),
  location_id INT UNSIGNED NOT NULL REFERENCES locations(id),
  favorite BOOL NOT NULL DEFAULT TRUE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET utf8;

--
-- truck scheduled locations
--
DROP TABLE IF EXISTS truck_locations;
CREATE TABLE truck_locations (
  schedule_id INT UNSIGNED NOT NULL REFERENCES truck_schedule(id),
  location_id INT UNSIGNED NOT NULL REFERENCES locations(id)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET utf8;
