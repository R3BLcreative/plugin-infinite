CREATE TABLE $TABLE_NAME (
ID bigint (20) unsigned NOT NULL AUTO_INCREMENT,
CID bigint (20) unsigned NOT NULL,
start_date timestamp NOT NULL,
end_date timestamp,
published tinyint(1) NOT NULL DEFAULT 0,
updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY  (ID)
) $COLLATE;