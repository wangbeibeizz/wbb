# Host: localhost  (Version: 5.5.53)
# Date: 2018-05-11 21:16:56
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "new_admin"
#

DROP TABLE IF EXISTS `new_admin`;
CREATE TABLE `new_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ad_work_number` varchar(10) DEFAULT NULL COMMENT '工号',
  `ad_account` varchar(255) DEFAULT NULL,
  `ad_tel` char(11) NOT NULL DEFAULT '',
  `ad_real_name` varchar(255) DEFAULT NULL COMMENT '真实姓名',
  `ad_pwd` char(32) NOT NULL,
  `ad_status` tinyint(1) NOT NULL DEFAULT '1',
  `ro_id` int(10) DEFAULT '0' COMMENT '后台用户角色分组id',
  `ad_add_time` datetime DEFAULT NULL COMMENT '入职日期',
  `delete_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='管理员';

#
# Data for table "new_admin"
#

INSERT INTO `new_admin` VALUES (1,'kg10025','1','gly','毛毛','9bKhruGd881c4dOcIKdbtw==',1,1,'0000-00-00 00:00:00',NULL),(15,'kg10026','2','18739912501','555','b70482e5bc2ccd501a5ceff731233136',1,1,'2018-03-19 13:47:28',NULL),(16,'kg10027','3','18739912502','22','b70482e5bc2ccd501a5ceff731233136',1,2,'2018-03-19 13:48:15',NULL),(17,'kg10028','4','13','123','b002f9598fa0a50a6d5c756bd26feb17',1,2,'2018-03-24 13:59:07',NULL),(18,'kg10029','5','123456','asdf','f9f4697ac4c0d800948b6bae10336afb',1,2,'2018-03-24 14:00:33',NULL),(19,'kg10030','6','12345678901','555','9bKhruGd881c4dOcIKdbtw==',1,0,'2018-04-16 15:56:23',NULL),(20,'kg10031','7','187','555','9bKhruGd881c4dOcIKdbtw==',1,1,'2018-04-16 15:56:34',NULL),(21,'kg10032','8','18739912538','555','9bKhruGd881c4dOcIKdbtw==',1,1,'2018-04-16 15:56:40',NULL),(22,'kg10033','9','18739912536','555','9bKhruGd881c4dOcIKdbtw==',1,1,'2018-04-16 16:03:14',NULL),(23,'kg10034','10','18739912537','555','9bKhruGd881c4dOcIKdbtw==',1,1,'2018-04-16 16:03:39',NULL),(24,'kg10035','11','18739912539','555','9bKhruGd881c4dOcIKdbtw==',1,1,'2018-04-16 16:04:53',NULL),(25,'kg10036','12','18739912540','555','9bKhruGd881c4dOcIKdbtw==',1,1,'2018-04-16 16:05:13',NULL),(26,'kg10037','13','18739912541','555','9bKhruGd881c4dOcIKdbtw==',1,1,'2018-04-16 16:05:50',NULL),(27,'kg10038','14','18739912542','555','9bKhruGd881c4dOcIKdbtw==',1,1,'2018-04-16 16:06:57',NULL),(28,'kg10039','15','18739912545','2132','9bKhruGd881c4dOcIKdbtw==',1,1,'2018-04-16 16:07:30',NULL),(29,'kg10040','16','18739912546','2222','i5D4R48c3pT+wMIGRmNBlg==',1,1,'2018-04-16 16:09:31',NULL),(30,'kg10041','17','18739912547','asdf','iapVQ0Vj5TO96MupQCQm/w==',1,1,'2018-04-16 16:29:03',1523868144),(31,'kg10041','18','18739912222','new1','9bKhruGd881c4dOcIKdbtw==',1,0,'2018-04-26 09:26:56',NULL),(32,'kg10042','abc','18739911111','aa','9bKhruGd881c4dOcIKdbtw==',1,1,'2018-04-26 09:44:11',NULL);

#
# Structure for table "new_cate"
#

DROP TABLE IF EXISTS `new_cate`;
CREATE TABLE `new_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `st_id` int(11) DEFAULT NULL COMMENT '门店id',
  `ca_name` varchar(255) DEFAULT NULL COMMENT '分类名称',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `ca_status` int(11) DEFAULT '1' COMMENT '状态0禁用  1开启',
  `ca_add_time` datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='分类表';

