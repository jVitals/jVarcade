CREATE TABLE IF NOT EXISTS `#__jvarcade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `score` double NOT NULL,
  `ip` varchar(13) NOT NULL,
  `gameid` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_idx` (`userid`,`gameid`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_contentrating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `warningrequired` tinyint(1) NOT NULL DEFAULT '0',
  `imagename` varchar(255) NOT NULL DEFAULT 'group.png',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_contest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `imagename` varchar(255) NOT NULL,
  `startdatetime` datetime NOT NULL,
  `enddatetime` datetime NOT NULL,
  `islimitedtoslots` tinyint(4) NOT NULL DEFAULT '0',
  `minaccesslevelrequired` int(11) NOT NULL DEFAULT '0',
  `published` int(11) DEFAULT '0',
  `hasadvertisedstarted` int(11) DEFAULT '0',
  `hasadvertisedended` int(11) DEFAULT '0',
  `maxplaycount` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_contestgame` (
  `contestid` int(11) NOT NULL,
  `gameid` int(11) NOT NULL,
  PRIMARY KEY (`contestid`,`gameid`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_contestmember` (
  `contestid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `dateregistered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contestid`,`userid`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_contestscore` (
  `userid` int(11) NOT NULL,
  `score` float NOT NULL,
  `ip` varchar(13) NOT NULL,
  `gameid` int(11) NOT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contestid` int(11) NOT NULL,
  `attemptnum` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_faves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_idx` (`userid`,`gid`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `parentid` int(11) NOT NULL DEFAULT '0',
  `viewpermissions` varchar(100) NOT NULL DEFAULT '0',
  `imagename` varchar(255) NOT NULL DEFAULT 'folder.gif',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_gamedata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `gamedata` blob NOT NULL,
  `gameid` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gamename` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `height` int(11) NOT NULL DEFAULT '400',
  `width` int(11) NOT NULL DEFAULT '500',
  `description` mediumtext NOT NULL,
  `numplayed` int(11) NOT NULL DEFAULT '0',
  `imagename` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `background` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `reverse_score` tinyint(1) NOT NULL DEFAULT '0',
  `scoring` tinyint(1) NOT NULL DEFAULT '1',
  `folderid` int(11) DEFAULT NULL,
  `window` tinyint(2) NOT NULL,
  `contentratingid` int(11) NOT NULL DEFAULT '1',
  `gsafe` tinyint(2) NOT NULL DEFAULT '0',
  `ajaxscore` tinyint(1) NOT NULL DEFAULT '0',
  `author` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename_idx` (`filename`),
  UNIQUE KEY `gamename_idx` (`gamename`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_lastvisited` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `gameid` int(11) DEFAULT NULL,
  `folderid` int(11) DEFAULT NULL,
  `sessionid` varchar(200) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid_idx` (`userid`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_leaderboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contestid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`,`contestid`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_ratings` (
  `total_votes` int(8) NOT NULL DEFAULT '0',
  `total_value` float NOT NULL DEFAULT '0',
  `gameid` int(11) NOT NULL,
  `used_ids` varchar(250) NOT NULL,
  UNIQUE KEY `gameid_idx` (`gameid`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `optname` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'default',
  `ord` int(11) NOT NULL DEFAULT '1',
  `type` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`optname`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(100) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `gameid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

CREATE TABLE IF NOT EXISTS `#__jvarcade_achievements` ( 
 	`id` int(11) NOT NULL AUTO_INCREMENT, 
 	`userid` int(11) NOT NULL,
	`gameid` int(11) NOT NULL,
 	`gametitle` varchar(255) NOT NULL, 
 	`title` varchar(255) NOT NULL, 
 	`description` mediumtext NOT NULL, 
 	`icon_url` varchar(1000) NOT NULL,
	`points` int(11) NOT NULL,
 	PRIMARY KEY (`id`), 
 	UNIQUE KEY (`userid`, `title`) 
 ) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'; 
