DROP TABLE IF EXISTS `catwoman`;
CREATE TABLE IF NOT EXISTS `catwoman` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(32) NOT NULL,
	`view` int(11) NOT NULL DEFAULT '3',
	PRIMARY KEY (`id`)
);
DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(32) NOT NULL,
	`money` int(11) NOT NULL,
	`url` varchar(32) NOT NULL,
	PRIMARY KEY (`id`)
);
DROP TABLE IF EXISTS `pos`;
CREATE TABLE IF NOT EXISTS `pos` (
	`uname` varchar(64) NOT NULL,
	`icoid` int(11) NOT NULL,
	`actived` tinyint(1) NOT NULL DEFAULT '0',
	KEY `icoid` (`icoid`),
	KEY `uname` (`uname`)
);
DROP TABLE IF EXISTS `posterieur`;
CREATE TABLE IF NOT EXISTS `posterieur` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`cid` int(11) NOT NULL,
	`title` varchar(32) NOT NULL,
	`txt` text NOT NULL,
	PRIMARY KEY (`id`),
	KEY `catwoman` (`cid`)
);
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
	`pseudale` varchar(32) NOT NULL,
	`adm` tinyint(1) NOT NULL DEFAULT '0',
	`pts` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`pseudale`)
);
ALTER TABLE `pos`
ADD CONSTRAINT `icoid` FOREIGN KEY (`icoid`) REFERENCES `items` (`id`),
ADD CONSTRAINT `uname` FOREIGN KEY (`uname`) REFERENCES `user` (`pseudale`);
ALTER TABLE `posterieur` ADD CONSTRAINT `catwoman` FOREIGN KEY (`cid`) REFERENCES `catwoman` (`id`);
