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
