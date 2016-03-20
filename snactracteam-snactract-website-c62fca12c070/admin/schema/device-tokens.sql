--
-- device tokens for push notifications
-- 
DROP TABLE IF EXISTS device_tokens;
CREATE TABLE device_tokens (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  created_at INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  token VARCHAR(256) NOT NULL,
  platform SMALLINT UNSIGNED NOT NULL
)
ENGINE=InnoDB
DEFAULT CHARACTER SET utf8;