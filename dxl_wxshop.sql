/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : dxl_wxshop

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2018-03-12 19:54:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wxshop_access
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_access`;
CREATE TABLE `wxshop_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` smallint(6) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wxshop_access
-- ----------------------------

-- ----------------------------
-- Table structure for wxshop_node
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_node`;
CREATE TABLE `wxshop_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT '访问链接',
  `title` varchar(50) DEFAULT NULL COMMENT '节点名称',
  `zhu_module` varchar(255) DEFAULT NULL COMMENT '主模块',
  `access_name` varchar(255) DEFAULT NULL COMMENT '权限名称',
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned DEFAULT '0',
  `ismenu` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wxshop_node
-- ----------------------------
INSERT INTO `wxshop_node` VALUES ('1', '/', '权限管理', '权限管理', '', '1', null, '0', '0', '0', '0', '0', '1');
INSERT INTO `wxshop_node` VALUES ('2', '/Permission/roleList', '角色列表', '权限管理', '角色列表', '1', null, '0', '1', '1', '0', '0', '1');
INSERT INTO `wxshop_node` VALUES ('3', '/Permission/roleAdd', '角色添加', '权限管理', '角色添加', '1', null, '1', '1', '1', '0', '0', '0');
INSERT INTO `wxshop_node` VALUES ('4', '/Permission/roleEdit', '角色编辑', '权限管理', '角色编辑', '1', null, '2', '1', '1', '0', '0', '0');
INSERT INTO `wxshop_node` VALUES ('5', '/Permission/roleDelete', '角色删除', '权限管理', '角色删除', '1', null, '3', '1', '1', '0', '0', '0');
INSERT INTO `wxshop_node` VALUES ('7', '/Permission/nodeList', '节点列表', '权限管理', '节点列表', '1', null, '4', '1', '1', '0', '0', '1');
INSERT INTO `wxshop_node` VALUES ('8', '/Permission/addNode', '节点添加', '权限管理', '节点添加', '1', null, '5', '1', '1', '0', '0', '0');
INSERT INTO `wxshop_node` VALUES ('9', '/Permission/updateNode', '节点修改', '权限管理', '节点修改', '1', null, '6', '1', '1', '0', '0', '0');
INSERT INTO `wxshop_node` VALUES ('10', '/Permission/deleteNode', '节点删除', '权限管理', '节点修改', '1', null, '7', '1', '1', '0', '0', '0');
INSERT INTO `wxshop_node` VALUES ('11', '/Permission/memberList', '用户列表', '权限管理', '用户列表', '1', null, '8', '1', '1', '0', '0', '1');
INSERT INTO `wxshop_node` VALUES ('12', '/Permission/memberAdd', '用户添加', '权限管理', '用户添加', '1', null, '9', '1', '1', '0', '0', '0');
INSERT INTO `wxshop_node` VALUES ('13', '/Permission/memberEdit', '用户修改', '权限管理', '用户修改', '1', null, '10', '1', '1', '0', '0', '0');
INSERT INTO `wxshop_node` VALUES ('14', '/Permission/memberDelete', '用户删除', '权限管理', '用户删除', '1', null, '11', '1', '1', '0', '0', '0');

-- ----------------------------
-- Table structure for wxshop_role
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_role`;
CREATE TABLE `wxshop_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wxshop_role
-- ----------------------------
INSERT INTO `wxshop_role` VALUES ('1', '超级管理员', '1', null, '1483429525', '1483429525');
INSERT INTO `wxshop_role` VALUES ('5', '运营管理', '1', null, '1520586828', '1520586828');
INSERT INTO `wxshop_role` VALUES ('6', '推广人员', '1', null, '1520586837', '1520586837');

-- ----------------------------
-- Table structure for wxshop_role_user
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_role_user`;
CREATE TABLE `wxshop_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wxshop_role_user
-- ----------------------------
INSERT INTO `wxshop_role_user` VALUES ('1', '68');
INSERT INTO `wxshop_role_user` VALUES ('3', '1');
INSERT INTO `wxshop_role_user` VALUES ('3', '2');
INSERT INTO `wxshop_role_user` VALUES ('1', '79');
INSERT INTO `wxshop_role_user` VALUES ('1', '80');
INSERT INTO `wxshop_role_user` VALUES ('1', '81');
INSERT INTO `wxshop_role_user` VALUES ('1', '82');
INSERT INTO `wxshop_role_user` VALUES ('1', '83');

-- ----------------------------
-- Table structure for wxshop_user
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_user`;
CREATE TABLE `wxshop_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `realname` varchar(30) DEFAULT NULL,
  `password` char(32) NOT NULL,
  `bind_account` varchar(50) NOT NULL,
  `last_login_time` int(11) unsigned DEFAULT '0',
  `last_login_ip` varchar(40) DEFAULT NULL,
  `login_count` mediumint(8) unsigned DEFAULT '0',
  `verify` varchar(32) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `type_id` tinyint(2) unsigned DEFAULT '0',
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wxshop_user
-- ----------------------------
INSERT INTO `wxshop_user` VALUES ('68', 'admin', '管理员', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '', '1484182577', '127.0.0.1', '138', null, '', '', '1467963560', '1467963560', '1', '0', '/portrait/57be642fb50eb.png');
INSERT INTO `wxshop_user` VALUES ('80', '测试1', '123456', '测试1', 'e10adc3949ba59abbe56e057f20f883e', '', '1520579106', null, '0', null, '测试1@7477.com', '', '1520579106', '1520579106', '1', '0', '');
INSERT INTO `wxshop_user` VALUES ('79', '测试', '测试', '测试', 'e10adc3949ba59abbe56e057f20f883e', '', '1520579052', null, '0', null, '测试@7477.com', '', '1520579052', '1520579052', '1', '0', '');
INSERT INTO `wxshop_user` VALUES ('81', '12311aa', '撒旦飞洒', '12311aa', 'aa933fe446ea70cb30ef2dc10dd931ae', '', '1520579960', null, '0', null, '12311aa@7477.com', '', '1520579960', '1520579960', '1', '0', '');
INSERT INTO `wxshop_user` VALUES ('82', '阿萨德飞洒', '1241', '阿萨德飞洒', 'c0acf5db94e4966914dde362c7f096ed', '', '1520580037', null, '0', null, '阿萨德飞洒@7477.com', '', '1520580037', '1520580037', '1', '0', '');
