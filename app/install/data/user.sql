-- ------------------------------------------------
-- 用户部分SQL: 定义员工，普通用户，登录所用表
-- 需要在market.sql之后执行
-- ------------------------------------------------

-- 
-- 表的结构 `tb_adminstrator` 
--

CREATE TABLE IF NOT EXISTS `tb_adminstrator` (
	`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '员工id',
	`user_login` varchar(64) NOT NULL DEFAULT '' COMMENT '员工登录名',
	`name` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '员工姓名',
	`mobile` char(11) NOT NULL COMMENT '员工登录手机号',
	`user_pass` varchar(64) NOT NULL COMMENT '员工登录密码,cmf_password()加密',
	`user_status` tinyint(3) NOT NULL DEFAULT '2' COMMENT '员工状态,0:离职,1:正常,2:未验证',
	`birthday` int(11) DEFAULT '0' COMMENT '员工生日',
	`sex` tinyint(2) DEFAULT '1' COMMENT '员工性别,1:男,2:女',
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '入职时间',
	`post_id` int UNSIGNED NOT NULL COMMENT '员工岗位',
	PRIMARY KEY(`id`),
	UNIQUE KEY(`user_login`),
	UNIQUE KEY(`mobile`),
	FOREIGN KEY(`post_id`) REFERENCES `tb_market_posts`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='员工表';

--
-- 表的结构 `tb_adminstrator_token`
--

CREATE TABLE IF NOT EXISTS `tb_adminstrator_token` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`adminstrator_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '员工id',
	`expire_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT ' 过期时间',
	`create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
	`token` varchar(64) NOT NULL DEFAULT '' COMMENT 'token',
	`device_type` varchar(10) NOT NULL DEFAULT '' COMMENT '设备类型;mobile,android,iphone,ipad,web,pc,mac,wxapp',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='员工客户端登录 token 表';

--
-- 表的结构 `tb_user_level`
--

CREATE TABLE IF NOT EXISTS `tb_user_level` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '等级名称',
	`count` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户数',
	`status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态, 0:禁用，1:启用',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户等级';

--
-- 转存表的数据 `tb_user_level`
--

INSERT INTO `tb_user_level` (`id`,`name`,`count`,`status`) VALUES
('1','level 1','1','1'),
('2','level 2','0','1'),
('3','level 3','0','1'),
('4','level 4','0','1'),
('5','level 5','0','1'),
('6','level 6','0','1'),
('7','level 7','0','1'),
('8','level 8','0','1'),
('9','level 9','0','1'),
('10','level 10','0','1');

-- ---------------------------------------------------

--
-- 表的结构 `tb_user`
-- UNIQUE key `mobile` `user_email` checked in runtime
--

CREATE TABLE IF NOT EXISTS `tb_user` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`name` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户实名',
	`mobile` char(11) NOT NULL DEFAULT '' COMMENT '用户登录手机号',
	`user_pass` varchar(64) NOT NULL DEFAULT '' COMMENT '用户登录密码，cmf_password()加密',
	`user_status` tinyint(3) NOT NULL DEFAULT '2' COMMENT '用户状态, 0:禁用，1:正常, 2:未验证',
	`user_login` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
	`user_email` varchar(100) NOT NULL DEFAULT '' COMMENT '用户登录邮箱',
	`last_login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
	`last_login_time`	int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录时间', 
	`user_activation_key` varchar(60) NOT NULL DEFAULT '' COMMENT '激活码',
	`create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
	`point` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户积分',
	`balance` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '余额',
	`user_nickname` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户昵称',
	`avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
	`sex` tinyint(3) NOT NULL DEFAULT '0' COMMENT '性别, 0:保密, 1:男, 2:女',
	`birthday` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '生日',
	`user_level` int NOT NULL DEFAULT '1' COMMENT '用户等级',
	`more` text COMMENT '扩展属性',
	PRIMARY KEY(`id`),
	UNIQUE KEY(`user_login`),
	FOREIGN KEY(`user_level`) REFERENCES `tb_user_level`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

--
-- 转存表的数据 `tb_user`
-- 密码: 111111
--

INSERT INTO `tb_user`(`id`,`name`,`mobile`,`user_pass`,`user_status`,`user_login`,`user_email`,`last_login_ip`,`last_login_time`,
			`user_activation_key`, `create_time`,`point`,`balance`,`user_nickname`,`avatar`,`sex`,`birthday`,`user_level`) VALUES
('1','NON VIP','17862701356','###f7a5371c3ba3df3d6e492f7de8e64df4','1','111111111111','111111111111@gt.com'
	,'127.0.0.1','0','000000','0','0','0','non vip','','0','0','1');

--
-- 表的结构 `tb_user_login_attempt`
--

CREATE TABLE IF NOT EXISTS `tb_user_login_attempt` (
	`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`login_attempts` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '尝试次数',
	`attempt_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '尝试登录时间',
	`locked_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '锁定时间',
	`ip` varchar(15) NOT NULL DEFAULT '' COMMENT '用户 ip',
	`account` varchar(100) NOT NULL DEFAULT '' COMMENT '用户账号,手机号,邮箱或用户名',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户登录尝试表' ROW_FORMAT=COMPACT;

--
-- 表的结构 `tb_user_token`
--

CREATE TABLE IF NOT EXISTS `tb_user_token` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户id',
	`expire_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT ' 过期时间',
	`create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
	`token` varchar(64) NOT NULL DEFAULT '' COMMENT 'token',
	`device_type` varchar(10) NOT NULL DEFAULT '' COMMENT '设备类型;mobile,android,iphone,ipad,web,pc,mac,wxapp',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户客户端登录 token 表';

--
-- 表的结构 `tb_verification_code`
--

CREATE TABLE IF NOT EXISTS `tb_verification_code` (
	`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '表id',
	`count` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '当天已经发送成功的次数',
	`send_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后发送成功时间',
	`expire_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '验证码过期时间',
	`code` varchar(8) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '最后发送成功的验证码',
	`account` varchar(100) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '手机号或者邮箱',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='手机邮箱数字验证码表';


--
-- 表的结构 `tb_third_party_user`
--

CREATE TABLE IF NOT EXISTS `tb_third_party_user` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '本站用户id',
  `last_login_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `expire_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'access_token过期时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '绑定时间',
  `login_times` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录次数',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态;1:正常;0:禁用',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `third_party` varchar(20) NOT NULL DEFAULT '' COMMENT '第三方惟一码',
  `app_id` varchar(64) NOT NULL DEFAULT '' COMMENT '第三方应用 id',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `access_token` varchar(512) NOT NULL DEFAULT '' COMMENT '第三方授权码',
  `openid` varchar(40) NOT NULL DEFAULT '' COMMENT '第三方用户id',
  `union_id` varchar(64) NOT NULL DEFAULT '' COMMENT '第三方用户多个产品中的惟一 id,(如:微信平台)',
  `more` text COMMENT '扩展信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='第三方用户表';

-- --------------------------------------------------------

--
-- 表的结构 `cmf_user_action_log`
--

CREATE TABLE IF NOT EXISTS `cmf_user_action_log` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户id',
  `count` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '访问次数',
  `last_visit_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后访问时间',
  `object` varchar(100) NOT NULL DEFAULT '' COMMENT '访问对象的id,格式:不带前缀的表名+id;如posts1表示xx_posts表里id为1的记录',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '操作名称;格式:应用名+控制器+操作名,也可自己定义格式只要不发生冲突且惟一;',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '用户ip',
  PRIMARY KEY (`id`),
  KEY `user_object_action` (`user_id`,`object`,`action`),
  KEY `user_object_action_ip` (`user_id`,`object`,`action`,`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='访问记录表';


-- --------------------------------------------------------

--
-- 表的结构 `tb_user_grant`
--

CREATE TABLE IF NOT EXISTS `tb_user_grant` (
	`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '点击授权的用户ID',
	`token` CHAR(32) NOT NULL DEFAULT '' COMMENT '授权字符串',
	`action` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '请求操作',
	`status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '授权状态, 1:等待扫描, 2:已授权, 3:超时关闭授权, 4:已扫描,尚未授权',
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
	`expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '授权过期时间',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户授权请求表';

--
-- 表的结构 `tb_user_pay_shadow` 会员支付密码表
--

CREATE TABLE IF NOT EXISTS `tb_user_pay_shadow`(
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '会员ID',
	`shadow` varchar(255) NOT NULL DEFAULT '' COMMENT '加密后支付密码',
	`modify_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后一次修改的时间',
	PRIMARY KEY(`id`),
	FOREIGN KEY(`user_id`) REFERENCES `tb_user`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT '会员支付密码表';

