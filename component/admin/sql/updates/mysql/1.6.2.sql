CREATE TABLE IF NOT EXISTS `#__redshop_alerts` (
	`id` 		INT(11)      NOT NULL AUTO_INCREMENT,
	`message` 	VARCHAR(255) NOT NULL,
	`sent_date` DATETIME     NOT NULL,
	`read`      TINYINT(4)   NOT NULL,
	PRIMARY KEY (`id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Notification Alert';