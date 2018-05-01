-- ---------------------------------------------------
-- 无人超市的财务模块 SQL: 财务， 签到
-- ---------------------------------------------------


--
-- 表的结构 `tb_market_salary_accounting`
--

CREATE TABLE IF NOT EXISTS `tb_market_salary_accounting` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`adminstrator_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '员工id',
	`num` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '结算金额', 
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '结算时间',
	`detail` text COMMENT '财务详情,JSON格式',
	`remark` text COMMENT '备注',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`adminstrator_id`) REFERENCES `tb_adminstrator`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='员工工资财务表';

-- ---------------------------------------------------

--
-- 表的结构 `tb_market_daily_checkin`
--

CREATE TABLE IF NOT EXISTS `tb_market_daily_checkin` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`adminstrator_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '员工id',
	`checkin_time` int(11) NOT NULL DEFAULT '0' COMMENT '签到时间',
	`checkin_status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '签到状态,1:正常,2:请假',
	`remark` text COMMENT '备注',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`adminstrator_id`) REFERENCES `tb_adminstrator`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8 COMMENT='每日签到表';

-- ----------------------------------------------------

--
--  表的结构 `tb_market_account_tag`
--

CREATE TABLE IF NOT EXISTS `tb_market_account_type` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` char(64) NOT NULL COMMENT '财务类型, 采购, 维护, 工资等',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8 COMMENT='无人超市财务类型表';


--
-- 转存表的数据 `tb_market_account_type`
--

INSERT INTO `tb_market_account_type`(`id`,`name`) VALUES
	('1', '采购'),
	('2', '维护'),
	('3', '工资');
	
--
-- 表的结构 `tb_market_account`
--

CREATE TABLE IF NOT EXISTS `tb_market_account`(
	`id` int NOT NULL AUTO_INCREMENT,
	`type_id` int NOT NULL COMMENT '财务类型',
	`amount`  int NOT NULL DEFAULT '0' COMMENT '金额',
	`inout` tinyint(3) NOT NULL DEFAULT '1' COMMENT '收入or支出, 1:收入, 2:支出',
	`create_time` int NOT NULL DEFAULT '0' COMMENT '创建时间',
	`remark` text COMMENT '备注',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`type_id`) REFERENCES `tb_market_account_type`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8 COMMENT='无人超市财务表';