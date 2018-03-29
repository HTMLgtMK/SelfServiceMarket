-- ---------------------------------------------------
-- 商品管理SQL: 商品类别, 商品, 供应商, 供应关系
-- ---------------------------------------------------

--
-- 表的结构 `tb_goods_type`
--

CREATE TABLE IF NOT EXISTS `tb_goods_type` (
	`id` int UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品名称',
	`images` text COMMENT '商品图片,以逗号隔开',
	`price` float NOT NULL DEFAULT '0' COMMENT '商品价格',
	`address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '生产地址',
	`company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '公司名称',
	PRIMARY KEY(`id`),
	UNIQUE KEY(`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品类别';


--
-- 表的结构 `tb_goods`
--

CREATE TABLE IF NOT EXISTS `tb_goods` (
	`id` char(128) NOT NULL COMMENT '主键, 为ecode唯一编码',
	`type_id` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品类别号',
	`manufacture_date` int(11) NOT NULL DEFAULT '0' COMMENT '生产日期',
	`batch_number` char(255) NOT NULL DEFAULT '0' COMMENT '生产批号',
	`status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态, 1:待售, 2:已售',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`type_id`) REFERENCES `tb_goods_type`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品表';

-- ---------------------------------------------------

--
-- 表的结构 `tb_provider`
--

CREATE TABLE IF NOT EXISTS `tb_provider` (
	`id` int UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '供应商名称',
	`address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '供应商地址',
	PRIMARY KEY(`id`),
	UNIQUE KEY(`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='供应商表';

--
-- 表的结构 `tb_provider_goods`
--

CREATE TABLE IF NOT EXISTS `tb_provider_goods` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`provider_id` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '供应商id',
	`type_id` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品类别号',
	`price` float NOT NULL DEFAULT '0' COMMENT '供应商商品价格',
	`count` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '供应总数',
	`handover_num` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '已交付数',
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
	`status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '供应状态, 1:供应结束, 2:正在供应, 0:供应中断',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`provider_id`) REFERENCES `tb_provider`(`id`),
	FOREIGN KEY(`type_id`) REFERENCES `tb_goods_type`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='供应关系比表';







