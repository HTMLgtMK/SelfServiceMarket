-- ---------------------------------------------------
-- 销售数据表: 折扣，折扣关系， 销售关系
-- ---------------------------------------------------

--
-- 表的结构 `tb_discount`
--

CREATE TABLE IF NOT EXISTS `tb_discount` (
	`id` int UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '折扣名',
	`extent` float NOT NULL DEFAULT '0' COMMENT '比例折扣，正小数',
	`coin` int NOT NULL DEFAULT '0' COMMENT '现金抵扣，为负，以分为单位。。。',
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
	`expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',
	`count` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '折扣总数',
	`rest` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '折扣剩余数量',
	`open` tinyint(2) NOT NULL DEFAULT '1' COMMENT '开发性, 1:全面开放, 2:仅对会员开放'
	`remark` text COMMENT '备注',
	PRIMARY KEY(`id`),
	UNIQUE KEY(`name`),
	CONSTRAINT `chk_discount` CHECK 
	(`extent` >= '0' 
		AND `extent` <= '1.0' 
		AND `rest` <= `count`
		AND `coin` <= '0' )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='折扣表';

--
-- 转存表中的数据 `tb_discount`
--

INSERT INTO `tb_discount`(`id`,`name`,`extent`,`coin`,`create_time`, `expire_time`, `count`, `rest`, `remark`) VALUES
('1','无折扣','1.0','0','1522292333','0','2147483647','2147483647','无折扣，购买不减少剩余份数');

--
-- 折扣关系 `tb_discount_goods`
--

CREATE TABLE IF NOT EXISTS `tb_discount_goods` (
	`id` int UNSIGNED NOT NULL AUTO_INCREMENT,
	`discount_id` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '折扣id',
	`goods_type_id` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品类别id',
	`count` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '折扣总数',
	`rest` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '剩余数量',
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
	`expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',
	`remark` text COMMENT '备注',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`discount_id`) REFERENCES `tb_discount`(`id`),
	FOREIGN KEY(`goods_type_id`) REFERENCES `tb_goods_type`(`id`),
	CONSTRAINT `chk_discount_goods_rest` CHECK (`rest` <= `count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='折扣关系表';

--
-- 表的结构 `tb_discount_user`
--

CREATE TABLE IF NOT EXISTS `tb_discount_user` (
	`id` int UNSIGNED NOT NULL AUTO_INCREMENT, 
	`discount_id` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '优惠ID',
	`user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
	`count` int UNSIGNED NOT NULL DEFAULT '1' COMMENT '优惠数量',
	`rest` int UNSIGNED NOT NULL DEFAULT '1' COMMENT '剩余数量',
	`create_time` int(11) NOT NULL DEFAULT '1' COMMENT '创建时间',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`discount_id`) REFERENCES `tb_discount`(`id`),
	FOREIGN KEY(`user_id`) REFERENCES `tb_user`(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT '优惠用户表';

--
-- 表的结构 `tb_cart`
--

CREATE TABLE IF NOT EXISTS `tb_cart` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
	`user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户id',
	`goods_type_id` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品类别id',
	`count` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '选购件数',
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
	`remark` text COMMENT '备注',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`store_id`) REFERENCES `tb_store`(`id`),
	FOREIGN KEY(`goods_type_id`) REFERENCES `tb_goods_type`(`id`),
	FOREIGN KEY(`user_id`) 	REFERENCES `tb_user`(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='购物车表';

--
-- 表的结构 `tb_sale`
--

CREATE TABLE IF NOT EXISTS `tb_sale` (
	`id` char(32) NOT NULL COMMENT '交易单号, 时间戳+随机字符串(16)',
	`user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户id',
	`store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
	`terminal_id` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '终端id',
	`goods_detail` text COMMENT '商品交易详情，JSON格式数据',
	`pay_amount` int NOT NULL DEFAULT '0' COMMENT '支付金额, 以分为单位。。。',
	`discount_amount` int NOT NULL DEFAULT '0' COMMENT '折扣金额, 以分为单位。。。',
	`total_amount` int NOT NULL DEFAULT '0' COMMENT '总金额, 以分为单位。。。',
	`pay_detail` text COMMENT '交易详情, JSON格式数据',
	`remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '备注',
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
	`modify_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
	`status` tinyint NOT NULL DEFAULT '1' COMMENT '交易状态, 1: 待付款, 2:超时关闭, 3:成功, 4:取消',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`user_id`) REFERENCES `tb_user`(`id`),
	FOREIGN KEY(`store_id`) REFERENCES `tb_store`(`id`),
	FOREIGN KEY(`terminal_id`) REFERENCES `tb_store_terminal`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='交易表';

--
-- 视图的结构 `view_store_sale` 店铺今日交易视图
--

CREATE VIEW `view_store_sale` AS 
	SELECT `store_id`, sum(`total_amount`) as `sale_total_amount` FROM `tb_sale` 
		WHERE `create_time` BETWEEN unix_timestamp(curdate()) AND unix_timestamp() 
		GROUP BY `store_id` ;
