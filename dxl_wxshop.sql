/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.241
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : dxl_wxshop

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2018-04-08 19:19:24
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
INSERT INTO `wxshop_access` VALUES ('5', '18', '1', '1', '分配权限');
INSERT INTO `wxshop_access` VALUES ('5', '17', '1', '1', '分配权限');
INSERT INTO `wxshop_access` VALUES ('5', '15', '1', '1', '用户删除');
INSERT INTO `wxshop_access` VALUES ('5', '13', '1', '1', '用户修改');
INSERT INTO `wxshop_access` VALUES ('5', '12', '1', '1', '用户添加');
INSERT INTO `wxshop_access` VALUES ('5', '11', '1', '1', '用户列表');
INSERT INTO `wxshop_access` VALUES ('5', '5', '1', '1', '角色删除');
INSERT INTO `wxshop_access` VALUES ('5', '4', '1', '1', '角色编辑');
INSERT INTO `wxshop_access` VALUES ('5', '3', '1', '1', '角色添加');
INSERT INTO `wxshop_access` VALUES ('5', '2', '1', '1', '角色列表');
INSERT INTO `wxshop_access` VALUES ('5', '1', '0', '0', '权限管理');

-- ----------------------------
-- Table structure for wxshop_ad
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_ad`;
CREATE TABLE `wxshop_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `pic` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片地址',
  `url` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片链接',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1-启用 0 冻结',
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='广告表';

-- ----------------------------
-- Records of wxshop_ad
-- ----------------------------
INSERT INTO `wxshop_ad` VALUES ('11', '测试广告图', '/uploads/2018-04-08/5ac9f91fa4bfa.jpg', '测试广告图', '1', '1', '1523185952');

