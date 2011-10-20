-- Adminer 3.2.2 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `bracket`;
CREATE TABLE `bracket` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `size` int(11) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `game_id` int(11) unsigned NOT NULL,
  `arena` int(11) unsigned NOT NULL,
  `team_size` int(10) unsigned NOT NULL,
  `special_image` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `image` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `team__attend__bracket`;
CREATE TABLE `team__attend__bracket` (
  `team_id` int(10) unsigned NOT NULL,
  `bracket_id` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `match_id` int(11) NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`match_id`),
  UNIQUE KEY `bracket_id` (`bracket_id`,`position`,`match_id`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `nick` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mnumber` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `authority` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `user__register__team`;
CREATE TABLE `user__register__team` (
  `user_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `officer` tinyint(3) unsigned DEFAULT NULL,
  `player` tinyint(3) unsigned DEFAULT NULL,
  `active` tinyint(3) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- 2011-10-20 15:52:56
