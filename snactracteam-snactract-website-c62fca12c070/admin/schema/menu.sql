--
-- menu_items table
-- 
DROP TABLE IF EXISTS menu_items;
CREATE TABLE menu_items (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  created_at INT UNSIGNED NOT NULL,
  status SMALLINT UNSIGNED NOT NULL,
  truck_id INT UNSIGNED REFERENCES trucks(id),
  name VARCHAR(128) NOT NULL,
  description VARCHAR(1024),
  price INT UNSIGNED NOT NULL
)
ENGINE=InnoDB
DEFAULT CHARACTER SET utf8;

--
-- menu item keywords
-- 
DROP TABLE IF EXISTS menu_item_keywords;
CREATE TABLE menu_item_keywords (
  menu_item_id INT UNSIGNED NOT NULL REFERENCES menu_items(id),
  keyword VARCHAR(16) NOT NULL
)
ENGINE=InnoDB
DEFAULT CHARACTER SET utf8;


