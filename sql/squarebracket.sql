-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2021 at 01:02 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

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
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
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
  `sender` int(11) NOT NULL
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
  `views` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Video views',
  `flags` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '8 bools to determine certain video properties',
  `category_id` int(11) DEFAULT 0 COMMENT 'Category ID for the video',
  `tags` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Video tags, serialized in JSON',
  `videofile` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path to the video file(?)',
  `videolength` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Length of the video in seconds'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `z_categories`
--

CREATE TABLE `z_categories` (
  `id` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `ord` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `z_forums`
--

CREATE TABLE `z_forums` (
  `id` int(5) UNSIGNED NOT NULL DEFAULT 0,
  `cat` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `ord` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `descr` varchar(255) NOT NULL,
  `threads` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `posts` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `lastdate` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `lastuser` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `lastid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `private` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `readonly` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `z_forumsread`
--

CREATE TABLE `z_forumsread` (
  `uid` int(10) UNSIGNED NOT NULL,
  `fid` int(5) UNSIGNED NOT NULL,
  `time` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `z_groups`
--

CREATE TABLE `z_groups` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `title` varchar(32) NOT NULL,
  `nc` varchar(6) NOT NULL,
  `inherit_group_id` tinyint(3) UNSIGNED NOT NULL,
  `sortorder` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `visible` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `z_perm`
--

CREATE TABLE `z_perm` (
  `id` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `permbind_id` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `z_permx`
--

CREATE TABLE `z_permx` (
  `id` int(11) UNSIGNED NOT NULL,
  `x_id` int(11) UNSIGNED NOT NULL,
  `x_type` varchar(64) NOT NULL,
  `perm_id` varchar(64) NOT NULL,
  `permbind_id` varchar(64) NOT NULL,
  `bindvalue` int(11) UNSIGNED NOT NULL,
  `revoke` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `z_pmsgs`
--

CREATE TABLE `z_pmsgs` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `date` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `userto` int(10) UNSIGNED NOT NULL,
  `userfrom` int(10) UNSIGNED NOT NULL,
  `unread` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `del_from` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `del_to` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `z_posts`
--

CREATE TABLE `z_posts` (
  `id` int(11) UNSIGNED NOT NULL,
  `user` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `thread` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `date` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `z_poststext`
--

CREATE TABLE `z_poststext` (
  `id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `text` text NOT NULL,
  `revision` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `date` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `user` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `z_threads`
--

CREATE TABLE `z_threads` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `replies` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `views` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `forum` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `lastdate` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `lastuser` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `lastid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `sticky` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `closed` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `z_threadsread`
--

CREATE TABLE `z_threadsread` (
  `uid` int(10) UNSIGNED NOT NULL,
  `tid` int(10) UNSIGNED NOT NULL,
  `time` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

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
-- Indexes for table `z_categories`
--
ALTER TABLE `z_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_forums`
--
ALTER TABLE `z_forums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_forumsread`
--
ALTER TABLE `z_forumsread`
  ADD UNIQUE KEY `uid` (`uid`,`fid`);

--
-- Indexes for table `z_groups`
--
ALTER TABLE `z_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_perm`
--
ALTER TABLE `z_perm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_permx`
--
ALTER TABLE `z_permx`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_pmsgs`
--
ALTER TABLE `z_pmsgs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_posts`
--
ALTER TABLE `z_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `threadid` (`thread`);

--
-- Indexes for table `z_poststext`
--
ALTER TABLE `z_poststext`
  ADD PRIMARY KEY (`id`,`revision`);

--
-- Indexes for table `z_threads`
--
ALTER TABLE `z_threads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_threadsread`
--
ALTER TABLE `z_threadsread`
  ADD UNIQUE KEY `uid` (`uid`,`tid`);

--
-- AUTO_INCREMENT for dumped tables
--

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

--
-- AUTO_INCREMENT for table `z_groups`
--
ALTER TABLE `z_groups`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `z_permx`
--
ALTER TABLE `z_permx`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `z_pmsgs`
--
ALTER TABLE `z_pmsgs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `z_posts`
--
ALTER TABLE `z_posts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `z_threads`
--
ALTER TABLE `z_threads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
