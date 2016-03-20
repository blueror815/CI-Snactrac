-- ----------------------------------------------------------------------
-- Sessions Table
-- see: ellislab.com/codeigniter/user-guide/libraries/sessions.html
--

DROP TABLE IF EXISTS ci_sessions;
CREATE TABLE ci_sessions (
	session_id varchar(40) DEFAULT '0' NOT NULL,
	ip_address varchar(45) DEFAULT '0' NOT NULL,
	user_agent varchar(120) NOT NULL,
	last_activity int(10) unsigned DEFAULT 0 NOT NULL,
	user_data text NOT NULL,
	PRIMARY KEY (session_id),
	KEY last_activity_idx (last_activity)
)
ENGINE=InnoDB;
