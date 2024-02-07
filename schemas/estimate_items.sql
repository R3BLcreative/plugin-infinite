CREATE TABLE
	R3BL_TABLE_NAME (
		ID bigint (20) unsigned NOT NULL AUTO_INCREMENT,
		estimate_id bigint (20) unsigned,
		metal varchar(50),
		type varchar(50),
		photos longtext,
		weight decimal,
		weight_unit varchar(50),
		purity decimal,
		purity_unit varchar(50),
		color varchar(50),
		estimated_offer decimal,
		notes longtext,
		updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (ID),
		FOREIGN KEY (estimate_id) REFERENCES R3BL_TABLE_PREFIX alloy_estimates (ID)
	);