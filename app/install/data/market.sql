--
-- 表的结构 `tb_role`
-- 使用ThinkCMF自带的权限管理
--


--
-- 表的结构 `tb_posts`
--
CREATE TABLE IF NOT EXISTS `tb_posts`(
	`id` int UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` varchar(100) NOT NULL CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '岗位名称',
	`salary` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '工资',
	`address` varchar(100) NOT NULL CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '工作地点',
	`count` int UNSIGNED NULL DEFAULT '0' COMMENT '在岗人数', 
	`role` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理权限',
	`remark` text NULL CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`role`) REFERENCES `tb_role`(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=UTF8 COMMENT='员工岗位表';


--
-- 转存表的数据 `tb_posts`
--
INSERT INTO `tb_posts`(`id`,`name`,`salary`,`address`,`count`,`role`,`remark`) VALUES
('1','店主','0',' ','1','1','店主为超级管理员'),
('2','普通员工','5600','山东威海','1','2','普通员工拥有普通权限');


-- 
-- 表的结构 `tb_adminstrator` 
--

CREATE TABLE IF NOT EXISTS `tb_adminstrator`(
	`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '员工id',
	`name` char(50) NOT NULL CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '员工姓名',
	`mobile` char(11) NOT NULL COMMENT '员工登录手机号',
	`user_pass` varchar(64) NOT NULL COMMENT '员工登录密码,cmf_password()加密',
	`user_status` tinyint(3) NOT NULL DEFAULT '2' COMMENT '员工状态,0:离职,1:正常,2:未验证',
	`birthday` int(11) DEFAULT ‘0’ COMMENT '员工生日',
	`sex` tinyint(2) DEFAULT '1' COMMENT '员工性别,1:男,2:女',
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '入职时间',
	`post` int UNSINGED NOT NULL COMMENT '员工岗位',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`post`) REFERENCES `tb_posts`(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=UTF8 COMMENT='员工表';

--
-- 转存表的数据 `tb_adminstrator`
--
INSERT INTO `tb_adminstrator`(`id`,`name`,`mobile`,`user_pass`,`birthday`,`sex`,`create_time`,`post`) VALUES 
('1','GT','17862701356','e10adc3949ba59abbe56e057f20f883e','850924800','1','1522228648','1'),
('2','aman','17862700605','e10adc3949ba59abbe56e057f20f883e','851000000','1','1522230000','2'); 


--
-- 表的结构 `tb_salary_accounting`
--
CREATE TABLE IF NOT EXISTS `tb_salary_accounting`(
	`id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`adminstrator_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '员工id',
	`num` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '结算金额', 
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '结算时间',
	`remark` text NULL DEFAULT '' COMMENT '备注',
	FOREIGN KEY(`adminstrator_id`) REFERENCES `tb_adminstrator`(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=UTF8 COMMENT='员工工资财务表';

--
-- 表的结构 `tb_daily_checkin`
--
CREATE TABLE IF NOT EXISTS `tb_daily_checkin`(
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`adminstrator_id` NOT NULL DEFAULT '0' COMMENT '员工id',
	`checkin_time` int(11) NOT NULL DEFAULT '0' COMMENT '签到时间',
	`checkin_status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '签到状态,1:正常,2:请假',
	`remark` text NULL DEFAULT '' COMMENT '备注',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`adminstrator_id`) `tb_adminstrator`(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=UTF8 COMMENT='每日签到表';


--
-- 表的结构 `tb_user_level`
--
CREATE TABLE IF NOT EXISTS `tb_user_level`(
	`id` int NOT NULL AUTO_INCREAMENT,
	`name` varchar(64) NOT NULL CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT `等级名称`,
	`count` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户数',
	`status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态, 0:禁用，1:启用',
	PRIMARY KEY(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=UTF8 COMMENT='用户等级';


--
-- 转存表的数据 `tb_user_level`
--
INSERT INTO `tb_user_level`(`id`,`name`,`count`,`status`) VALUES
('1','level 1','1','1'),
('1','level 2','0','1'),
('1','level 3','0','1'),
('1','level 4','0','1'),
('1','level 5','0','1'),
('1','level 6','0','1'),
('1','level 7','0','1'),
('1','level 8','0','1'),
('1','level 9','0','1'),
('1','level 10','0','1');


--
-- 表的结构 `tb_user`
--
CREATE TABLE IF NOT EXISTS `tb_user`(
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`name` char(50) NOT NULL CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户实名',
	`mobile` char(11) NOT NULL DEFAULT '' COMMENT '用户登录手机号',
	`user_pass` varchar(64) NOT NULL DEFAULT '' COMMENT '用户登录密码，cmf_password()加密',
	`user_status` tinyint(3) NOT NULL DEFAULT '' COMMENT '用户状态, 0:禁用，1:正常, 2:未验证'
	`user_login` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
	`user_email` varchar(100) NOT NULL DEFAULT '' COMMENT '用户登录邮箱',
	`last_login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
	`user_activation_key` varchar(60) NOT NULL DEFAULT '' COMMENT '激活码',
	`create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
	`point` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户积分',
	`balance` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '余额',
	`avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
	`sex` tinyint(3) NOT NULL DEFAULT '0' COMMENT '性别, 0:保密, 1:男, 2:女',
	`birthday` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '生日',
	`user_level` int NOT NULL DEFAULT '1' COMMENT '用户等级',
	`more` text NULL DEFAULT '' COMMENT '扩展属性',
	PRIMARY KEY(`id`),
	UNIQUE KEY(`mobile`),(`user_login`),(`user_email`),
	FOREIGN KEY(`user_level`) REFERENCES `tb_user_level`(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=UTF8 COMMENT='用户表';


--
-- 表的结构
--











