-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2019 年 08 月 04 日 18:30
-- 服务器版本: 5.5.53
-- PHP 版本: 5.4.45

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `xc-admin`
--

-- --------------------------------------------------------

--
-- 表的结构 `xc_permission`
--

CREATE TABLE IF NOT EXISTS `xc_permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '权限节点名称',
  `type` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '权限类型1api权限2前路由权限',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限分组id',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT '权限路径',
  `path_id` varchar(100) NOT NULL DEFAULT '' COMMENT '路径唯一编码',
  `description` varchar(200) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态0未启用1正常',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `sort_num` int(11) NOT NULL DEFAULT '0' COMMENT '排序码',
  `icon` varchar(30) NOT NULL DEFAULT '' COMMENT 'FA图标',
  `is_menu` int(2) NOT NULL DEFAULT '0' COMMENT '是否显示在菜单里',
  PRIMARY KEY (`id`),
  KEY `idx_permission` (`path_id`,`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='权限节点' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `xc_permission`
--

INSERT INTO `xc_permission` (`id`, `name`, `type`, `category_id`, `path`, `path_id`, `description`, `status`, `create_time`, `sort_num`, `icon`, `is_menu`) VALUES
(1, '文章列表查询', 1, 1, 'article/content/list', 'd960f3c54713200775b25d687f63eba0', '文章列表查询', 1, 0, 0, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `xc_permission_category`
--

CREATE TABLE IF NOT EXISTS `xc_permission_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '权限分组名称',
  `description` varchar(200) NOT NULL DEFAULT '' COMMENT '权限分组描述',
  `status` smallint(4) unsigned NOT NULL DEFAULT '1' COMMENT '权限分组状态1有效2无效',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '权限分组创建时间',
  `sort_num` int(11) NOT NULL DEFAULT '0' COMMENT '排序码',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT 'FA图标',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `xc_permission_category`
--

INSERT INTO `xc_permission_category` (`id`, `name`, `description`, `status`, `create_time`, `sort_num`, `icon`) VALUES
(4, '用户管理组', '网站用户的管理', 1, 0, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `xc_role`
--

CREATE TABLE IF NOT EXISTS `xc_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '角色名',
  `description` varchar(200) NOT NULL DEFAULT '' COMMENT '角色描述',
  `status` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态1正常0未启用',
  `sort_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序值',
  PRIMARY KEY (`id`),
  KEY `idx_role` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='角色' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `xc_role`
--

INSERT INTO `xc_role` (`id`, `name`, `description`, `status`, `sort_num`) VALUES
(1, '内容管理员', '负责网站内容管理', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `xc_role_permission`
--

CREATE TABLE IF NOT EXISTS `xc_role_permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色编号',
  `permission_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='角色权限对应表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `xc_role_permission`
--

INSERT INTO `xc_role_permission` (`id`, `role_id`, `permission_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3);

-- --------------------------------------------------------

--
-- 表的结构 `xc_user`
--

CREATE TABLE IF NOT EXISTS `xc_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '用户密码',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号码',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登录时间',
  `status` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态0禁用1正常',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '账号创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '信息更新时间',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '真实姓名',
  PRIMARY KEY (`id`),
  KEY `idx_user` (`username`,`mobile`,`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='用户表' AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `xc_user`
--

INSERT INTO `xc_user` (`id`, `username`, `password`, `mobile`, `last_login_time`, `status`, `create_time`, `update_time`, `realname`) VALUES
(1, 'admin', '52dc80f1ab7fbb08dcc0158f2f03ef86', '18600001234', 0, 1, 1564750548, 0, '超级管理员'),
(2, '1', '123123', '1', 0, 0, 0, 1564905766, ''),
(3, 'test', 'e8f4648756430e460d3f256ce592a5ac', '', 0, 1, 1564814254, 0, ''),
(4, 'test1', 'af97524658bc77e3c0662f6667035974', '', 0, 1, 1564814540, 0, ''),
(5, 'test2', '7e1fd68075ff3e711bdd345747f80fcf', '', 0, 0, 1564814550, 0, ''),
(6, 'test3', 'b44ecc87418b9aee6345c142dc09bb49', '', 0, 0, 1564814681, 0, ''),
(7, 'test5', '676b6fba9d47e9f50df609ec8fd98483', '', 0, 0, 1564814784, 0, ''),
(8, 'test6', '838b736589169c06d7e144fee1977d13', '', 0, 1, 1564814927, 0, ''),
(9, 'test8', '2705bac6b4336ee8fddcbbcb840adadc', '', 0, 1, 1564815137, 0, ''),
(10, 'test9', '22e9eee38221ead57d5d1e2f66e43766', '', 0, 1, 1564815167, 0, ''),
(11, 'test10', 'c10a9b1c8655b8c076fbb6c97c5672ee', '', 0, 1, 1564899142, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `xc_user_role`
--

CREATE TABLE IF NOT EXISTS `xc_user_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='用户角色对应关系' AUTO_INCREMENT=16 ;

--
-- 转存表中的数据 `xc_user_role`
--

INSERT INTO `xc_user_role` (`id`, `user_id`, `role_id`) VALUES
(1, 1, 1),
(2, 3, 1),
(3, 4, 1),
(4, 5, 1),
(5, 6, 1),
(6, 7, 1),
(7, 8, 1),
(8, 9, 1),
(9, 10, 1),
(10, 11, 1),
(15, 2, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