-- ----------------------------
-- Table structure for wxshop_address
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_address`;
CREATE TABLE `wxshop_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '用户id',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '手机',
  `ordername` varchar(255) NOT NULL DEFAULT '' COMMENT '订购人',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '市',
  `area` varchar(255) DEFAULT '' COMMENT '县',
  `detailaddress` varchar(255) NOT NULL COMMENT '详细地址',
  `street` varchar(255) DEFAULT '' COMMENT '街道',
  `isdefault` int(11) DEFAULT '0' COMMENT '0普通 1默认',
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`member_id`),
  KEY `userid` (`member_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员地址表';

-- ----------------------------
-- Records of wxshop_address
-- ----------------------------

-- ----------------------------
-- Table structure for wxshop_ad_type
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_ad_type`;
CREATE TABLE `wxshop_ad_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1-启用 0 冻结',
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='广告类型表';

-- ----------------------------
-- Records of wxshop_ad_type
-- ----------------------------
INSERT INTO `wxshop_ad_type` VALUES ('11', '阿萨德飞洒', '1', '1', '1523185980');
INSERT INTO `wxshop_ad_type` VALUES ('12', '首页banner图', '11', '1', '1523186031');

-- ----------------------------
-- Table structure for wxshop_goods
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_goods`;
CREATE TABLE `wxshop_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) DEFAULT '1' COMMENT '店铺ID',
  `good_type_id` int(11) DEFAULT NULL COMMENT '商品类型ID',
  `goods_name` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '商品名称',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `thumbs` text,
  `price` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '前台显示价格',
  `addtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(11) DEFAULT NULL,
  `status` tinyint(11) DEFAULT NULL,
  `description` text CHARACTER SET utf8 COMMENT '描述',
  `label_id` int(11) NOT NULL COMMENT '标签id',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of wxshop_goods
-- ----------------------------
INSERT INTO `wxshop_goods` VALUES ('35', '1', '9', '产品1', '/uploads/2018-04-03/5ac3451cb0c20.jpg', '|/uploads/2018-04-03/5ac343bb33838.jpg|/uploads/2018-04-03/5ac343bbda430.jpg|/uploads/2018-04-03/5ac3472bcf468.jpg', '198￥~398￥', '1522723842', null, '1', '<p>啊啊啊啊啊啊啊啊啊啊啊111111</p>', '11', '1');
INSERT INTO `wxshop_goods` VALUES ('36', '1', '9', '测试', '/uploads/2018-04-03/5ac3472bcf468.jpg', '|/uploads/2018-04-08/5ac9ad19108e2.jpg|/uploads/2018-04-08/5ac9ad19181fa.jpg|/uploads/2018-04-08/5ac9ad19206ca.jpg', '11-23', '1523166498', null, '1', '<p>阿萨德飞洒</p>', '10', '1');

-- ----------------------------
-- Table structure for wxshop_goodslabel
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_goodslabel`;
CREATE TABLE `wxshop_goodslabel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `pic1` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '小图片链接地址',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1-启用 0 冻结',
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='产品标签表';

-- ----------------------------
-- Records of wxshop_goodslabel
-- ----------------------------
INSERT INTO `wxshop_goodslabel` VALUES ('10', '热门', '1', '/uploads/2018-04-03/5ac2eaeea2d78.jpg', '1', '1522723569');
INSERT INTO `wxshop_goodslabel` VALUES ('11', '推荐', '2', '/uploads/2018-04-03/5ac2eb0a81650.jpg', '1', '1522723598');

-- ----------------------------
-- Table structure for wxshop_goodssize
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_goodssize`;
CREATE TABLE `wxshop_goodssize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sizename` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `price` int(10) DEFAULT NULL,
  `kucun` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of wxshop_goodssize
-- ----------------------------
INSERT INTO `wxshop_goodssize` VALUES ('1', '卡其色-无害', '19', '1000', '1522750921', '35');
INSERT INTO `wxshop_goodssize` VALUES ('2', '红色-无害', '398', '1000', '1522750921', '35');
INSERT INTO `wxshop_goodssize` VALUES ('17', '1111', '1111', '11', '1522750921', '35');
INSERT INTO `wxshop_goodssize` VALUES ('18', 'sada', '11', '23131', '1522750921', '35');
INSERT INTO `wxshop_goodssize` VALUES ('19', '11', '11', '11', '1523166511', '36');
INSERT INTO `wxshop_goodssize` VALUES ('20', '1111', '0', '0', '1523166511', '36');

-- ----------------------------
-- Table structure for wxshop_goodstype
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_goodstype`;
CREATE TABLE `wxshop_goodstype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `pic1` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '小图片链接地址',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1-启用 0 冻结',
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='产品标签表';

-- ----------------------------
-- Records of wxshop_goodstype
-- ----------------------------
INSERT INTO `wxshop_goodstype` VALUES ('9', '测试栏目1', '1', '/uploads/2018-04-03/5ac2eac75d048.jpg', '1', '1522723531');
INSERT INTO `wxshop_goodstype` VALUES ('10', '测试栏目2', '2', '/uploads/2018-04-03/5ac2ead608ca0.jpg', '1', '1522723544');

-- ----------------------------
-- Table structure for wxshop_member
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_member`;
CREATE TABLE `wxshop_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(128) DEFAULT NULL COMMENT '小程序openId',
  `nickname` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '昵称',
  `phone` varchar(128) DEFAULT '' COMMENT '注册手机号码',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '会员状态：1-正常，0-冻结',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间（数据库自动操作，不必写入）',
  `updatetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间（数据库自动操作，不必写入）',
  `sharecode` varchar(100) NOT NULL DEFAULT '' COMMENT '个人code',
  `subscribe` int(11) DEFAULT '0' COMMENT '0为关注 1关注',
  `top_userid` int(11) DEFAULT NULL COMMENT '一级UserID',
  `sub_userid` int(11) DEFAULT NULL COMMENT '二级UserID',
  `tagids` varchar(50) DEFAULT '' COMMENT '标签id(多个逗号隔开)：，XX,XX,',
  `shop_id` int(11) DEFAULT '0' COMMENT '所属店铺',
  `agent_id` int(11) DEFAULT NULL COMMENT '上家代理商ID',
  `distributorid` int(11) DEFAULT NULL COMMENT '上家分销商ID',
  `addtype` int(11) DEFAULT NULL COMMENT '会员添加类型 0无上家 1会员 2分销商 3代理商',
  `token` varchar(128) NOT NULL DEFAULT '' COMMENT 'token',
  `tokentime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'token过期时间',
  `cost` double(9,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `point` double(9,2) DEFAULT '0.00' COMMENT '积分',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sharecode` (`sharecode`) USING BTREE,
  UNIQUE KEY `token` (`token`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='微信用户表';

-- ----------------------------
-- Records of wxshop_member
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
  `iconclass` varchar(255) DEFAULT NULL,
  `ismenu` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wxshop_node
-- ----------------------------
INSERT INTO `wxshop_node` VALUES ('1', '/', '权限管理', '0', '权限管理', '1', null, '0', '0', '0', '0', '0', ' anticon-dashboard', '1');
INSERT INTO `wxshop_node` VALUES ('2', '/Permission/roleList', '角色列表', '权限管理', '角色列表', '1', null, '0', '1', '1', '0', '0', 'anticon-github', '1');
INSERT INTO `wxshop_node` VALUES ('3', '/Permission/roleAdd', '角色添加', '权限管理', '角色添加', '1', null, '1', '1', '1', '0', '0', null, '0');
INSERT INTO `wxshop_node` VALUES ('4', '/Permission/roleEdit', '角色编辑', '权限管理', '角色编辑', '1', null, '2', '1', '1', '0', '0', null, '0');
INSERT INTO `wxshop_node` VALUES ('5', '/Permission/roleDelete', '角色删除', '权限管理', '角色删除', '1', null, '3', '1', '1', '0', '0', null, '0');
INSERT INTO `wxshop_node` VALUES ('7', '/Permission/nodeList', '节点列表', '权限管理', '节点列表', '1', null, '4', '1', '1', '0', '0', 'anticon-share-alt', '1');
INSERT INTO `wxshop_node` VALUES ('8', '/Permission/addNode', '节点添加', '权限管理', '节点添加', '1', null, '5', '1', '1', '0', '0', null, '0');
INSERT INTO `wxshop_node` VALUES ('9', '/Permission/updateNode', '节点修改', '权限管理', '节点修改', '1', null, '6', '1', '1', '0', '0', null, '0');
INSERT INTO `wxshop_node` VALUES ('10', '/Permission/deleteNode', '节点删除', '权限管理', '节点修改', '1', null, '7', '1', '1', '0', '0', null, '0');
INSERT INTO `wxshop_node` VALUES ('11', '/Permission/memberList', '用户列表', '权限管理', '用户列表', '1', null, '8', '1', '1', '0', '0', 'anticon-user', '1');
INSERT INTO `wxshop_node` VALUES ('12', '/Permission/memberAdd', '用户添加', '权限管理', '用户添加', '1', null, '9', '1', '1', '0', '0', null, '0');
INSERT INTO `wxshop_node` VALUES ('13', '/Permission/memberEdit', '用户修改', '权限管理', '用户修改', '1', null, '10', '1', '1', '0', '0', null, '0');
INSERT INTO `wxshop_node` VALUES ('15', '/Permission/memberDelete', '用户删除', '权限管理', '用户删除', '1', null, '11', '1', '1', '0', '0', null, '0');
INSERT INTO `wxshop_node` VALUES ('17', '/Permission/addAccess', '分配权限', '权限管理', '分配权限', '1', null, '12', '1', '1', '0', '0', null, '0');
INSERT INTO `wxshop_node` VALUES ('18', '/Permission/saveAccess', '分配权限', '权限管理', '分配权限', '1', null, '12', '1', '1', '0', '0', null, '0');
INSERT INTO `wxshop_node` VALUES ('19', '/', '充值管理', '0', '充值管理', '1', null, '1', '0', '0', '0', '0', 'anticon-pay-circle-o', '1');
INSERT INTO `wxshop_node` VALUES ('20', '/', '商品管理', '0', '商品管理', '1', null, '2', '0', '0', '0', '0', 'anticon-shop', '1');
INSERT INTO `wxshop_node` VALUES ('33', '//', '系统管理', '0', '系统管理', '1', null, '3', '0', '0', '0', '0', 'anticon-windows', '1');
INSERT INTO `wxshop_node` VALUES ('21', '/Goods/index', '物品列表', '商品管理', '物品列表', '1', null, '1', '20', '1', '0', '0', 'anticon-database', '1');
INSERT INTO `wxshop_node` VALUES ('22', '/Goods/goodadd', '物品添加', '物品列表', '物品添加', '1', null, '1', '20', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('23', '/Goods/goodedit', '物品编辑', '商品管理', '物品编辑', '1', null, '2', '20', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('24', '/Goods/gooddelete', '产品删除', '商品管理', '产品删除', '1', null, '3', '20', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('25', '/Goods/goods_type_list', '产品类型列表管理', '商品管理', '产品类型列表管理', '1', null, '5', '20', '1', '0', '0', 'anticon-api', '1');
INSERT INTO `wxshop_node` VALUES ('26', '/Goods/goods_type_add', '产品类型添加', '商品管理', '产品类型添加', '1', null, '6', '20', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('27', '/Goods/goods_type_edit', '产品类型编辑', '商品管理', '产品类型编辑', '1', null, '7', '20', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('28', '/Goods/goods_type_delete', '产品类型删除', '商品管理', '产品类型删除', '1', null, '8', '20', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('29', '/Goods/goods_label_list', '产品标签列表', '商品管理', '产品标签列表', '1', null, '9', '20', '1', '0', '0', 'anticon-tag', '1');
INSERT INTO `wxshop_node` VALUES ('30', '/Goods/goods_label_add', '产品标签增加', '商品管理', '产品标签增加', '1', null, '10', '20', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('31', '/Goods/goods_label_edit', '产品标签编辑', '商品管理', '产品标签编辑', '1', null, '11', '20', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('32', '/Goods/goods_label_delete', '产品标签删除', '商品管理', '产品标签删除', '1', null, '12', '20', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('34', '/System/ad_type_list', '广告类型管理', '系统管理', '广告类型管理', '1', null, '1', '33', '1', '0', '0', 'anticon-flag', '1');
INSERT INTO `wxshop_node` VALUES ('35', '/System/ad_type_add', '广告类型添加', '系统管理', '广告类型添加', '1', null, '2', '33', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('36', '/System/ad_type_edit', '广告类型编辑', '系统管理', '广告类型编辑', '1', null, '2', '33', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('37', '/System/ad_type_delete', '广告类型删除', '系统管理', '广告类型删除', '1', null, '3', '33', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('38', '/System/ad_list', '广告管理', '系统管理', '广告管理', '1', null, '1', '33', '1', '0', '0', 'anticon-disconnect', '1');
INSERT INTO `wxshop_node` VALUES ('39', '/System/ad_add', '广告添加', '系统管理', '广告添加', '1', null, '2', '33', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('40', '/System/ad_edit', '广告编辑', '系统管理', '广告编辑', '1', null, '2', '33', '1', '0', '0', '1', '0');
INSERT INTO `wxshop_node` VALUES ('41', '/System/ad_delete', '广告删除', '系统管理', '广告删除', '1', null, '3', '33', '1', '0', '0', '1', '0');

-- ----------------------------
-- Table structure for wxshop_rent_good
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_rent_good`;
CREATE TABLE `wxshop_rent_good` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `goodid` int(11) DEFAULT NULL,
  `goodsizeid` int(11) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  KEY `id` (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wxshop_rent_good
-- ----------------------------

-- ----------------------------
-- Table structure for wxshop_role
-- ----------------------------
DROP TABLE IF EXISTS `wxshop_role`;
CREATE TABLE `wxshop_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` int(11) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `pid` (`pid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wxshop_role
-- ----------------------------
INSERT INTO `wxshop_role` VALUES ('1', '超级管理员', null, '1', null, '1483429525', '1483429525');
INSERT INTO `wxshop_role` VALUES ('5', '运营管理', null, '1', null, '1520586828', '1520586828');
INSERT INTO `wxshop_role` VALUES ('6', '推广人员', null, '1', null, '1520586837', '1520586837');

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
INSERT INTO `wxshop_role_user` VALUES ('5', '79');
INSERT INTO `wxshop_role_user` VALUES ('5', '80');
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
INSERT INTO `wxshop_user` VALUES ('68', 'admin', '管理员', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '', '1523152896', '127.0.0.1', '155', null, '', '', '1467963560', '1467963560', '1', '0', '/portrait/57be642fb50eb.png');
INSERT INTO `wxshop_user` VALUES ('80', 'test', '123456', '测试1', 'e10adc3949ba59abbe56e057f20f883e', '', '1520995646', null, '20', null, '测试1@7477.com', '', '1520579106', '1520579106', '1', '0', '');
