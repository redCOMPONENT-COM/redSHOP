SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `#__redshop_fields_group` (
  `id` int(11) NOT NULL,
  `name` varchar(125) NOT NULL,
  `description` mediumtext NOT NULL,
  `section` varchar(125) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_by_alias` varchar(125) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `params` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `#__redshop_fields_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

ALTER TABLE `#__redshop_fields_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__redshop_fields` ADD `groupId` INT NOT NULL AFTER `section`;

SET FOREIGN_KEY_CHECKS = 1;