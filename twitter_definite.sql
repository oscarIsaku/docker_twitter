-- 会員テーブル
CREATE TABLE `members` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `email` varchar(255),
  `password` varchar(100),
  `picture` varchar(255),
  `created` datetime,
  `modified` timestamp,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 投稿テーブル
CREATE TABLE `posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message` text, 
  `member_id` int(11), 
  `reply_post_id` int(11), 
  `created` datetime,
  `modified` timestamp,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;