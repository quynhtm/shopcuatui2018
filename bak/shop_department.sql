/*
Navicat MySQL Data Transfer

Source Server         : LOCAL
Source Server Version : 50720
Source Host           : localhost:3306
Source Database       : project_shopcuatui2018

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2018-09-23 09:45:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for shop_department
-- ----------------------------
DROP TABLE IF EXISTS `shop_department`;
CREATE TABLE `shop_department` (
  `department_id` int(10) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `department_alias` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `department_order` tinyint(5) DEFAULT '0',
  `department_status` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`department_id`),
  KEY `status` (`department_status`) USING BTREE,
  KEY `id_parrent` (`department_status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of shop_department
-- ----------------------------
INSERT INTO `shop_department` VALUES ('8', 'Hàng xách tay Mỹ', 'Hang-xach-tay-My', '4', '1', '2018-09-22 16:35:57', '2018-09-22 16:45:38');
INSERT INTO `shop_department` VALUES ('10', 'Hàng xách tay Úc', 'Hang-xach-tay-Uc', '3', '1', '2018-09-22 17:06:03', '2018-09-22 17:06:11');
