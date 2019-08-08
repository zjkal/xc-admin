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
  `is_del` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '软删除:1.已删除',
  PRIMARY KEY (`id`),
  KEY `idx_user` (`username`,`mobile`,`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='用户表' AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `xc_user`
--

INSERT INTO `xc_user` (`id`, `username`, `password`, `mobile`, `last_login_time`, `status`, `create_time`, `update_time`, `realname`) VALUES
(1, 'admin', '52dc80f1ab7fbb08dcc0158f2f03ef86', '18600001234', 0, 1, 1564750548, 0, '超级管理员');

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


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
