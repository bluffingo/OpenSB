-- Adminer 4.8.0 MySQL 5.5.5-10.5.9-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `videos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Incrementing ID for internal purposes.',
  `video_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Random alphanumeric video ID which will be visible.',
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Video title',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Video description',
  `author` bigint(20) unsigned NOT NULL COMMENT 'User ID of the video author',
  `time` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT 'Unix timestamp for the time the video was uploaded',
  `views` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT 'Video views',
  `flags` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '8 bools to determine certain video properties',
  `category_id` int(11) DEFAULT NULL COMMENT 'Category ID for the video',
  `videofile` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path to the video file(?)',
  `videofile_hd` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path to the HD video file(?)',
  `videolength` bigint(20) unsigned DEFAULT NULL COMMENT 'Length of the video in seconds',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2021-04-24 22:01:47
