/*
Navicat MySQL Data Transfer

Source Server         : LOCAL
Source Server Version : 50720
Source Host           : localhost:3306
Source Database       : project_shopcuatui2018

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2018-09-24 22:24:12
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
  `meta_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_keywords` text COLLATE utf8_unicode_ci,
  `meta_description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`category_id`),
  KEY `status` (`category_status`) USING BTREE,
  KEY `id_parrent` (`category_parent_id`,`category_status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of shop_category
-- ----------------------------
INSERT INTO `shop_category` VALUES ('10', null, 'Mỹ phẩm - Dưỡng da - Tóc (Hàn)', '2', '0', '5', '1', null, null, '0', '1', '1', '2', null, null, null);
INSERT INTO `shop_category` VALUES ('15', null, 'Mẹ và Bé (Nhật)', '1', '0', '5', '1', null, null, '1', '1', '1', '3', null, null, null);
INSERT INTO `shop_category` VALUES ('16', null, 'Mỹ phẩm (Nhật)', '1', '0', '5', '1', null, null, '1', '1', '1', '2', null, null, null);
INSERT INTO `shop_category` VALUES ('17', null, 'MỸ PHẨM - LÀM ĐẸP', '0', '0', '5', '1', null, null, '1', '1', '1', '3', null, null, null);
INSERT INTO `shop_category` VALUES ('18', null, 'Chất tẩy rửa - Tạp hóa ', '24', '0', '5', '1', null, null, '1', '1', '1', '5', null, null, null);
INSERT INTO `shop_category` VALUES ('21', null, 'Mỹ phẩm ', '17', '0', '5', '1', null, null, '1', '1', '1', '2', null, null, null);
INSERT INTO `shop_category` VALUES ('22', null, 'Đồ trang điểm', '17', '0', '5', '1', null, null, '1', '1', '1', '1', null, null, null);
INSERT INTO `shop_category` VALUES ('23', null, 'Thuốc - Sức khỏe (Nhật)', '1', '0', '5', '1', null, null, '1', '1', '1', '1', null, null, null);
INSERT INTO `shop_category` VALUES ('24', null, 'ĐỒ ĐIỆN - ĐỒ GIA DỤNG', '0', '0', '5', '1', null, null, '1', '1', '1', '2', null, null, null);
INSERT INTO `shop_category` VALUES ('26', null, 'Dành cho mẹ', '30', '0', '5', '1', null, null, '1', '1', '1', '3', null, null, null);
INSERT INTO `shop_category` VALUES ('30', null, 'MẸ VÀ BÉ', '0', '0', '5', '1', null, null, '1', '1', '1', '8', null, null, null);
INSERT INTO `shop_category` VALUES ('31', null, 'Dành cho bé', '30', '0', '5', '1', null, null, '1', '1', '1', '1', null, null, null);
INSERT INTO `shop_category` VALUES ('32', null, 'Bếp xinh - Dụng cụ Nhà bếp', '24', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('33', null, 'Đồ Gia dụng - Nhà Xinh', '24', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('34', null, 'Son ', '17', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('35', null, 'Sữa và bột dinh dưỡng', '30', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('36', null, 'Đồ văn phòng', '24', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('37', null, 'Thời trang, mỹ phẩm', '0', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('38', null, 'Túi sách', '37', '0', '5', '1', null, null, '1', '1', '1', '2', null, null, null);
INSERT INTO `shop_category` VALUES ('39', null, 'THUỐC & SỨC KHOẺ', '0', '0', '5', '1', null, null, '1', '1', '1', '1', null, null, null);
INSERT INTO `shop_category` VALUES ('40', null, 'Quần áo nam', '37', '0', '5', '1', null, null, '1', '1', '1', '1', null, null, null);
INSERT INTO `shop_category` VALUES ('41', null, 'Dành cho nam giới', '39', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('42', null, 'Dành cho nữ giới', '39', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('43', null, 'Dành cho trẻ em', '39', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('44', null, 'Thực phẩm chức năng', '39', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('45', null, 'Thiết bị y tế - hỗ trợ sức khoẻ', '39', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('46', null, 'CẢM ƠN KHÁCH HÀNG TIN TƯỞNG!', '0', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('47', null, 'HÀNG ÚC ĐÃ VỀ', '46', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('48', null, 'HÀNG ĐỨC ĐÃ VỀ', '46', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('49', null, 'HÀNG NGA ĐÃ VỀ', '46', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('50', null, 'BÁNH - KẸO - HẠT KHÔ - RƯỢU', '0', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('51', null, 'Bánh kẹo, hạt khô, rượu từ ÚC', '50', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('52', null, 'Bánh kẹo, hạt khô, rượu từ Đức', '50', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('53', null, 'Bánh kẹo, hạt khô, rượu từ Nga', '50', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('54', null, 'Nước hoa - chất tạo mùi', '17', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
INSERT INTO `shop_category` VALUES ('56', null, 'Bánh kẹo, hạt khô, rượu từ Mỹ', '50', '0', '5', '1', null, null, '1', '1', '1', '0', null, null, null);
