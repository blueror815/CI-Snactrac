--
-- Business info table
-- 
DROP TABLE IF EXISTS business;
CREATE TABLE business (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  created_at INT UNSIGNED NOT NULL,
  status SMALLINT UNSIGNED NOT NULL,
  owner_id INT UNSIGNED REFERENCES users(id),
  name VARCHAR(128) NOT NULL,
  phone VARCHAR(32) NOT NULL,
  email VARCHAR(128),
  website VARCHAR(128),
  twitter VARCHAR(128),
  facebook VARCHAR(128),
  yelp VARCHAR(128),
  description VARCHAR(1024)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET utf8;
