/*
Navicat MySQL Data Transfer

Source Server         : LOCAL
Source Server Version : 50720
Source Host           : localhost:3306
Source Database       : project_shopcuatui2018

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2018-09-23 09:45:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for shop_infor_sale
-- ----------------------------
DROP TABLE IF EXISTS `shop_infor_sale`;
CREATE TABLE `shop_infor_sale` (
  `infor_sale_id` int(12) NOT NULL AUTO_INCREMENT,
  `member_id` int(12) DEFAULT NULL,
  `infor_sale_uid` int(12) DEFAULT NULL,
  `infor_sale_name` varchar(255) DEFAULT NULL,
  `infor_sale_phone` varchar(255) DEFAULT NULL,
  `infor_sale_mail` varchar(255) DEFAULT NULL,
  `infor_sale_skype` varchar(255) DEFAULT NULL,
  `infor_sale_address` varchar(255) DEFAULT NULL,
  `infor_sale_sotaikhoan` text,
  `infor_sale_vanchuyen` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`infor_sale_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_infor_sale
-- ----------------------------
INSERT INTO `shop_infor_sale` VALUES ('2', null, '23', 'Shopcuatui.com.vn', ' 0985.10.10.26 (Ms. Giang) -  0903.187.988 (Ms. Bình) - 0945.389.542 (Ms. Hiền)', 'shoponlinecuatui@gmail.com', 'nguyenduypt86', 'Việt Hưng - Long Biên - Hà Nội  - Đại lý: Toà nhà Hoà Bình Green House, 505 Minh Khai, HBT, Hà Nội', '<p><span style=\\\"font-size:18px\\\"><strong>1</strong></span>. T&agrave;i khoản <span style=\\\"color:#0000FF\\\"><span style=\\\"font-size:18px\\\"><strong>Vietcombank</strong></span></span> Số TK:<span style=\\\"color:#FF0000\\\"><strong><span style=\\\"font-size:18px\\\"> 0301000335819</span></strong></span></p>\r\n\r\n<p>Ng&acirc;n h&agrave;ng TMCP Ngoại Thương Việt Nam Vietcombank, chi nh&aacute;nh Chương Dương,H&agrave; Nội.</p>\r\n\r\n<p>Chủ t&agrave;i khoản: <strong>Trương Thị Hương Giang</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><span style=\\\"font-size:18px\\\"><strong>2</strong></span>. T&agrave;i khoản <span style=\\\"color:#0000FF\\\"><span style=\\\"font-size:18px\\\"><strong>Techcombank </strong></span></span>Số TK: <span style=\\\"font-size:18px\\\"><span style=\\\"color:rgb(255, 0, 0)\\\"><strong>10824190363016</strong></span></span></p>\r\n\r\n<p>Ng&acirc;n h&agrave;ng Techcombank chi nh&aacute;nh L&yacute; Thường Kiệt, H&agrave; Nội.</p>\r\n\r\n<p>Chủ t&agrave;i khoản: <strong>Trương Thị Hương Giang</strong></p>\r\n', '<p><strong>NHẬN H&Agrave;NG</strong></p>\r\n\r\n<p>Sau 2-4 ng&agrave;y bạn đặt h&agrave;ng, sản phẩm bạn mua sẽ được giao tận tay bạn ở nh&agrave; hoặc bất cứ địa điểm n&agrave;o bạn muốn trong giờ h&agrave;nh ch&iacute;nh (Từ 8h s&aacute;ng đến 17h chiều).</p>\r\n\r\n<p><strong>THANH TO&Aacute;N</strong><br />\r\n<strong>1.</strong> Thanh to&aacute;n bằng h&igrave;nh thức chuyển khoản.<br />\r\n<strong>2.</strong> Thanh to&aacute;n tại nh&agrave; (Khi nhận được h&agrave;ng bạn chỉ cần gửi tiền cho người giao h&agrave;ng vừa tiết kiệm thời gian vừa an to&agrave;n)<br />\r\n+ Địa chỉ: Số nh&agrave; 37 - tổ 6- Lệ Mật - Việt Hưng - Long Bi&ecirc;n - H&agrave; Nội<br />\r\n+ Li&ecirc;n hệ: <span style=\\\"font-size:18px\\\"><span style=\\\"color:#FF0000\\\"><strong>0985.10.10.26 -&nbsp; 0903.187.988&nbsp;</strong></span></span></p>\r\n\r\n<p><strong>PH&Iacute; VẬN CHUYỂN:</strong><br />\r\n<strong>1.</strong> Nội th&agrave;nh H&agrave; Nội: 20.000 đ<br />\r\n<strong>2. </strong>Ngoại th&agrave;nh v&agrave; C&aacute;c tỉnh kh&aacute;c :&nbsp; 30.000 đ.</p>\r\n', null, null);
