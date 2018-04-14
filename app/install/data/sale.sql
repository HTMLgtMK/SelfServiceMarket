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
	`coin` float NOT NULL DEFAULT '0' COMMENT '现金抵扣，为负',
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
	`expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',
	`count` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '折扣总数',
	`rest` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '折扣剩余数量',
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
('1','无折扣','1.0','0.0','1522292333','0','2147483647','2147483647','无折扣，购买不减少剩余份数');

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
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户id',
	`store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
	`terminal_id` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '终端id',
	`goods_detail` text COMMENT '商品交易详情，JSON格式数据',
	`pay_amount` float NOT NULL DEFAULT '0' COMMENT '支付金额',
	`discount_amount` float NOT NULL DEFAULT '0' COMMENT '折扣金额',
	`total_amount` float NOT NULL DEFAULT '0' COMMENT '总金额',
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


