-- ---------------------------------------------------
-- 店铺相关模块： 终端， 店铺
-- ---------------------------------------------------

--
-- 表的结构 `tb_store`
--

CREATE TABLE IF NOT EXISTS `tb_store` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '店铺名',
	`status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '店铺状态, 0:长期关闭， 1:正常营业， 2:打烊',
	`address` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '店铺地址',
	`adminstrator_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '店铺管理员',
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`adminstrator_id`) REFERENCES `tb_adminstrator`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商店表';


--
-- 转存表中的数据 `tb_store`
-- 
INSERT INTO `tb_store` (`id`,`name`,`status`,`address`,`adminstrator_id`,`create_time`) VALUES
('1','一号店','1','山东威海','1','1522331661');

--
-- 表的结构 `tb_store_terminal`
--

CREATE TABLE IF NOT EXISTS `tb_store_terminal` (
	`id` int UNSIGNED NOT NULL AUTO_INCREMENT,
	`ip` char(15) NOT NULL DEFAULT '0.0.0.0' COMMENT '终端ip地址',
	`salecount` int NOT NULL DEFAULT '0' COMMENT '销售总数',
	`status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '终端状态, 0:停用, 1:正常',
	`store_id` int NOT NULL DEFAULT '0' COMMENT '店铺id',
	`remark` text COMMENT '备注',
	PRIMARY KEY(`id`),
	UNIQUE KEY(`ip`),
	FOREIGN KEY(`store_id`) REFERENCES `tb_store`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='店铺自助收银终端表';

--
-- 转存表中的数据 `tb_store_terminal`
--

INSERT INTO `tb_store_terminal` (`id`,`ip`,`salecount`,`status`,`store_id`,`remark`) VALUES
('1', '0.0.0.0', '1','1','1', '尚未分配ip的终端');
