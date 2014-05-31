ALTER TABLE `#__jvarcade_settings` ADD UNIQUE (`optname`);
ALTER TABLE `#__jvarcade_games` ADD `gsafe` tinyint(2) NOT NULL DEFAULT 0;
ALTER TABLE `#__jvarcade_games` DROP COLUMN `mochi`;
INSERT INTO `#__jvarcade_settings` (`optname`,`value`,`group`,`ord`,`type`,`description`) VALUES('homepage_view', 'default', 'frontend', 7, 'select', 'COM_JVARCADE_OPTDESC_HOMEPAGE_VIEW');
INSERT INTO `#__jvarcade_settings` (`optname`,`value`,`group`,`ord`,`type`,`description`) VALUES('aup_itemid', '8', 'integration', 6, 'text', 'COM_JVARCADE_OPTDESC_AUP_ITEMID');
DELETE FROM `#__jvarcade_settings` WHERE `optname` = 'mochi_id';
DELETE FROM `#__jvarcade_settings` WHERE `optname` = 'flat';