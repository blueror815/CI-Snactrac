--
-- Table used for internal messaging
-- Note: Planned feature not implemented yet.
-- 
DROP TABLE IF EXISTS messages;
CREATE TABLE messages (

    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    parent_id INT UNSIGNED NOT NULL REFERENCES messages(id),
    sender_id INT UNSIGNED NOT NULL REFERENCES users(id),
    receiver_id INT UNSIGNED NOT NULL REFERENCES users(id),
    subject VARCHAR(256) NOT NULL,
    content VARCHAR(1024)
);