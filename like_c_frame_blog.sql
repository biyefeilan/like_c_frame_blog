-- phpMyAdmin SQL Dump
-- version 2.11.2.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 07 月 06 日 09:22
-- 服务器版本: 5.0.45
-- PHP 版本: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `like_c_frame_blog`
--

-- --------------------------------------------------------

--
-- 表的结构 `admins`
--

CREATE TABLE `admins` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `email` varchar(200) NOT NULL,
  `department` varchar(50) NOT NULL,
  `department_id` smallint(5) unsigned NOT NULL,
  `admin_id` int(8) unsigned NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `modify_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=521 ;

--
-- 导出表中的数据 `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `department`, `department_id`, `admin_id`, `add_time`, `modify_time`) VALUES
(1, 'administrator', '1234', 'administator@126.com', '', 0, 0, 0, 0),
(520, 'chenbo', 'chenbo', 'chenbo@126.com', '雷禅', 8, 1, 1340962476, 0);

-- --------------------------------------------------------

--
-- 表的结构 `admins_roles`
--

CREATE TABLE `admins_roles` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `admin_id` int(8) unsigned NOT NULL,
  `role_id` smallint(4) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `admins_roles`
--


-- --------------------------------------------------------

--
-- 表的结构 `articles`
--

CREATE TABLE `articles` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `article_id` bigint(10) unsigned NOT NULL default '0' COMMENT '如果是对文章的点评，此字段为对应文章id',
  `title` varchar(200) NOT NULL,
  `author` varchar(30) NOT NULL,
  `guide` text NOT NULL,
  `content` text NOT NULL,
  `img` text NOT NULL COMMENT '文章的标题图片的url',
  `category_id` mediumint(6) unsigned NOT NULL,
  `author_id` int(10) unsigned NOT NULL default '0',
  `add_time` int(10) unsigned NOT NULL,
  `modify_time` int(10) unsigned NOT NULL default '0',
  `url` text NOT NULL COMMENT '文章静态文件的地址',
  `hits` bigint(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '9' COMMENT '9-等待 1-未通过 0-OK',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=6 ;

--
-- 导出表中的数据 `articles`
--

INSERT INTO `articles` (`id`, `article_id`, `title`, `author`, `guide`, `content`, `img`, `category_id`, `author_id`, `add_time`, `modify_time`, `url`, `hits`, `status`) VALUES
(2, 0, '美丽的大脚', '大脚', '', '美丽的大脚，美丽的一天。', '', 0, 0, 0, 0, '', 0, 9),
(3, 0, '明天星期五', '高兴', '唉', '明天星期五，后天得加班。', '', 0, 1, 1340871517, 1340877473, '', 0, 9),
(4, 0, '这个杀手不太冷', '东水', '', '这个杀手不太冷，不错', '', 0, 1, 1340872445, 0, '', 0, 0),
(5, 0, '第一滴血', 'Ranboo', '', '第一滴血不错。', '', 0, 0, 0, 0, '', 0, 9);

-- --------------------------------------------------------

--
-- 表的结构 `articles_check`
--

CREATE TABLE `articles_check` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `article_id` int(8) unsigned NOT NULL,
  `article_status` tinyint(1) unsigned NOT NULL,
  `checker` varchar(50) NOT NULL,
  `admin_id` int(8) unsigned NOT NULL default '0',
  `author_id` int(8) unsigned NOT NULL default '0',
  `explain` text NOT NULL,
  `read` tinyint(1) unsigned NOT NULL default '1' COMMENT '1-未读 0-已读',
  `time` bigint(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `articles_check`
--


-- --------------------------------------------------------

--
-- 表的结构 `articles_positions`
--

CREATE TABLE `articles_positions` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `user_id` int(8) unsigned NOT NULL default '0' COMMENT '推荐人id',
  `user_type` enum('超级管理员','管理员','普通用户') NOT NULL default '管理员',
  `article_id` bigint(10) unsigned NOT NULL default '0',
  `position_id` int(8) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `author` varchar(30) NOT NULL,
  `guide` text NOT NULL,
  `reason` text NOT NULL,
  `img` text NOT NULL COMMENT '文章的标题图片的url',
  `author_id` int(10) unsigned NOT NULL default '0',
  `url` text NOT NULL COMMENT '文章静态文件的地址',
  `sort` int(8) NOT NULL default '0',
  `add_time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `articles_positions`
--


-- --------------------------------------------------------

--
-- 表的结构 `articles_tags`
--

CREATE TABLE `articles_tags` (
  `id` bigint(11) unsigned NOT NULL auto_increment,
  `article_id` int(10) unsigned NOT NULL,
  `tag_id` int(8) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=2 ;

--
-- 导出表中的数据 `articles_tags`
--


-- --------------------------------------------------------

--
-- 表的结构 `attachments`
--

CREATE TABLE `attachments` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `url` varchar(100) NOT NULL,
  `ref_count` int(8) unsigned NOT NULL default '0',
  `size` bigint(11) unsigned NOT NULL,
  `is_img` tinyint(1) unsigned NOT NULL default '1',
  `add_time` bigint(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='附件' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `attachments`
--


-- --------------------------------------------------------

--
-- 表的结构 `authors`
--

CREATE TABLE `authors` (
  `id` int(10) NOT NULL auto_increment,
  `rank_id` smallint(5) unsigned NOT NULL default '0',
  `pseudonym` varchar(30) NOT NULL COMMENT '名笔',
  `name` varchar(30) NOT NULL COMMENT '真名',
  `email` varchar(150) NOT NULL,
  `password` varchar(30) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `msn` varchar(150) NOT NULL,
  `page` text NOT NULL COMMENT '个人主页',
  `sex` tinyint(1) unsigned NOT NULL default '0' COMMENT '0-女 1-男',
  `photo` text NOT NULL,
  `address` text NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `login_times` int(8) unsigned NOT NULL default '0',
  `status` tinyint(2) unsigned NOT NULL default '2' COMMENT 'status总共3位，最高位为0表示不在线，为1表示在线状态 为9表示注销或禁用',
  `reg_time` int(10) unsigned NOT NULL,
  `disabled` tinyint(1) unsigned NOT NULL default '0' COMMENT '0 正常 1 自己注销 2 被管理员禁用',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=2 ;

--
-- 导出表中的数据 `authors`
--

INSERT INTO `authors` (`id`, `rank_id`, `pseudonym`, `name`, `email`, `password`, `mobile`, `msn`, `page`, `sex`, `photo`, `address`, `postcode`, `login_times`, `status`, `reg_time`, `disabled`) VALUES
(1, 0, 'test', 'test', 'test', '1234', '122', '212', '212', 0, '', '', '', 0, 4, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `authors_roles`
--

CREATE TABLE `authors_roles` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `author_id` int(8) unsigned NOT NULL,
  `role_id` smallint(4) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `authors_roles`
--


-- --------------------------------------------------------

--
-- 表的结构 `categories`
--

CREATE TABLE `categories` (
  `id` smallint(4) unsigned NOT NULL auto_increment,
  `parent_id` smallint(4) unsigned NOT NULL default '0',
  `name` varchar(200) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `url` text NOT NULL,
  `display` tinyint(1) unsigned NOT NULL default '1',
  `sort` int(8) unsigned NOT NULL default '0',
  `system` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否系统栏目',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='栏目' AUTO_INCREMENT=26 ;

--
-- 导出表中的数据 `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `folder`, `description`, `url`, `display`, `sort`, `system`) VALUES
(1, 0, '散文', 'sanwen', '散文', '/sanwen/', 1, 5, 1),
(2, 0, '小说', 'xiaoshuo', '小说', '/xiaoshuo/', 1, 4, 1),
(3, 0, '故事', 'gushi', '故事', '/gushi/', 1, 3, 1),
(4, 0, '杂文', 'zawen', '杂文', '/zawen/', 1, 2, 1),
(5, 0, '诗歌', 'shige', '诗歌', '/shige/', 1, 1, 1),
(6, 1, '抒情散文', 'feeling', '抒情散文', '/feeling/', 1, 4, 1),
(7, 1, '叙事散文', 'narrate', '叙事散文', '/narrate/', 1, 3, 1),
(8, 1, '写景散文', 'scene', '写景散文', '/scene/', 1, 2, 1),
(9, 1, '议论散文', 'discuss', '议论散文', '/discuss/', 1, 1, 1),
(10, 2, '都市言情', 'doushi', '都市言情', '/doushi/', 1, 5, 1),
(11, 2, '历史武侠', 'wuxia', '历史武侠', '/wuxia/', 1, 4, 1),
(12, 2, '科幻魔幻', 'kehuan', '科幻魔幻', '/kehuan/', 1, 3, 1),
(13, 2, '推理悬疑', 'tuili', '推理悬疑', '/tuili/', 1, 2, 1),
(14, 3, '恐怖故事', 'terror', '恐怖故事', '/terror/', 1, 4, 1),
(15, 3, '神话故事', 'myth', '神话故事', '/myth/', 1, 3, 1),
(16, 3, '童话故事', 'fairy', '童话故事', '/fairy/', 1, 2, 1),
(17, 3, '民间故事', 'folk', '民间故事', '/folk/', 1, 1, 1),
(18, 4, '时事评论', 'shishi', '时事评论', '/shishi/', 1, 4, 1),
(19, 4, '乱弹八卦', 'luantan', '乱弹八卦', '/luantan/', 1, 3, 1),
(20, 4, '处世之道', 'chushi', '处世之道', '/chushi/', 1, 2, 1),
(21, 4, '影评书评', 'yingping', '影评书评', '/yingping/', 1, 1, 1),
(22, 5, '现代诗歌', 'xiandai', '现代诗歌', '/xiandai/', 1, 4, 1),
(23, 5, '爱情诗歌', 'aiqing', '爱情诗歌', '/aiqing/', 1, 3, 1),
(24, 5, '古代诗歌', 'gudai', '古代诗歌', '/gudai/', 1, 2, 1),
(25, 5, '经典诗歌', 'jingdian', '经典诗歌', '/jingdian/', 1, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `comments`
--

CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ref_id` int(10) unsigned NOT NULL default '0',
  `article_id` int(8) unsigned NOT NULL,
  `content` text NOT NULL,
  `author` varchar(60) NOT NULL COMMENT '作者名或ip',
  `author_id` int(8) unsigned NOT NULL default '0',
  `up` int(8) unsigned NOT NULL default '0' COMMENT '赞同',
  `down` int(8) unsigned NOT NULL default '0' COMMENT '反对',
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=2 ;

--
-- 导出表中的数据 `comments`
--

INSERT INTO `comments` (`id`, `ref_id`, `article_id`, `content`, `author`, `author_id`, `up`, `down`, `time`) VALUES
(1, 0, 3, '不错', '127.0.0.1', 0, 0, 0, 1340947291);

-- --------------------------------------------------------

--
-- 表的结构 `departments`
--

CREATE TABLE `departments` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `parent_id` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=9 ;

--
-- 导出表中的数据 `departments`
--

INSERT INTO `departments` (`id`, `parent_id`, `name`, `description`) VALUES
(1, 0, 'IPS', 'IPS'),
(2, 0, '防火墙', '防火墙'),
(3, 0, '主机审计', '主机审计'),
(4, 1, 'chenbo', 'chenbo'),
(5, 1, 'wang', 'wang'),
(6, 2, 'dhx', 'dxh'),
(7, 2, 'mm', 'mm'),
(8, 4, '雷禅', '雷禅');

-- --------------------------------------------------------

--
-- 表的结构 `departments_menus`
--

CREATE TABLE `departments_menus` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `department_id` smallint(5) unsigned NOT NULL,
  `menu_id` int(8) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `departments_menus`
--


-- --------------------------------------------------------

--
-- 表的结构 `departments_roles`
--

CREATE TABLE `departments_roles` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `department_id` smallint(5) unsigned NOT NULL,
  `role_id` smallint(4) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `departments_roles`
--


-- --------------------------------------------------------

--
-- 表的结构 `dicts`
--

CREATE TABLE `dicts` (
  `id` bigint(12) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `replace_desc` text NOT NULL,
  `name_pattern` varchar(300) NOT NULL,
  `replace_pattern` varchar(300) NOT NULL COMMENT '会用preg_replace替换，这里是正则。',
  `type` enum('关键词','敏感词') NOT NULL default '关键词',
  `sys_add` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否系统自动添加',
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `dicts`
--


-- --------------------------------------------------------

--
-- 表的结构 `login_logs`
--

CREATE TABLE `login_logs` (
  `id` bigint(10) unsigned NOT NULL auto_increment,
  `user_id` int(8) unsigned NOT NULL,
  `user_type` enum('超级管理员','管理员','普通用户') NOT NULL,
  `login_time` bigint(10) unsigned NOT NULL,
  `loginout_time` bigint(10) NOT NULL,
  `login_ip` varchar(50) NOT NULL,
  `login_addr` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=5 ;

--
-- 导出表中的数据 `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `user_type`, `login_time`, `loginout_time`, `login_ip`, `login_addr`) VALUES
(1, 1, '管理员', 1309759900, 1309761459, '127.0.0.1', '127.0.0.1'),
(2, 1, '管理员', 1309930749, 1309941146, '127.0.0.1', '127.0.0.1'),
(3, 1, '管理员', 1309942041, 1309943369, '127.0.0.1', '127.0.0.1'),
(4, 1, '管理员', 1309943540, 1309943621, '127.0.0.1', '127.0.0.1');

-- --------------------------------------------------------

--
-- 表的结构 `logs`
--

CREATE TABLE `logs` (
  `id` bigint(11) unsigned NOT NULL auto_increment,
  `user_id` int(8) unsigned NOT NULL,
  `menu_id` int(8) unsigned NOT NULL default '0',
  `user_type` enum('管理员','普通用户') NOT NULL,
  `operate` varchar(50) NOT NULL,
  `uri` varchar(300) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `level` int(5) NOT NULL default '0',
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `logs`
--


-- --------------------------------------------------------

--
-- 表的结构 `menus`
--

CREATE TABLE `menus` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `parent_id` int(8) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `module` varchar(50) NOT NULL,
  `class` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `data` varchar(200) NOT NULL,
  `display` tinyint(1) NOT NULL default '0',
  `sort` int(8) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `menus`
--


-- --------------------------------------------------------

--
-- 表的结构 `moods`
--

CREATE TABLE `moods` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='顶踩' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `moods`
--


-- --------------------------------------------------------

--
-- 表的结构 `pages`
--

CREATE TABLE `pages` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=7 ;

--
-- 导出表中的数据 `pages`
--

INSERT INTO `pages` (`id`, `name`, `url`) VALUES
(1, '网站首页', '/'),
(2, '散文首页', '/sanwen/'),
(3, '小说首页', '/xiaoshuo/'),
(4, '故事首页', '/gushi/'),
(5, '杂文首页', '/zawen/'),
(6, '诗歌首页', '/shige/');

-- --------------------------------------------------------

--
-- 表的结构 `positions`
--

CREATE TABLE `positions` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `page_name` varchar(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `page_id` smallint(6) NOT NULL,
  `description` text NOT NULL,
  `add_time` bigint(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=7 ;

--
-- 导出表中的数据 `positions`
--

INSERT INTO `positions` (`id`, `page_name`, `name`, `page_id`, `description`, `add_time`) VALUES
(1, '散文首页', '推荐排行', 2, 'slides10篇文章的推荐', 0),
(2, '小说首页', '推荐排行', 3, 'slides10篇文章的推荐', 1309918533),
(3, '故事首页', '推荐排行', 4, 'slides10篇文章的推荐', 1309918545),
(4, '杂文首页', '推荐排行', 5, 'slides10篇文章的推荐', 1309918550),
(5, '诗歌首页', '推荐排行', 6, 'slides10篇文章的推荐', 1309918557),
(6, '网站首页', '推荐排行', 1, 'slides10篇文章的推荐', 1309918562);

-- --------------------------------------------------------

--
-- 表的结构 `ranks`
--

CREATE TABLE `ranks` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `parent_id` smallint(5) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `ranks`
--


-- --------------------------------------------------------

--
-- 表的结构 `ranks_roles`
--

CREATE TABLE `ranks_roles` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `rank_id` smallint(5) unsigned NOT NULL,
  `role_id` smallint(4) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `ranks_roles`
--


-- --------------------------------------------------------

--
-- 表的结构 `roles`
--

CREATE TABLE `roles` (
  `id` smallint(4) unsigned NOT NULL auto_increment,
  `parent_id` smallint(4) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `roles`
--


-- --------------------------------------------------------

--
-- 表的结构 `roles_menus`
--

CREATE TABLE `roles_menus` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `role_id` smallint(4) unsigned NOT NULL,
  `menu_id` int(8) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL default '0' COMMENT '0-可访问 1-可授权',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `roles_menus`
--


-- --------------------------------------------------------

--
-- 表的结构 `tags`
--

CREATE TABLE `tags` (
  `id` bigint(10) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=3 ;

--
-- 导出表中的数据 `tags`
--

INSERT INTO `tags` (`id`, `name`, `description`, `time`) VALUES
(2, '经典', '经典', 1341206670);
