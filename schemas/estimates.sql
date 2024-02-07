CREATE TABLE
	R3BL_TABLE_NAME (
		ID bigint (20) unsigned NOT NULL AUTO_INCREMENT,
		customer_id bigint (20) unsigned,
		status varchar(50),
		estimated_offer decimal,
		notes longtext,
		updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (ID),
		FOREIGN KEY (customer_id) REFERENCES R3BL_TABLE_PREFIX alloy_customers (ID)
	);