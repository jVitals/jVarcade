ALTER TABLE `#__jvarcade_settings` ADD UNIQUE (`optname`);
ALTER TABLE `#__jvarcade_games` CHANGE `mochi` `gsafe` tinyint(2) NOT NULL DEFAULT 0;
INSERT INTO `#__jvarcade_settings` (`optname`,`value`,`group`,`ord`,`type`,`description`) VALUES('homepage_view', 'default', 'frontend', 7, 'select', 'COM_JVARCADE_OPTDESC_HOMEPAGE_VIEW');
DELETE FROM `#__jvarcade_settings` WHERE `optname` = 'mochi_id';