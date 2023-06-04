-- Adminer 4.8.1 MySQL 10.5.19-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `bans`;
CREATE TABLE `bans` (
  `autoint` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY (`autoint`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `channels`;
CREATE TABLE `channels` (
  `id` bigint(30) unsigned NOT NULL AUTO_INCREMENT,
  `channel_name` varchar(32) NOT NULL,
  `lobby_type` enum('true','false') NOT NULL DEFAULT 'false',
  `locked` enum('true','false') NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`),
  UNIQUE KEY `channel_name_UNIQUE` (`channel_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `channel_comments`;
CREATE TABLE `channel_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` text NOT NULL,
  `reply_to` bigint(20) NOT NULL DEFAULT 0,
  `comment` text NOT NULL,
  `author` bigint(20) NOT NULL,
  `date` bigint(20) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `comment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id` text NOT NULL COMMENT 'ID to video or user.',
  `reply_to` bigint(20) NOT NULL DEFAULT 0,
  `comment` text NOT NULL COMMENT 'The comment itself, formatted in Markdown.',
  `author` bigint(20) NOT NULL COMMENT 'Numerical ID of comment author.',
  `date` bigint(20) NOT NULL COMMENT 'UNIX timestamp when the comment was posted.',
  `deleted` tinyint(4) NOT NULL COMMENT 'States that the comment is deleted',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `favorites`;
CREATE TABLE `favorites` (
  `user_id` int(11) NOT NULL,
  `video_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `ipbans`;
CREATE TABLE `ipbans` (
  `ip` varchar(45) NOT NULL DEFAULT '0.0.0.0',
  `reason` varchar(255) NOT NULL DEFAULT '<em>No reason specified</em>'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `music`;
CREATE TABLE `music` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `music_id` varchar(11) NOT NULL,
  `title` text NOT NULL,
  `author` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `file` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL DEFAULT 'Lorem ipsum',
  `text` text DEFAULT NULL,
  `time` bigint(20) DEFAULT 0,
  `redirect` varchar(256) DEFAULT NULL,
  `author_userid` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `level` int(11) DEFAULT NULL,
  `recipient` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `passwordresets`;
CREATE TABLE `passwordresets` (
  `id` varchar(64) NOT NULL,
  `user` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post` text NOT NULL,
  `author` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `rating`;
CREATE TABLE `rating` (
  `user` bigint(20) unsigned NOT NULL COMMENT 'User that does the rating.',
  `video` bigint(20) unsigned NOT NULL COMMENT 'Video that is being rated.',
  `rating` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '1 for like, 0 for dislike.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL COMMENT 'ID of the user that wants to subscribe to a user.',
  `user` int(11) NOT NULL COMMENT 'The user that the user wants to subscribe to.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `tag_index`;
CREATE TABLE `tag_index` (
  `video_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `tag_meta`;
CREATE TABLE `tag_meta` (
  `tag_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `latestUse` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Incrementing ID for internal purposes.',
  `name` varchar(128) NOT NULL COMMENT 'Username, chosen by the user',
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL COMMENT 'Password, hashed in bcrypt.',
  `token` varchar(128) NOT NULL COMMENT 'User token for cookie authentication.',
  `joined` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT 'User''s join date',
  `lastview` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT 'Timestamp of last view',
  `lastpost` int(10) unsigned NOT NULL DEFAULT 0,
  `title` text NOT NULL COMMENT 'Display Name',
  `about` text DEFAULT NULL COMMENT 'User''s description',
  `customcolor` varchar(7) DEFAULT '#523bb8' COMMENT 'The color that the user has set for their profile',
  `language` varchar(10) NOT NULL DEFAULT 'en-US' COMMENT 'Language (Defaults to English)',
  `avatar` tinyint(1) NOT NULL DEFAULT 0,
  `ip` varchar(48) DEFAULT '999.999.999.999',
  `u_flags` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '8 bools to determine certain user properties',
  `powerlevel` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '0 - banned. 1 - normal user. 2 - moderator. 3 - administrator',
  `group_id` int(11) NOT NULL DEFAULT 3,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Incrementing ID for internal purposes.',
  `video_id` varchar(11) NOT NULL COMMENT 'Random alphanumeric video ID which will be visible.',
  `title` varchar(128) NOT NULL COMMENT 'Video title',
  `description` text DEFAULT NULL COMMENT 'Video description',
  `author` bigint(20) unsigned NOT NULL COMMENT 'User ID of the video author',
  `time` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT 'Unix timestamp for the time the video was uploaded',
  `most_recent_view` bigint(20) unsigned NOT NULL DEFAULT 0,
  `views` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT 'Video views',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '8 bools to determine certain video properties',
  `category_id` int(11) DEFAULT 0 COMMENT 'Category ID for the video',
  `videofile` text DEFAULT NULL COMMENT 'Path to the video file(?)',
  `videolength` bigint(20) unsigned DEFAULT NULL COMMENT 'Length of the video in seconds',
  `tags` text DEFAULT NULL COMMENT 'Video tags, serialized in JSON',
  `post_type` int(11) NOT NULL DEFAULT 0 COMMENT 'The type of the post, 0 is a video, 1 is a legacy video, 2 is art, and 3 is music.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `views`;
CREATE TABLE `views` (
  `video_id` text NOT NULL,
  `user` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2023-06-04 05:03:24