#
# Data for table "new_cate"
#

INSERT INTO `new_cate` VALUES (1,2,'maomao',NULL,0,'2018-05-02 17:34:38'),(2,3,'maomao',NULL,1,'2018-05-02 17:34:38'),(3,2,'kkkk',NULL,0,'2018-05-02 17:54:41');

#
# Structure for table "new_coin"
#

DROP TABLE IF EXISTS `new_coin`;
CREATE TABLE `new_coin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `us_id` int(11) DEFAULT NULL COMMENT '用户id',
  `type` int(11) DEFAULT NULL COMMENT '类型',
  `note` varchar(255) DEFAULT NULL COMMENT '说明',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购买购物币记录';

#
# Data for table "new_coin"
#


#
# Structure for table "new_config"
#

DROP TABLE IF EXISTS `new_config`;
CREATE TABLE `new_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` text,
  `note` varchar(255) DEFAULT NULL COMMENT '说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='系统表';

#
# Data for table "new_config"
#

INSERT INTO `new_config` VALUES (1,'status','1','网站状态'),(2,'yuan_coin','3','购物币/人民币'),(3,'yong_ding','500','每日佣金封顶'),(4,'tixian_day','3\t','每周提现的日期 1234567'),(5,'direct_profit','10','动态直推佣金奖励%'),(6,'label','a:3:{i:1;a:2:{s:4:\"name\";s:6:\"超市\";s:3:\"pic\";s:54:\"/uploads/20180508\\641dd215aea7cf91f0670323e21f7e08.jpg\";}i:2;a:2:{s:4:\"name\";s:6:\"美食\";s:3:\"pic\";s:54:\"/uploads/20180508\\7d25c590d0474a9ed291adb31d85a60a.jpg\";}i:3;a:2:{s:4:\"name\";s:3:\"们\";s:3:\"pic\";s:54:\"/uploads/20180508\\fc30273afecc1b9d25ba85eebb9053e2.jpg\";}}','门店标签列表'),(7,'erdai_profit','10','动态二代佣金奖励%'),(8,'shuffling_figure','/uploads/20180508\\2d53a1db494464b06b1662a7200013e9.jpg,/uploads/20180508\\103a40b2fbb8013b857fa398b4972bed.jpg,/uploads/20180508\\e8352dfa4d1e078621dec1ffa8175cb6.jpg,/uploads/20180508\\ce422c1894a7c0997d7cbccb43e2e1f9.jpg,/uploads/20180508\\f91aee4cda3fd759ed8bdb5e4d1ac968.jpg','轮播图');

#
# Structure for table "new_courier"
#

DROP TABLE IF EXISTS `new_courier`;
CREATE TABLE `new_courier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `co_number` varchar(255) DEFAULT NULL COMMENT '配送员编号',
  `co_name` varchar(255) DEFAULT NULL COMMENT '配送员名称',
  `co_add_time` datetime DEFAULT NULL COMMENT '添加时间',
  `co_tel` varchar(12) DEFAULT NULL COMMENT '手机号',
  `co_status` int(11) DEFAULT '1' COMMENT '状态、',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `co_number` (`co_number`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='配送员表';

#
# Data for table "new_courier"
#

INSERT INTO `new_courier` VALUES (1,'psy10001','123','2018-05-04 14:35:01','456',1,NULL),(2,'psy10002','史经理','2018-05-04 14:35:31','18739912538',1,NULL);

#
# Structure for table "new_order"
#

DROP TABLE IF EXISTS `new_order`;
CREATE TABLE `new_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单表',
  `or_number` varchar(11) DEFAULT NULL COMMENT '订单号',
  `or_total` decimal(10,2) DEFAULT '0.00' COMMENT '总价值',
  `delete_time` int(11) DEFAULT NULL,
  `st_id` int(11) DEFAULT NULL COMMENT '门店id',
  `us_id` int(11) DEFAULT NULL COMMENT '用户id',
  `addr_id` int(11) DEFAULT NULL COMMENT '地址id',
  `or_address` varchar(255) DEFAULT NULL COMMENT '收货地址',
  `or_type` int(11) DEFAULT '0' COMMENT '0正常 1预购',
  `or_style` int(11) DEFAULT '0' COMMENT '0未使用购物币 1使用了',
  `or_yuan` decimal(10,2) DEFAULT '0.00' COMMENT '总人民币',
  `or_coin` int(10) DEFAULT '0' COMMENT '总购物币',
  `or_add_time` datetime DEFAULT NULL COMMENT '添加订单时间',
  `or_courier_time` datetime DEFAULT NULL COMMENT '配送时间',
  `or_status` int(11) DEFAULT NULL COMMENT '订单状态 0未付款 1已付款待配送 2已配送待收货 3交易完成',
  `co_id` int(11) DEFAULT NULL COMMENT '配送员id',
  `or_finish_time` datetime DEFAULT NULL COMMENT '完成时间',
  `or_opinion_time` datetime DEFAULT NULL COMMENT '预约时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='订单表';

#
# Data for table "new_order"
#

INSERT INTO `new_order` VALUES (1,'123456',0.00,NULL,2,1,NULL,NULL,0,0,0.00,0,'2018-05-02 14:43:00',NULL,0,2,NULL,NULL);

#
# Structure for table "new_order_detail"
#

DROP TABLE IF EXISTS `new_order_detail`;
CREATE TABLE `new_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `or_id` int(11) DEFAULT NULL COMMENT '订单id',
  `st_id` int(11) DEFAULT NULL COMMENT '门店id',
  `ca_id` int(11) DEFAULT NULL COMMENT '分类id',
  `pd_id` int(11) DEFAULT NULL COMMENT '产品id',
  `or_de_name` varchar(255) DEFAULT NULL COMMENT '产品名称',
  `or_de_pic` text COMMENT '产品主图',
  `or_de_price` int(11) DEFAULT NULL COMMENT '单价',
  `or_de_total` decimal(10,2) DEFAULT NULL COMMENT '总价',
  `or_de_coin` int(11) DEFAULT '0' COMMENT '购物币需求',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `or_number` varchar(255) DEFAULT NULL COMMENT '订单编号',
  `or_de_num` int(11) DEFAULT NULL COMMENT '产品数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='订单详情表';

#
# Data for table "new_order_detail"
#

INSERT INTO `new_order_detail` VALUES (1,1,2,1,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),(2,2,2,2,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL);

#
# Structure for table "new_pay_wechat"
#

DROP TABLE IF EXISTS `new_pay_wechat`;
CREATE TABLE `new_pay_wechat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `us_id` int(11) DEFAULT NULL COMMENT '用户id',
  `pa_we_type` int(11) DEFAULT '0' COMMENT '0购买购物币1购买产品',
  `pa_we_status` int(11) DEFAULT '0' COMMENT '支付状态',
  `pa_we_num` int(10) DEFAULT NULL COMMENT '支付金额',
  `pa_we_add_time` datetime DEFAULT NULL COMMENT '添加时间',
  `pa_we_number` int(11) DEFAULT NULL COMMENT '支付单号',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信支付记录';

#
# Data for table "new_pay_wechat"
#


#
# Structure for table "new_product"
#

DROP TABLE IF EXISTS `new_product`;
CREATE TABLE `new_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pd_name` varchar(255) DEFAULT NULL COMMENT '产品名称',
  `pd_price` decimal(10,2) DEFAULT '0.00' COMMENT '产品价格',
  `pd_price_reservation` decimal(10,2) DEFAULT '0.00' COMMENT '产品预定价格',
  `pd_price_coin` decimal(10,2) DEFAULT '0.00' COMMENT '可包含购物币',
  `pd_status` int(11) DEFAULT '0' COMMENT '状态 0 仓库 1下架 2上架',
  `pd_inventory` varchar(255) DEFAULT '0' COMMENT '产品库存',
  `pd_sales` int(11) DEFAULT '0' COMMENT '产品销量',
  `pd_pic` text COMMENT '产品主图',
  `pd_content` text COMMENT '内容',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `pd_add_time` datetime DEFAULT NULL COMMENT '添加时间',
  `st_id` int(11) DEFAULT NULL COMMENT '门店id',
  `ca_id` int(11) DEFAULT NULL COMMENT '分类id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='商品表';

#
# Data for table "new_product"
#

INSERT INTO `new_product` VALUES (3,'测试',1000.00,0.00,NULL,2,'100',0,'/uploads/20180503\\78cd378277b9914da8fc087806cf2043.jpg',NULL,NULL,'2018-05-03 14:03:00',2,1),(4,'测试2',500.00,100.00,50.00,2,'10',0,'/uploads/20180507\\8b4b49432bd13bfc292583b16d4ce729.jpg',NULL,NULL,'2018-05-03 14:03:00',2,1),(5,'测试2',500.00,100.00,50.00,2,'10',0,'/uploads/20180507\\8b4b49432bd13bfc292583b16d4ce729.jpg','',NULL,'2018-05-03 14:03:00',2,1),(6,'测试2',500.00,100.00,50.00,2,'10',0,'/uploads/20180507\\8b4b49432bd13bfc292583b16d4ce729.jpg','',NULL,'2018-05-03 14:03:00',2,2),(7,'测试2',500.00,100.00,50.00,2,'10',0,'/uploads/20180507\\8b4b49432bd13bfc292583b16d4ce729.jpg','',NULL,'2018-05-03 14:03:00',2,2),(8,'测试2',500.00,100.00,50.00,2,'10',0,'/uploads/20180507\\8b4b49432bd13bfc292583b16d4ce729.jpg','',NULL,'2018-05-03 14:03:00',2,3),(9,'测试2',500.00,100.00,50.00,2,'10',0,'/uploads/20180507\\8b4b49432bd13bfc292583b16d4ce729.jpg','',NULL,'2018-05-03 14:03:00',2,2),(10,'测试2',500.00,100.00,50.00,2,'10',0,'/uploads/20180507\\8b4b49432bd13bfc292583b16d4ce729.jpg','',NULL,'2018-05-03 14:03:00',2,3),(11,'测试2',500.00,100.00,50.00,2,'10',0,'/uploads/20180507\\8b4b49432bd13bfc292583b16d4ce729.jpg','',NULL,'2018-05-03 14:03:00',2,3),(12,'测试2',500.00,100.00,50.00,2,'10',0,'/uploads/20180507\\8b4b49432bd13bfc292583b16d4ce729.jpg','',NULL,'2018-05-03 14:03:00',2,4),(13,'测试2',500.00,100.00,50.00,2,'10',0,'/uploads/20180507\\8b4b49432bd13bfc292583b16d4ce729.jpg','',NULL,'2018-05-03 14:03:00',2,4),(14,'测试2',500.00,100.00,50.00,2,'10',0,'/uploads/20180507\\8b4b49432bd13bfc292583b16d4ce729.jpg','',NULL,'2018-05-03 14:03:00',2,4);

#
# Structure for table "new_role"
#

DROP TABLE IF EXISTS `new_role`;
CREATE TABLE `new_role` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ro_name` char(100) NOT NULL DEFAULT '' COMMENT '角色名称',
  `ro_description` text COMMENT '角色描述',
  `ro_status` tinyint(1) NOT NULL DEFAULT '1',
  `ro_rules` varchar(100) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，逗号隔开',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='后台用户组，角色表';

#
# Data for table "new_role"
#

INSERT INTO `new_role` VALUES (1,'超管','网站所有权限',1,'1,2,3,4,5,6,7,8'),(2,'管理员','普通管理员所有权限',1,'1,2'),(5,'仓库管理','管理仓库商品',1,'1,2,3,4'),(6,'客服','订单 门店管理',1,'1,2,3,4,5,6');

#
# Structure for table "new_rule"
#

DROP TABLE IF EXISTS `new_rule`;
CREATE TABLE `new_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '方法名',
  `meth` varchar(255) DEFAULT NULL COMMENT '方法',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='规则表';

#
# Data for table "new_rule"
#

INSERT INTO `new_rule` VALUES (1,0,'admin','管理员',''),(2,1,'admin/admin/index','列表','get'),(3,1,'admin/admin/index','修改状态','post'),(4,1,'admin/admin/add','添加','post'),(5,1,'admin/admin/edit','修改','post'),(6,1,'admin/admin/roleindex','角色列表','get'),(7,1,'admin/admin/roleadd','添加角色','post'),(8,1,'admin/admin/roleedit','修改角色','post');

#
# Structure for table "new_store"
#

DROP TABLE IF EXISTS `new_store`;
CREATE TABLE `new_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `st_name` varchar(255) DEFAULT NULL COMMENT '门店名称',
  `st_status` int(11) DEFAULT '1' COMMENT '门店状态0封 1开启',
  `st_serial_number` varchar(255) DEFAULT NULL COMMENT '门店编号',
  `st_pwd` varchar(255) DEFAULT NULL COMMENT '门店登录密码',
  `st_safe_pwd` varchar(255) DEFAULT NULL COMMENT '安全密码',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `st_place` varchar(255) DEFAULT NULL COMMENT '门店位置',
  `st_logo` text COMMENT '门店logo',
  `st_label` text COMMENT '标签',
  `st_describe` varchar(255) DEFAULT NULL COMMENT '门店描述',
  `st_pic` text COMMENT '门店主图',
  `st_add_time` datetime DEFAULT NULL COMMENT '添加时间',
  `st_tel` varchar(255) DEFAULT NULL COMMENT '门店联系电话',
  `st_longitude` varchar(255) DEFAULT NULL COMMENT '经度',
  `st_latitude` varchar(255) DEFAULT NULL COMMENT '纬度',
  `st_address` varchar(255) DEFAULT NULL COMMENT '门店地址信息',
  `st_addr_detail` varchar(255) DEFAULT NULL COMMENT '详细街道信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='门店列表';

#
# Data for table "new_store"
#

INSERT INTO `new_store` VALUES (1,'小学生花店',1,'md10001','123456',NULL,NULL,NULL,'/uploads/20180426\\88fa285cf4189b07ff3553a37379e7fe.jpg','美食,零食,美食',NULL,'/uploads/20180426\\b1f910377baa480d77a92372063b3b57.jpg\0','2018-04-26 18:17:27','123456','113.619968','34.738055',NULL,NULL),(2,'中学生书店',1,'md10002','123',NULL,NULL,NULL,'/uploads/20180426\\4f7e67f2371420c754e0a2ba2a07df24.jpg','零食,美食,米饭',NULL,'/uploads/20180426\\b1f910377baa480d77a92372063b3b57.jpg�,/uploads/20180427\\56a64c048931c0d5207f9251ea8b4ce3.jpg,/uploads/20180427\\e0f92659fc69d9df6830aa0b24682626.jpg','2018-04-26 19:51:22','123','113.619968','34.748055',NULL,NULL),(3,'大学生小吃店',1,'md10003','9bKhruGd881c4dOcIKdbtw==',NULL,NULL,NULL,'/uploads/20180426\\33984f30cd87aaf676398ecc32acdb2e.jpg','零食,美食',NULL,'/uploads/20180502\\ff17778d5c31019a180160d7a227df40.jpg','2018-04-26 19:56:18','12345678','113.615081','34.729152','河南省郑州市中原区沁河路','毛毛');

#
# Structure for table "new_tixian"
#

DROP TABLE IF EXISTS `new_tixian`;
CREATE TABLE `new_tixian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `us_id` int(11) DEFAULT NULL COMMENT '提现人id',
  `tx_num` int(11) DEFAULT NULL COMMENT '提现金额',
  `tx_add_time` datetime DEFAULT NULL COMMENT '提现时间',
  `tx_status` int(11) DEFAULT '0' COMMENT '提现状态',
  `tx_type` int(11) DEFAULT '0' COMMENT '0银行卡1支付宝2微信',
  `tx_account` varchar(255) DEFAULT NULL COMMENT '提现账号',
  `tx_name` varchar(255) DEFAULT NULL COMMENT '提现人',
  `tx_addr` varchar(255) DEFAULT NULL COMMENT '开户行地址',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='佣金提现记录';

#
# Data for table "new_tixian"
#


#
# Structure for table "new_user"
#

DROP TABLE IF EXISTS `new_user`;
CREATE TABLE `new_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `us_account` varchar(100) DEFAULT NULL COMMENT '账户名',
  `us_pid` int(11) DEFAULT '0' COMMENT '父id',
  `us_tel` varchar(11) DEFAULT NULL COMMENT '手机号',
  `us_real_name` varchar(255) DEFAULT NULL COMMENT '真实姓名',
  `us_head_pic` text COMMENT '头像',
  `us_pwd` varchar(255) DEFAULT NULL COMMENT '登录密码',
  `us_safe_pwd` varchar(255) DEFAULT NULL COMMENT '安全密码',
  `us_status` int(11) DEFAULT '1' COMMENT '0冻结 1正常',
  `us_wallet` int(10) DEFAULT '0' COMMENT '购物币',
  `us_msc` decimal(10,2) DEFAULT '0.00' COMMENT '佣金',
  `us_path` text COMMENT '全路径',
  `us_add_time` datetime DEFAULT NULL COMMENT '添加时间',
  `delete_time` int(11) DEFAULT NULL,
  `us_qr_code` text COMMENT '带头像的二维码',
  `us_bank` varchar(255) DEFAULT NULL COMMENT '银行名称',
  `us_bank_number` varchar(255) DEFAULT NULL COMMENT '银行卡号',
  `us_bank_person` varchar(255) DEFAULT NULL COMMENT '收款人',
  `us_bank_addr` varchar(255) DEFAULT NULL COMMENT '开户行地址',
  `us_openid` varchar(255) DEFAULT NULL COMMENT '微信openid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`us_account`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COMMENT='用户';

#
# Data for table "new_user"
#

INSERT INTO `new_user` VALUES (1,'gz100001',0,'18739912501','杜梦',NULL,'WTjVcHJSLw5bZeHPyyEN6g==','9bKhruGd881c4dOcIKdbtw==',1,860,5000.00,'0,12,13','2018-03-24 11:38:13',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(13,'gz100002',1,'18739912502','',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,19,2100.00,'0,1','2018-03-24 11:38:13',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(15,'gz100004',13,'18739912537','mao',NULL,'9bKhruGd881c4dOcIKdbtw==','i5D4R48c3pT+wMIGRmNBlg==',1,16708,2000.00,'0,12,13','2018-03-28 14:50:29',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(17,'gz100006',15,'18739912535','123456',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'0','2018-03-29 17:23:23',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(18,'gz100007',0,'18739912536','123456',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'0','2018-03-29 17:24:18',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(20,'gz100008',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(23,'gz100009',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(24,'gz100010',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(25,'gz100011',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(26,'gz100012',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(27,'gz100013',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,'gz100014',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(29,'gz100015',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(30,'gz100016',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(31,'gz100017',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(32,'gz100018',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',0,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(33,'gz100019',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(34,'gz100020',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(35,'gz100021',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(36,'gz100022',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(37,'gz100023',0,'13071099897','1',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(38,'gz100024',0,'13071099897','mao',NULL,'i5D4R48c3pT+wMIGRmNBlg==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,'','2018-04-02 22:05:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(39,'gz100025',0,'','SDFAS',NULL,'XNHajanOz0A=','XNHajanOz0A=',1,400,0.00,'0','2018-04-09 11:38:19',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(40,'gz100026',1,'13071099898','杜梦',NULL,'WTjVcHJSLw5bZeHPyyEN6g==','WTjVcHJSLw5bZeHPyyEN6g==',1,300,0.00,'0,12,13,1','2018-04-09 11:45:50',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(41,'gz100027',15,'12345675676','123456',NULL,'WTjVcHJSLw5bZeHPyyEN6g==','WTjVcHJSLw5bZeHPyyEN6g==',1,0,0.00,'0,12,13,1,40','2018-04-09 14:22:32',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(42,'gz100028',15,'213','456',NULL,'WTjVcHJSLw5bZeHPyyEN6g==','wRBj0O3rGqhbZeHPyyEN6g==',1,0,0.00,'0,12,13,1,40','2018-04-09 14:27:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(43,'gz100029',41,'13071099800','费大幅',NULL,'WTjVcHJSLw5bZeHPyyEN6g==','WTjVcHJSLw5bZeHPyyEN6g==',1,0,0.00,'0,12,13,1,40,41','2018-04-09 14:28:13',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(44,'gz100030',39,'123','admin',NULL,'WTjVcHJSLw5bZeHPyyEN6g==','XNHajanOz0A=',1,0,0.00,'0,39','2018-04-10 09:19:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(45,'gz100031',39,'123456','admin1',NULL,'WTjVcHJSLw5bZeHPyyEN6g==','XNHajanOz0A=',1,0,0.00,'0,39','2018-04-10 09:21:59',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(46,'gz100032',39,'000','admin2',NULL,'H/MmQyJ84G9bZeHPyyEN6g==','XNHajanOz0A=',1,0,0.00,'0,39','2018-04-10 09:25:27',1523325464,NULL,NULL,NULL,NULL,NULL,NULL),(47,'gz100033',39,'111','admin',NULL,'+AN2PRTN9Ds=','XNHajanOz0A=',1,0,0.00,'0,39','2018-04-10 09:26:17',1523325455,NULL,NULL,NULL,NULL,NULL,NULL),(48,'gz100034',39,'77777','admin7',NULL,'hvZsXRgD0eJbZeHPyyEN6g==','XNHajanOz0A=',1,0,0.00,'0,39','2018-04-10 09:26:40',1523325446,NULL,NULL,NULL,NULL,NULL,NULL),(49,'gz100035',39,'1111','admin',NULL,'XNHajanOz0A=','XNHajanOz0A=',1,0,0.00,'0,39','2018-04-10 09:46:42',1523325439,NULL,NULL,NULL,NULL,NULL,NULL),(51,'gz100036',1,'13077777777','杜梦',NULL,'XNHajanOz0A=','XNHajanOz0A=',1,0,1.00,'0,12,13,1','2018-04-11 10:09:23',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(52,'gz100037',51,'13088888888',NULL,NULL,'WTjVcHJSLw5bZeHPyyEN6g==','WTjVcHJSLw5bZeHPyyEN6g==',1,0,1000.00,'0,12,13,1,51','2018-04-11 10:21:56',1523609087,NULL,NULL,NULL,NULL,NULL,NULL),(53,'1',1,'13054656465',NULL,NULL,'WTjVcHJSLw5bZeHPyyEN6g==','WTjVcHJSLw5bZeHPyyEN6g==',1,0,0.00,'0,12,13,1','2018-04-13 17:35:45',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(54,'2',1,'13045646456',NULL,NULL,'WTjVcHJSLw5bZeHPyyEN6g==','WTjVcHJSLw5bZeHPyyEN6g==',0,0,0.00,'0,12,13,1','2018-04-13 17:40:48',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(55,'3',1,'13058945945',NULL,NULL,'WTjVcHJSLw5bZeHPyyEN6g==','WTjVcHJSLw5bZeHPyyEN6g==',0,0,0.00,'0,12,13,1','2018-04-13 17:41:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(56,'34',15,'1234567','123456',NULL,'123456','123456',0,0,0.00,'0,12,13,15','2018-04-16 18:36:57',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(57,'3435',18,'12345','123456',NULL,'123456','123456',0,0,0.00,'0,18','2018-04-16 18:43:46',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(58,'34353436',18,'222','222',NULL,'222','222',0,0,0.00,'0,18','2018-04-16 19:06:19',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(59,'343553437',0,'123455','123',NULL,'123','123',0,0,0.00,NULL,'2018-04-16 19:32:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(60,'343553438',0,'123fasdf','123',NULL,'123','1',0,0,0.00,NULL,'2018-04-16 19:32:37',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(61,'343553439',0,'3','2',NULL,'4','5',0,0,0.00,NULL,'2018-04-16 19:33:32',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(62,'343553440',0,'5241','4',NULL,'21','3',0,0,0.00,NULL,'2018-04-16 19:35:48',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(63,'343553441',0,'adsfa','fasd',NULL,'fasd','afsds',0,0,0.00,NULL,'2018-04-16 19:36:33',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(64,'343553442',0,'asdfa','afsdf','/uploads/20180505\\93475f48c014977d8f4fcc292b61c94b.jpg','','',0,0,0.00,NULL,'2018-04-16 19:36:40',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(65,'343553443',0,'18739912525','dengyan',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,NULL,'2018-05-07 11:34:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(66,'343553444',0,'18739912524','dengyan',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,NULL,'2018-05-07 11:35:32',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(67,'343553445',0,'18739912222','毛毛毛',NULL,'9bKhruGd881c4dOcIKdbtw==','9bKhruGd881c4dOcIKdbtw==',1,0,0.00,NULL,'2018-05-11 18:08:01',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(68,'343553446',0,'18739912538','mao',NULL,'9bKhruGd881c4dOcIKdbtw==',NULL,1,0,0.00,NULL,'2018-05-11 19:45:18',NULL,NULL,NULL,NULL,NULL,NULL,NULL);

#
# Structure for table "new_user_addr"
#

DROP TABLE IF EXISTS `new_user_addr`;
CREATE TABLE `new_user_addr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addr_longitude` varchar(255) DEFAULT NULL COMMENT '经度',
  `addr_latitude` varchar(255) DEFAULT NULL COMMENT '纬度',
  `addr_addr` varchar(255) DEFAULT NULL COMMENT '用户地址',
  `addr_detail` varchar(255) DEFAULT NULL COMMENT '街道信息',
  `addr_default` int(11) DEFAULT '0' COMMENT '默认',
  `us_id` int(11) DEFAULT NULL COMMENT '用户id',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `addr_add_time` datetime DEFAULT NULL COMMENT '添加时间',
  `addr_tel` varchar(255) DEFAULT NULL COMMENT '收货人手机号',
  `addr_consignee` varchar(255) DEFAULT NULL COMMENT '收货人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='收货地址';

#
# Data for table "new_user_addr"
#

INSERT INTO `new_user_addr` VALUES (1,'113.615081','34.729152','11','2',0,64,NULL,NULL,'3','4');

#
# Structure for table "new_user_wechat"
#

DROP TABLE IF EXISTS `new_user_wechat`;
CREATE TABLE `new_user_wechat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户绑定微信';

#
# Data for table "new_user_wechat"
#


#
# Structure for table "new_wallet"
#

DROP TABLE IF EXISTS `new_wallet`;
CREATE TABLE `new_wallet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `us_id` int(11) DEFAULT NULL COMMENT '用户id',
  `wa_num` int(11) DEFAULT NULL COMMENT '金额',
  `wa_type` int(11) DEFAULT NULL COMMENT '种类',
  `wa_type_text` varchar(255) DEFAULT NULL COMMENT '类型',
  `wa_note` varchar(255) DEFAULT NULL COMMENT '说明',
  `wa_add_time` datetime DEFAULT NULL COMMENT '添加时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='购物币 佣金明细';

#
# Data for table "new_wallet"
#

INSERT INTO `new_wallet` VALUES (1,1,1,1,'maomao','1','1899-12-30 01:00:00',NULL);
