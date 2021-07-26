-- Adminer 4.8.0 MySQL 5.5.5-10.5.9-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Incrementing ID for internal purposes.',
  `username` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Username, chosen by the user',
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User Email.',
  `password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Password, hashed in bcrypt.',
  `token` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User token for cookie authentication.',
  `joined` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT 'User''s join date',
  `lastview` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT 'Timestamp of last view',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User''s description',
  `color` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT '#523bb8' COMMENT 'The color that the user has set for their profile',
  `language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en-US' COMMENT 'Language (Defaults to English)',
  `u_flags` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '8 bools to determine certain user properties',
  `powerlevel` tinyint(4) unsigned NOT NULL DEFAULT 1 COMMENT '0 - banned. 1 - normal user. 2 - moderator. 3 - administrator',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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
  `tags` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Video tags, serialized in JSON',
  `videofile` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path to the video file(?)',
  `videolength` bigint(20) unsigned DEFAULT NULL COMMENT 'Length of the video in seconds',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `comments` (
  `id` text NOT NULL COMMENT 'ID to video or user.',
  `comment` text NOT NULL COMMENT 'The comment itself, formatted in Markdown.',
  `author` bigint(20) NOT NULL COMMENT 'Numerical ID of comment author.',
  `date` bigint(20) NOT NULL COMMENT 'UNIX timestamp when the comment was posted.',
  `deleted` tinyint(4) NOT NULL COMMENT 'States that the comment is deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `rating` (
  `user` bigint(20) unsigned NOT NULL COMMENT 'User that does the rating.',
  `video` bigint(20) unsigned NOT NULL COMMENT 'Video that is being rated.',
  `rating` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT '1 for like, 0 for dislike.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `passwordresets` (
  `id` varchar(64) NOT NULL COMMENT 'ID for password reset URL.',
  `user` int(11) NOT NULL COMMENT 'The user that requested a password reset.',
  `time` int(11) NOT NULL COMMENT 'The time when the password reset request was mades.',
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Boolean to check if password reset URL is active.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL COMMENT 'ID of the user that wants to subscribe to a user.',
  `user` int(11) NOT NULL COMMENT 'The user that the user wants to subscribe to.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Gamerappa is gay
-- ROllerozxa is gay
