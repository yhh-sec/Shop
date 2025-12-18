/*
MySQL Backup
Database: shop_db
Backup Time: 2025-12-15 16:32:26
*/

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `shop_db`.`goods`;
DROP TABLE IF EXISTS `shop_db`.`user`;
CREATE TABLE `goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(100) NOT NULL,
  `goods_price` decimal(10,2) NOT NULL,
  `goods_desc` text,
  `goods_img` varchar(255) DEFAULT NULL,
  `seller_id` int(11) NOT NULL,
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `seller_id` (`seller_id`),
  CONSTRAINT `goods_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT '3',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
BEGIN;
LOCK TABLES `shop_db`.`goods` WRITE;
DELETE FROM `shop_db`.`goods`;
INSERT INTO `shop_db`.`goods` (`id`,`goods_name`,`goods_price`,`goods_desc`,`goods_img`,`seller_id`,`create_time`) VALUES (2, '安卓系统🔥王者直装【冬瓜】🔥🕳️🍉直装🔥 🔥支持s41新赛季🔥', 999.00, '🔥王者直装冬瓜🔥🕳️🍉直装🔥\r\n\r\n🔥支持s41新赛季🔥\r\n\r\n🔥行为7🔥\r\n\r\n🔥支持安卓全部系统，不卡悬浮🔥\r\n\r\n🔥拯救直装市场，稳定奔放同号连打🔥\r\n\r\n🔥上分首选直装🔥\r\n\r\n🔥无敌稳定🔥 \r\n\r\n🔥连续稳定几个赛季🔥\r\n\r\n🔥同号连打，一直连打，一直爽🔥\r\n\r\n🔥稳定不稳定，看每天实时转图🔥\r\n\r\n🔥真实用户反馈\r\n\r\n🔥注意看战绩图时间即可分辨🔥', 'static/upload/693faa7fde0b2_1765780095.jpg', 2, '2025-12-15 14:26:53'),(3, '安卓系统内核【ADC】PUBG 4.1适配安卓16 适配6.6内核☘️', 999.00, '🐖ADC  PUBG 4.1\r\n\r\n适配安卓16  适配6.6内核☘️\r\n\r\n《注意盗版》\r\n\r\n☘️物资全部更新完成\r\n\r\n\r\n\r\n细节功能:\r\n\r\n\r\n\r\n🌹 🌿智能软锁开关，主播亲自调试！\r\n\r\n\r\n\r\n🌹 🌿【骨骼☞方框】🌿做了微透明处理 完美做到不挡视野\r\n\r\n🌹功能:支持观战透视\r\n\r\n🌹  🌿手雷爆炸倒计时(爆炸及消失，没有残影)🌿\r\n\r\n🌹功能:所有绘图颜色 自定义可调，物资-队编-距离\r\n\r\n\r\n\r\n骨骼-方框～\r\n\r\n\r\n\r\n🌹功能:腰射距离、开镜距离单独调节！不会出现腰射锁远距离的尴尬情况，也可实现腰射不锁 开镜锁《近战开镜可打提前枪》！\r\n\r\n\r\n\r\n🌹功能:智能动态自瞄圈、每30米自动调节圈大小\r\n\r\n\r\n\r\n🌹功能:自动识别步枪开火锁～狙击枪开镜锁\r\n\r\n\r\n\r\n🌹功能:腰射喷子锁、持续锁定☞开火锁定～距离可调节\r\n\r\n\r\n\r\n🌹功能:自动识别每一把枪压枪值，预判都调试 适配完毕\r\n\r\n\r\n\r\n🌹功能:自瞄300米站蹲趴同步一个点自动修正扫车打鸟究极预判无视高低坡。\r\n\r\n\r\n\r\n🌹功能:掉血自瞄，第一发子弹打中敌人触发自瞄（实战有效）。\r\n\r\n\r\n\r\n🌹功能:框内自瞄，准心在方框内大约3个身位调用自瞄\r\n\r\n\r\n\r\n纯内核 支持外设gt2 -gt3-灯神- 魔蝎2 支持改分辨率投屏 全源自研开发！\r\n\r\n\r\n\r\n触摸～自瞄/！指哪打哪该有的功能都有\r\n\r\n', 'static/upload/693faaf534a0d_1765780213.jpg', 2, '2025-12-15 14:30:14'),(4, '电脑系统三角洲【KT】本软件不支持W11/24H2系统-支持国服-steam国服-steam国际服-台服-过网吧玄磐石-自瞄模式推荐使用DAM自瞄模式-无需盒子', 999.00, '', 'static/upload/693fab3100be0_1765780273.jpg', 2, '2025-12-15 14:31:14'),(5, '安卓系统内核无畏契约手游内核【kkbb】', 666.00, '无畏契约手游内核【kkbb】\r\n\r\n🔥优选好品\r\n\r\n🔥支持安卓全系统\r\n\r\n🔥无杂乱问题 简单使用\r\n\r\n❤️流畅绘制/骨骼/射线/血量/背敌/方框🔥实时战绩转图反馈 稳定max', 'static/upload/693fb92d3b237_1765783853.jpg', 2, '2025-12-15 15:30:54'),(6, '苹果系统CFM【战场HUD】已更新至最新版本1.0.500支持iOS全系统', 123.00, '🔈iOS苹果CFM【战场HUD】已更新至最新版本1.0.500支持iOS全系统\r\n\r\n解决拉闸问题,更新防封优化稳定性\r\n\r\n战场HUD更新方式,网盘安装好app以后点击初始化一次\r\n\r\n巨魔支持14-17系统一键安装\r\n\r\n演戏非常稳定稳定连打\r\n\r\n裸奔内置防封,无需任何端口\r\n\r\n全系统版本支持任意签名安装', 'static/upload/693fba0cc52c6_1765784076.jpg', 2, '2025-12-15 15:34:37'),(7, '苹果系统 ​iOS三角洲【黑鹰】最新独家引擎多线程绘制', 777.00, 'iOS三角洲【黑鹰】最新独家引擎多线程绘制，解决卡顿发热问题，支持绘制样式自定义，引擎绘制，极致流畅，人机识别，漏打自瞄，掩体判断，无后聚点，子弹追踪，物资绘制，独家裸奔过检测，无需配备端口奔放得吃', 'static/upload/693fbaf8ab910_1765784312.jpg,static/upload/693fbaf8acfec_1765784312.jpg', 2, '2025-12-15 15:38:34');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `shop_db`.`user` WRITE;
DELETE FROM `shop_db`.`user`;
INSERT INTO `shop_db`.`user` (`id`,`username`,`password`,`role`,`create_time`) VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, '2025-12-15 13:19:34'),(2, 'seller', 'c4ca4238a0b923820dcc509a6f75849b', 2, '2025-12-15 13:19:34'),(3, 'yhh', 'e10adc3949ba59abbe56e057f20f883e', 3, '2025-12-15 15:27:48');
UNLOCK TABLES;
COMMIT;
