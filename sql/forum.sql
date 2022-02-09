-- Adminer 4.8.1 MySQL 5.5.5-10.5.11-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `z_categories` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `ord` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `z_categories` (`id`, `title`, `ord`) VALUES
(1,	'General',	50),
(2,	'Staff forums',	0);

CREATE TABLE `z_forums` (
  `id` int(5) unsigned NOT NULL DEFAULT 0,
  `cat` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `ord` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `descr` varchar(255) NOT NULL,
  `threads` int(10) unsigned NOT NULL DEFAULT 0,
  `posts` int(10) unsigned NOT NULL DEFAULT 0,
  `lastdate` int(10) unsigned NOT NULL DEFAULT 0,
  `lastuser` int(10) unsigned NOT NULL DEFAULT 0,
  `lastid` int(10) unsigned NOT NULL DEFAULT 0,
  `private` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `readonly` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `z_forums` (`id`, `cat`, `ord`, `title`, `descr`, `threads`, `posts`, `lastdate`, `lastuser`, `lastid`, `private`, `readonly`) VALUES
(1,	1,	0,	'Example forum',	'This is an example forum to get started with.',	0,	0,	0,	0,	0,	0,	0),
(2,	2,	0,	'Example staff forum',	'This is an example staff forum to get started with.',	0,	0,	0,	0,	0,	0,	0);

CREATE TABLE `z_forumsread` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(5) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  UNIQUE KEY `uid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `z_groups` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `nc` varchar(6) NOT NULL,
  `inherit_group_id` tinyint(3) unsigned NOT NULL,
  `sortorder` smallint(5) unsigned NOT NULL DEFAULT 0,
  `visible` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `z_groups` (`id`, `title`, `nc`, `inherit_group_id`, `sortorder`, `visible`) VALUES
(1,	'Banned',	'888888',	3,	0,	1),
(2,	'Base User',	'',	0,	100,	0),
(3,	'Normal User',	'4f77ff',	2,	200,	1),
(4,	'Staff',	'',	3,	300,	0),
(5,	'Moderator',	'47B53C',	4,	600,	1),
(6,	'Administrator',	'd8b00d',	5,	700,	1),
(7,	'Root Administrator',	'AA3C3C',	0,	800,	1);

CREATE TABLE `z_perm` (
  `id` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `permbind_id` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `z_perm` (`id`, `title`, `permbind_id`) VALUES
('ban-users',	'Ban Users',	''),
('consecutive-posts',	'Consecutive Posts',	''),
('create-all-private-forum-posts',	'Create All Private Forum Posts',	''),
('create-all-private-forum-threads',	'Create All Private Forum Threads',	''),
('create-pms',	'Create PMs',	''),
('create-private-forum-post',	'Create Private Forum Post',	'forums'),
('create-private-forum-thread',	'Create Private Forum Thread',	'forums'),
('create-public-post',	'Create Public Post',	''),
('create-public-thread',	'Create Public Thread',	''),
('delete-forum-post',	'Delete Forum Post',	'forums'),
('delete-own-pms',	'Delete Own PMs',	''),
('delete-post',	'Delete Post',	''),
('delete-user-pms',	'Delete User PMs',	''),
('edit-all-group',	'Edit All Group Assets',	''),
('edit-all-group-member',	'Edit All User Assets',	''),
('edit-forum-post',	'Edit Forum Post',	'forums'),
('edit-forum-thread',	'Edit Forum Thread',	'forums'),
('edit-forums',	'Edit Forums',	''),
('edit-groups',	'Edit Groups',	''),
('edit-own-permissions',	'Edit Own Permissions',	''),
('edit-own-title',	'Edit Own Title',	''),
('edit-permissions',	'Edit Permissions',	''),
('ignore-thread-time-limit',	'Ignore Thread Time Limit',	''),
('no-restrictions',	'No Restrictions',	''),
('override-closed',	'Post in Closed Threads',	''),
('override-readonly-forums',	'Override Read Only Forums',	''),
('rename-own-thread',	'Rename Own Thread',	''),
('update-own-post',	'Update Own Post',	''),
('update-own-profile',	'Update Own Profile',	''),
('update-post',	'Update Post',	''),
('update-profiles',	'Update Profiles',	''),
('update-thread',	'Update Thread',	''),
('view-all-private-forums',	'View All Private Forums',	''),
('view-post-history',	'View Post History',	''),
('view-private-forum',	'View Private Forum',	'forums'),
('view-user-pms',	'View User PMs',	'');

CREATE TABLE `z_pmsgs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `date` int(11) unsigned NOT NULL DEFAULT 0,
  `userto` int(10) unsigned NOT NULL,
  `userfrom` int(10) unsigned NOT NULL,
  `unread` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `del_from` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `del_to` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `z_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `thread` int(10) unsigned NOT NULL DEFAULT 0,
  `date` int(11) unsigned NOT NULL DEFAULT 0,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `threadid` (`thread`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `z_poststext` (
  `id` int(11) unsigned NOT NULL DEFAULT 0,
  `text` text NOT NULL,
  `revision` smallint(5) unsigned NOT NULL DEFAULT 1,
  `date` int(11) unsigned NOT NULL DEFAULT 0,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`,`revision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `z_threads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `replies` int(10) unsigned NOT NULL DEFAULT 0,
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `forum` int(10) unsigned NOT NULL DEFAULT 0,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `lastdate` int(10) unsigned NOT NULL DEFAULT 0,
  `lastuser` int(10) unsigned NOT NULL DEFAULT 0,
  `lastid` int(10) unsigned NOT NULL DEFAULT 0,
  `sticky` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `closed` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `z_threadsread` (
  `uid` int(10) unsigned NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  UNIQUE KEY `uid` (`uid`,`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `z_permx` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `x_id` int(11) unsigned NOT NULL,
  `x_type` varchar(64) NOT NULL,
  `perm_id` varchar(64) NOT NULL,
  `permbind_id` varchar(64) NOT NULL,
  `bindvalue` int(11) unsigned NOT NULL,
  `revoke` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `z_permx` (`x_id`, `x_type`, `perm_id`, `permbind_id`, `bindvalue`, `revoke`) VALUES
(1,	'group',	'create-public-post',	'',	0,	1),
(1,	'group',	'create-public-thread',	'',	0,	1),
(1,	'group',	'edit-own-title',	'',	0,	1),
(1,	'group',	'rename-own-thread',	'',	0,	1),
(1,	'group',	'update-own-post',	'',	0,	1),
(1,	'group',	'update-own-profile',	'',	0,	1),
(2,	'group',	'create-public-thread',	'',	0,	0),
(3,	'group',	'create-pms',	'',	0,	0),
(3,	'group',	'create-public-post',	'',	0,	0),
(3,	'group',	'create-public-thread',	'',	0,	0),
(3,	'group',	'delete-own-pms',	'',	0,	0),
(3,	'group',	'rename-own-thread',	'',	0,	0),
(3,	'group',	'update-own-post',	'',	0,	0),
(3,	'group',	'update-own-profile',	'',	0,	0),
(5,	'group',	'ban-users',	'',	0,	0),
(5,	'group',	'delete-post',	'',	0,	0),
(5,	'group',	'override-closed',	'',	0,	0),
(5,	'group',	'update-post',	'',	0,	0),
(5,	'group',	'update-thread',	'',	0,	0),
(5,	'group',	'view-post-history',	'',	0,	0),
(6,	'group',	'create-all-private-forum-posts',	'',	0,	0),
(6,	'group',	'create-all-private-forum-threads',	'',	0,	0),
(6,	'group',	'edit-all-group',	'',	0,	0),
(6,	'group',	'edit-forums',	'',	0,	0),
(6,	'group',	'edit-permissions',	'',	0,	0),
(6,	'group',	'override-readonly-forums',	'',	0,	0),
(6,	'group',	'update-profiles',	'',	0,	0),
(6,	'group',	'view-all-private-forums',	'',	0,	0),
(7,	'group',	'no-restrictions',	'',	0,	0),
(2,	'group',	'create-private-forum-post',	'forums',	1,	1),
(2,	'group',	'create-private-forum-thread',	'forums',	1,	1),
(2,	'group',	'delete-forum-post',	'forums',	1,	1),
(2,	'group',	'edit-forum-post',	'forums',	1,	1),
(2,	'group',	'edit-forum-thread',	'forums',	1,	1),
(2,	'group',	'view-private-forum',	'forums',	1,	1),
(2,	'group',	'create-private-forum-post',	'forums',	2,	1),
(2,	'group',	'create-private-forum-thread',	'forums',	2,	1),
(2,	'group',	'delete-forum-post',	'forums',	2,	1),
(2,	'group',	'edit-forum-post',	'forums',	2,	1),
(2,	'group',	'edit-forum-thread',	'forums',	2,	1),
(2,	'group',	'view-private-forum',	'forums',	2,	1),
(4,	'group',	'create-private-forum-post',	'forums',	2,	0),
(4,	'group',	'create-private-forum-thread',	'forums',	2,	0),
(4,	'group',	'delete-forum-post',	'forums',	2,	0),
(4,	'group',	'edit-forum-post',	'forums',	2,	0),
(4,	'group',	'edit-forum-thread',	'forums',	2,	0),
(4,	'group',	'view-private-forum',	'forums',	2,	0);

-- 2021-07-09 20:19:37
