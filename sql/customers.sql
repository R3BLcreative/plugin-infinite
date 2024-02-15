CREATE TABLE
	INFINITE_TABLE_NAME (
		ID bigint (20) unsigned NOT NULL AUTO_INCREMENT,
		user_id bigint (20) unsigned UNIQUE,
		first_name varchar(255),
		last_name varchar(255),
		full_name varchar(255),
		dob date,
		primary_phone varchar(20),
		street1 varchar(255),
		street2 varchar(255),
		city varchar(255),
		state varchar(255),
		postal_code varchar(20),
		source varchar(255),
		updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (ID),
		FOREIGN KEY (user_id) REFERENCES INFINITE_TABLE_PREFIX users (ID)
	);