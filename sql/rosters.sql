CREATE TABLE $TABLE_NAME (
ID bigint (20) unsigned NOT NULL AUTO_INCREMENT,
CID bigint (20) unsigned NOT NULL,
CSID bigint (20) unsigned NOT NULL,
SID bigint (20) unsigned NOT NULL,
OID bigint (20) unsigned NOT NULL,
passed tinyint(1),
updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY  (ID)
) $COLLATE;