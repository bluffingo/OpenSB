-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2021 at 03:47 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `squarebracket`
--

-- --------------------------------------------------------

--
-- Table structure for table `channel_comments`
--

CREATE TABLE `channel_comments` (
  `comment_id` int(11) NOT NULL,
  `id` text NOT NULL,
  `comment` text NOT NULL,
  `author` bigint(20) NOT NULL,
  `date` bigint(20) NOT NULL,
  `deleted` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `channel_settings`
--

CREATE TABLE `channel_settings` (
  `user` int(11) NOT NULL,
  `background` varchar(7) NOT NULL DEFAULT '#ffffff',
  `fontcolor` varchar(7) NOT NULL DEFAULT '#222222',
  `titlefont` varchar(7) NOT NULL DEFAULT '#ffffff',
  `link` varchar(7) NOT NULL DEFAULT '#0033CC',
  `headerfont` varchar(7) NOT NULL DEFAULT '#ffffff',
  `highlightheader` varchar(7) NOT NULL DEFAULT '#3399cc',
  `highlightinside` varchar(7) NOT NULL DEFAULT '#ecf4fb',
  `regularheader` varchar(7) NOT NULL DEFAULT '#3399cc',
  `regularinside` varchar(7) NOT NULL DEFAULT '#ffffff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `id` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ID to video or user.',
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The comment itself, formatted in Markdown.',
  `author` bigint(20) NOT NULL COMMENT 'Numerical ID of comment author.',
  `date` bigint(20) NOT NULL COMMENT 'UNIX timestamp when the comment was posted.',
  `deleted` tinyint(4) NOT NULL COMMENT 'States that the comment is deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `music`
--

CREATE TABLE `music` (
  `ID` int(11) NOT NULL,
  `music_id` varchar(11) NOT NULL,
  `title` text NOT NULL,
  `author` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `file` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `level` int(11) DEFAULT NULL,
  `recipient` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `passwordresets`
--

CREATE TABLE `passwordresets` (
  `id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ID for password reset URL.',
  `user` int(11) NOT NULL COMMENT 'The user that requested a password reset.',
  `time` int(11) NOT NULL COMMENT 'The time when the password reset request was mades.',
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Boolean to check if password reset URL is active.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `user` bigint(20) UNSIGNED NOT NULL COMMENT 'User that does the rating.',
  `video` bigint(20) UNSIGNED NOT NULL COMMENT 'Video that is being rated.',
  `rating` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 for like, 0 for dislike.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL COMMENT 'ID of the user that wants to subscribe to a user.',
  `user` int(11) NOT NULL COMMENT 'The user that the user wants to subscribe to.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL COMMENT 'Incrementing ID for internal purposes.',
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Username, chosen by the user',
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User Email.',
  `password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Password, hashed in bcrypt.',
  `token` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User token for cookie authentication.',
  `joined` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'User''s join date',
  `lastview` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Timestamp of last view',
  `lastpost` int(11) NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User''s description',
  `customcolor` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT '#523bb8' COMMENT 'The color that the user has set for their profile',
  `language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en-US' COMMENT 'Language (Defaults to English)',
  `avatar` tinyint(1) NOT NULL DEFAULT 0,
  `u_flags` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '8 bools to determine certain user properties',
  `powerlevel` tinyint(4) UNSIGNED NOT NULL DEFAULT 1 COMMENT '0 - banned. 1 - normal user. 2 - moderator. 3 - administrator',
  `group_id` int(11) NOT NULL DEFAULT 3 COMMENT 'Legacy Acmlmboard-related group ID field.',
  `posts` int(11) NOT NULL,
  `threads` int(11) NOT NULL,
  `blockland_id` int(11) NOT NULL COMMENT 'Blockland ID, intended for internal Vitre testing.',
  `signature` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Incrementing ID for internal purposes.',
  `video_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Random alphanumeric video ID which will be visible.',
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Video title',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Video description',
  `author` bigint(20) UNSIGNED NOT NULL COMMENT 'User ID of the video author',
  `time` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Unix timestamp for the time the video was uploaded',
  `most_recent_view` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'anti-bot shit is useless for this tbh',
  `views` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Video views',
  `flags` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '8 bools to determine certain video properties',
  `category_id` int(11) DEFAULT 0 COMMENT 'Category ID for the video',
  `tags` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Video tags, serialized in JSON',
  `videofile` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path to the video file(?)',
  `videolength` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Length of the video in seconds'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `views`
--

CREATE TABLE `views` (
  `video_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `channel_comments`
--
ALTER TABLE `channel_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `channel_settings`
--
ALTER TABLE `channel_settings`
  ADD PRIMARY KEY (`user`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `channel_comments`
--
ALTER TABLE `channel_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `music`
--
ALTER TABLE `music`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Incrementing ID for internal purposes.';

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Incrementing ID for internal purposes.';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
