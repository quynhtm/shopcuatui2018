/*
Navicat MySQL Data Transfer

Source Server         : LOCAL
Source Server Version : 50720
Source Host           : localhost:3306
Source Database       : project_shopcuatui2018

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2018-09-25 08:43:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for shop_category
-- ----------------------------
DROP TABLE IF EXISTS `shop_category`;
CREATE TABLE `shop_category` (
  `category_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `member_id` int(12) DEFAULT NULL,
  `category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category_parent_id` int(2) unsigned NOT NULL DEFAULT '0',
  `category_depart_id` int(12) DEFAULT NULL,
  `category_type` int(2) DEFAULT '0' COMMENT 'loại danh mục',
  `category_level` int(2) DEFAULT '1' COMMENT 'cấp danh mục',
  `category_image_background` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_icons` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_status` int(2) DEFAULT '0',
  `category_menu_status` int(2) DEFAULT '0',
  `category_menu_right` int(2) DEFAULT NULL,
  `category_order` int(2) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_keywords` text COLLATE utf8_unicode_ci,
  `meta_description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`category_id`),
  KEY `status` (`category_status`) USING BTREE,
  KEY `id_parrent` (`category_parent_id`,`category_status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of shop_category
-- ----------------------------
INSERT INTO `shop_category` VALUES ('10', '0', 'Mỹ phẩm - Dưỡng da - Tóc (Hàn)111111', '37', '0', '5', '1', null, null, '1', '1', '1', '2111111', null, '2018-09-25 01:37:18', '1', '1                                                                                                                    ', '1                                                                                      ');
INSERT INTO `shop_category` VALUES ('15', '0', 'Mẹ và Bé (Nhật)', '1', '0', '5', '1', null, null, '1', '1', '1', '3', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('16', '0', 'Mỹ phẩm (Nhật)', '1', '0', '5', '1', null, null, '1', '1', '1', '2', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('17', '0', 'MỸ PHẨM - LÀM ĐẸP', '0', '0', '5', '1', null, null, '1', '1', '1', '3', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('18', '0', 'Chất tẩy rửa - Tạp hóa ', '24', '0', '5', '1', null, null, '1', '1', '1', '5', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('21', '0', 'Mỹ phẩm ', '17', '0', '5', '1', null, null, '1', '1', '1', '2', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('22', '0', 'Đồ trang điểm', '17', '0', '5', '1', null, null, '1', '1', '1', '1', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('23', '0', 'Thuốc - Sức khỏe (Nhật)', '1', '0', '5', '1', null, null, '1', '1', '1', '1', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('24', '0', 'ĐỒ ĐIỆN - ĐỒ GIA DỤNG', '0', '0', '5', '1', null, null, '1', '1', '1', '2', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('26', '0', 'Dành cho mẹ', '30', '0', '5', '1', null, null, '1', '1', '1', '3', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('30', '0', 'MẸ VÀ BÉ', '0', '0', '5', '1', null, null, '1', '1', '1', '8', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('31', '0', 'Dành cho bé', '30', '0', '5', '1', null, null, '1', '1', '1', '1', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('32', '0', 'Bếp xinh - Dụng cụ Nhà bếp', '24', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('33', '0', 'Đồ Gia dụng - Nhà Xinh', '24', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('34', '0', 'Son ', '17', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('35', '0', 'Sữa và bột dinh dưỡng', '30', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('36', '0', 'Đồ văn phòng', '24', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('37', '0', 'Thời trang, mỹ phẩm', '0', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('38', '0', 'Túi sách', '37', '0', '5', '1', null, null, '1', '1', '1', '2', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('39', '0', 'THUỐC & SỨC KHOẺ', '0', '0', '5', '1', null, null, '1', '1', '1', '1', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('40', '0', 'Quần áo nam', '37', '0', '5', '1', null, null, '1', '1', '1', '1', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('41', '0', 'Dành cho nam giới', '39', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('42', '0', 'Dành cho nữ giới', '39', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('43', '0', 'Dành cho trẻ em', '39', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('44', '0', 'Thực phẩm chức năng', '39', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('45', '0', 'Thiết bị y tế - hỗ trợ sức khoẻ', '39', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('46', '0', 'CẢM ƠN KHÁCH HÀNG TIN TƯỞNG!', '0', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('47', '0', 'HÀNG ÚC ĐÃ VỀ', '46', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('48', '0', 'HÀNG ĐỨC ĐÃ VỀ', '46', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('49', '0', 'HÀNG NGA ĐÃ VỀ', '46', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('50', '0', 'BÁNH - KẸO - HẠT KHÔ - RƯỢU', '0', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('51', '0', 'Bánh kẹo, hạt khô, rượu từ ÚC', '50', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null, null, null);
INSERT INTO `shop_category` VALUES ('52', '0', 'Bánh kẹo, hạt khô, rượu từ Đức1', '37', '0', '5', '1', null, null, '1', '1', '1', '0', null, '2018-09-25 01:42:43', '1', '1      ', '1                           ');
INSERT INTO `shop_category` VALUES ('53', '0', 'Bánh kẹo, hạt khô, rượu từ Nga1', '50', '0', '5', '1', null, null, '1', '1', '1', '0', null, '2018-09-25 01:42:56', '', '                                                            ', '                                                            ');
