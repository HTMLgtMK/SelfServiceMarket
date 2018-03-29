-- ---------------------------------------------------
-- 无人超市部分SQL: 岗位, 薪资, 签到， 财务， 余额与积分, 店铺，终端
-- 需要在ThinkCMF SQL后执行
-- ---------------------------------------------------

--
-- 表的结构 `tb_market_posts`
--

CREATE TABLE IF NOT EXISTS `tb_market_posts` (
	`id` int UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '岗位名称',
	`salary` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '工资',
	`address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '工作地点',
	`count` int UNSIGNED NULL DEFAULT '0' COMMENT '在岗人数', 
	`role` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理权限',
	`remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '备注',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`role`) REFERENCES `tb_role`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='员工岗位表';

-- ---------------------------------------------------

--
-- 转存表的数据 `tb_market_posts`
--

INSERT INTO `tb_market_posts` (`id`,`name`,`salary`,`address`,`count`,`role`,`remark`) VALUES
('1','店主','0',' ','1','1','店主为超级管理员'),
('2','普通员工','5600','山东威海','1','2','普通员工拥有普通权限');

--
-- 表的结构 `tb_user_balance_log`
--

CREATE TABLE IF NOT EXISTS `tb_user_balance_log` (
	`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户 id',
	`create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
	`change` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '更改余额',
	`balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '更改后余额',
	`description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
	`remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户余额变更日志表';

-- ---------------------------------------------------

--
-- 表的结构 `tb_user_point_log`
--

CREATE TABLE IF NOT EXISTS `tb_user_point_log` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户 id',
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
	`action` varchar(50) NOT NULL DEFAULT '' COMMENT '用户操作名称',
	`point` int(11) NOT NULL DEFAULT '0' COMMENT '更改积分，可以为负',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户操作积分等奖励日志表';


