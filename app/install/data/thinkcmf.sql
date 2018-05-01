-- ---------------------------------------------------
-- ThinkCMF 基础配置
-- 第一执行
-- ---------------------------------------------------

--
-- 表的结构 `cmf_admin_menu`
--

CREATE TABLE IF NOT EXISTS `cmf_admin_menu` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父菜单id',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '菜单类型;1:有界面可访问菜单,2:无界面可访问菜单,0:只作为菜单',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态;1:显示,0:不显示',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `app` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '应用名',
  `controller` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '控制器名',
  `action` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '操作名称',
  `param` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '额外参数',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '菜单图标',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `parent_id` (`parent_id`),
  KEY `controller` (`controller`)
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=utf8mb4 COMMENT='后台菜单表';

--
-- 转存表中的数据 `cmf_admin_menu`
--

INSERT INTO `tb_admin_menu` (`id`, `parent_id`, `type`, `status`, `list_order`, `app`, `controller`, `action`, `param`, `name`, `icon`, `remark`) VALUES
(1, 0, 0, 1, 20, 'admin', 'Plugin', 'default', '', '插件中心', 'cloud', '插件中心'),
(2, 1, 1, 1, 10000, 'admin', 'Hook', 'index', '', '钩子管理', '', '钩子管理'),
(3, 2, 1, 0, 10000, 'admin', 'Hook', 'plugins', '', '钩子插件管理', '', '钩子插件管理'),
(4, 2, 2, 0, 10000, 'admin', 'Hook', 'pluginListOrder', '', '钩子插件排序', '', '钩子插件排序'),
(5, 2, 1, 0, 10000, 'admin', 'Hook', 'sync', '', '同步钩子', '', '同步钩子'),
(6, 0, 0, 1, 0, 'admin', 'Setting', 'default', '', '设置', 'cogs', '系统设置入口'),
(7, 6, 1, 1, 50, 'admin', 'Link', 'index', '', '友情链接', '', '友情链接管理'),
(8, 7, 1, 0, 10000, 'admin', 'Link', 'add', '', '添加友情链接', '', '添加友情链接'),
(9, 7, 2, 0, 10000, 'admin', 'Link', 'addPost', '', '添加友情链接提交保存', '', '添加友情链接提交保存'),
(10, 7, 1, 0, 10000, 'admin', 'Link', 'edit', '', '编辑友情链接', '', '编辑友情链接'),
(11, 7, 2, 0, 10000, 'admin', 'Link', 'editPost', '', '编辑友情链接提交保存', '', '编辑友情链接提交保存'),
(12, 7, 2, 0, 10000, 'admin', 'Link', 'delete', '', '删除友情链接', '', '删除友情链接'),
(13, 7, 2, 0, 10000, 'admin', 'Link', 'listOrder', '', '友情链接排序', '', '友情链接排序'),
(14, 7, 2, 0, 10000, 'admin', 'Link', 'toggle', '', '友情链接显示隐藏', '', '友情链接显示隐藏'),
(15, 6, 1, 1, 10, 'admin', 'Mailer', 'index', '', '邮箱配置', '', '邮箱配置'),
(16, 15, 2, 0, 10000, 'admin', 'Mailer', 'indexPost', '', '邮箱配置提交保存', '', '邮箱配置提交保存'),
(17, 15, 1, 0, 10000, 'admin', 'Mailer', 'template', '', '邮件模板', '', '邮件模板'),
(18, 15, 2, 0, 10000, 'admin', 'Mailer', 'templatePost', '', '邮件模板提交', '', '邮件模板提交'),
(19, 15, 1, 0, 10000, 'admin', 'Mailer', 'test', '', '邮件发送测试', '', '邮件发送测试'),
(20, 6, 1, 0, 10000, 'admin', 'Menu', 'index', '', '后台菜单', '', '后台菜单管理'),
(21, 20, 1, 0, 10000, 'admin', 'Menu', 'lists', '', '所有菜单', '', '后台所有菜单列表'),
(22, 20, 1, 0, 10000, 'admin', 'Menu', 'add', '', '后台菜单添加', '', '后台菜单添加'),
(23, 20, 2, 0, 10000, 'admin', 'Menu', 'addPost', '', '后台菜单添加提交保存', '', '后台菜单添加提交保存'),
(24, 20, 1, 0, 10000, 'admin', 'Menu', 'edit', '', '后台菜单编辑', '', '后台菜单编辑'),
(25, 20, 2, 0, 10000, 'admin', 'Menu', 'editPost', '', '后台菜单编辑提交保存', '', '后台菜单编辑提交保存'),
(26, 20, 2, 0, 10000, 'admin', 'Menu', 'delete', '', '后台菜单删除', '', '后台菜单删除'),
(27, 20, 2, 0, 10000, 'admin', 'Menu', 'listOrder', '', '后台菜单排序', '', '后台菜单排序'),
(28, 20, 1, 0, 10000, 'admin', 'Menu', 'getActions', '', '导入新后台菜单', '', '导入新后台菜单'),
(29, 6, 1, 1, 30, 'admin', 'Nav', 'index', '', '导航管理', '', '导航管理'),
(30, 29, 1, 0, 10000, 'admin', 'Nav', 'add', '', '添加导航', '', '添加导航'),
(31, 29, 2, 0, 10000, 'admin', 'Nav', 'addPost', '', '添加导航提交保存', '', '添加导航提交保存'),
(32, 29, 1, 0, 10000, 'admin', 'Nav', 'edit', '', '编辑导航', '', '编辑导航'),
(33, 29, 2, 0, 10000, 'admin', 'Nav', 'editPost', '', '编辑导航提交保存', '', '编辑导航提交保存'),
(34, 29, 2, 0, 10000, 'admin', 'Nav', 'delete', '', '删除导航', '', '删除导航'),
(35, 29, 1, 0, 10000, 'admin', 'NavMenu', 'index', '', '导航菜单', '', '导航菜单'),
(36, 35, 1, 0, 10000, 'admin', 'NavMenu', 'add', '', '添加导航菜单', '', '添加导航菜单'),
(37, 35, 2, 0, 10000, 'admin', 'NavMenu', 'addPost', '', '添加导航菜单提交保存', '', '添加导航菜单提交保存'),
(38, 35, 1, 0, 10000, 'admin', 'NavMenu', 'edit', '', '编辑导航菜单', '', '编辑导航菜单'),
(39, 35, 2, 0, 10000, 'admin', 'NavMenu', 'editPost', '', '编辑导航菜单提交保存', '', '编辑导航菜单提交保存'),
(40, 35, 2, 0, 10000, 'admin', 'NavMenu', 'delete', '', '删除导航菜单', '', '删除导航菜单'),
(41, 35, 2, 0, 10000, 'admin', 'NavMenu', 'listOrder', '', '导航菜单排序', '', '导航菜单排序'),
(42, 1, 1, 1, 10000, 'admin', 'Plugin', 'index', '', '插件列表', '', '插件列表'),
(43, 42, 2, 0, 10000, 'admin', 'Plugin', 'toggle', '', '插件启用禁用', '', '插件启用禁用'),
(44, 42, 1, 0, 10000, 'admin', 'Plugin', 'setting', '', '插件设置', '', '插件设置'),
(45, 42, 2, 0, 10000, 'admin', 'Plugin', 'settingPost', '', '插件设置提交', '', '插件设置提交'),
(46, 42, 2, 0, 10000, 'admin', 'Plugin', 'install', '', '插件安装', '', '插件安装'),
(47, 42, 2, 0, 10000, 'admin', 'Plugin', 'update', '', '插件更新', '', '插件更新'),
(48, 42, 2, 0, 10000, 'admin', 'Plugin', 'uninstall', '', '卸载插件', '', '卸载插件'),
(49, 0, 1, 0, 10000, 'admin', 'RecycleBin', 'index', '', '回收站', '', '回收站'),
(50, 49, 2, 0, 10000, 'admin', 'RecycleBin', 'restore', '', '回收站还原', '', '回收站还原'),
(51, 49, 2, 0, 10000, 'admin', 'RecycleBin', 'delete', '', '回收站彻底删除', '', '回收站彻底删除'),
(52, 6, 1, 1, 10000, 'admin', 'Route', 'index', '', 'URL美化', '', 'URL规则管理'),
(53, 52, 1, 0, 10000, 'admin', 'Route', 'add', '', '添加路由规则', '', '添加路由规则'),
(54, 52, 2, 0, 10000, 'admin', 'Route', 'addPost', '', '添加路由规则提交', '', '添加路由规则提交'),
(55, 52, 1, 0, 10000, 'admin', 'Route', 'edit', '', '路由规则编辑', '', '路由规则编辑'),
(56, 52, 2, 0, 10000, 'admin', 'Route', 'editPost', '', '路由规则编辑提交', '', '路由规则编辑提交'),
(57, 52, 2, 0, 10000, 'admin', 'Route', 'delete', '', '路由规则删除', '', '路由规则删除'),
(58, 52, 2, 0, 10000, 'admin', 'Route', 'ban', '', '路由规则禁用', '', '路由规则禁用'),
(59, 52, 2, 0, 10000, 'admin', 'Route', 'open', '', '路由规则启用', '', '路由规则启用'),
(60, 52, 2, 0, 10000, 'admin', 'Route', 'listOrder', '', '路由规则排序', '', '路由规则排序'),
(61, 52, 1, 0, 10000, 'admin', 'Route', 'select', '', '选择URL', '', '选择URL'),
(62, 6, 1, 1, 0, 'admin', 'Setting', 'site', '', '网站信息', '', '网站信息'),
(63, 62, 2, 0, 10000, 'admin', 'Setting', 'sitePost', '', '网站信息设置提交', '', '网站信息设置提交'),
(64, 6, 1, 0, 10000, 'admin', 'Setting', 'password', '', '密码修改', '', '密码修改'),
(65, 64, 2, 0, 10000, 'admin', 'Setting', 'passwordPost', '', '密码修改提交', '', '密码修改提交'),
(66, 6, 1, 1, 10000, 'admin', 'Setting', 'upload', '', '上传设置', '', '上传设置'),
(67, 66, 2, 0, 10000, 'admin', 'Setting', 'uploadPost', '', '上传设置提交', '', '上传设置提交'),
(68, 6, 1, 0, 10000, 'admin', 'Setting', 'clearCache', '', '清除缓存', '', '清除缓存'),
(69, 6, 1, 1, 40, 'admin', 'Slide', 'index', '', '幻灯片管理', '', '幻灯片管理'),
(70, 69, 1, 0, 10000, 'admin', 'Slide', 'add', '', '添加幻灯片', '', '添加幻灯片'),
(71, 69, 2, 0, 10000, 'admin', 'Slide', 'addPost', '', '添加幻灯片提交', '', '添加幻灯片提交'),
(72, 69, 1, 0, 10000, 'admin', 'Slide', 'edit', '', '编辑幻灯片', '', '编辑幻灯片'),
(73, 69, 2, 0, 10000, 'admin', 'Slide', 'editPost', '', '编辑幻灯片提交', '', '编辑幻灯片提交'),
(74, 69, 2, 0, 10000, 'admin', 'Slide', 'delete', '', '删除幻灯片', '', '删除幻灯片'),
(75, 69, 1, 0, 10000, 'admin', 'SlideItem', 'index', '', '幻灯片页面列表', '', '幻灯片页面列表'),
(76, 75, 1, 0, 10000, 'admin', 'SlideItem', 'add', '', '幻灯片页面添加', '', '幻灯片页面添加'),
(77, 75, 2, 0, 10000, 'admin', 'SlideItem', 'addPost', '', '幻灯片页面添加提交', '', '幻灯片页面添加提交'),
(78, 75, 1, 0, 10000, 'admin', 'SlideItem', 'edit', '', '幻灯片页面编辑', '', '幻灯片页面编辑'),
(79, 75, 2, 0, 10000, 'admin', 'SlideItem', 'editPost', '', '幻灯片页面编辑提交', '', '幻灯片页面编辑提交'),
(80, 75, 2, 0, 10000, 'admin', 'SlideItem', 'delete', '', '幻灯片页面删除', '', '幻灯片页面删除'),
(81, 75, 2, 0, 10000, 'admin', 'SlideItem', 'ban', '', '幻灯片页面隐藏', '', '幻灯片页面隐藏'),
(82, 75, 2, 0, 10000, 'admin', 'SlideItem', 'cancelBan', '', '幻灯片页面显示', '', '幻灯片页面显示'),
(83, 75, 2, 0, 10000, 'admin', 'SlideItem', 'listOrder', '', '幻灯片页面排序', '', '幻灯片页面排序'),
(84, 6, 1, 1, 10000, 'admin', 'Storage', 'index', '', '文件存储', '', '文件存储'),
(85, 84, 2, 0, 10000, 'admin', 'Storage', 'settingPost', '', '文件存储设置提交', '', '文件存储设置提交'),
(86, 6, 1, 1, 20, 'admin', 'Theme', 'index', '', '模板管理', '', '模板管理'),
(87, 86, 1, 0, 10000, 'admin', 'Theme', 'install', '', '安装模板', '', '安装模板'),
(88, 86, 2, 0, 10000, 'admin', 'Theme', 'uninstall', '', '卸载模板', '', '卸载模板'),
(89, 86, 2, 0, 10000, 'admin', 'Theme', 'installTheme', '', '模板安装', '', '模板安装'),
(90, 86, 2, 0, 10000, 'admin', 'Theme', 'update', '', '模板更新', '', '模板更新'),
(91, 86, 2, 0, 10000, 'admin', 'Theme', 'active', '', '启用模板', '', '启用模板'),
(92, 86, 1, 0, 10000, 'admin', 'Theme', 'files', '', '模板文件列表', '', '启用模板'),
(93, 86, 1, 0, 10000, 'admin', 'Theme', 'fileSetting', '', '模板文件设置', '', '模板文件设置'),
(94, 86, 1, 0, 10000, 'admin', 'Theme', 'fileArrayData', '', '模板文件数组数据列表', '', '模板文件数组数据列表'),
(95, 86, 2, 0, 10000, 'admin', 'Theme', 'fileArrayDataEdit', '', '模板文件数组数据添加编辑', '', '模板文件数组数据添加编辑'),
(96, 86, 2, 0, 10000, 'admin', 'Theme', 'fileArrayDataEditPost', '', '模板文件数组数据添加编辑提交保存', '', '模板文件数组数据添加编辑提交保存'),
(97, 86, 2, 0, 10000, 'admin', 'Theme', 'fileArrayDataDelete', '', '模板文件数组数据删除', '', '模板文件数组数据删除'),
(98, 86, 2, 0, 10000, 'admin', 'Theme', 'settingPost', '', '模板文件编辑提交保存', '', '模板文件编辑提交保存'),
(99, 86, 1, 0, 10000, 'admin', 'Theme', 'dataSource', '', '模板文件设置数据源', '', '模板文件设置数据源'),
(100, 0, 0, 1, 60, 'admin', 'Adminstrator', 'default', '', '部门管理', 'group', '部门管理'),
(101, 100, 1, 1, 70, 'admin', 'Rbac', 'index', '', '角色管理', '', '角色管理'),
(102, 101, 1, 0, 10000, 'admin', 'Rbac', 'roleAdd', '', '添加角色', '', '添加角色'),
(103, 101, 2, 0, 10000, 'admin', 'Rbac', 'roleAddPost', '', '添加角色提交', '', '添加角色提交'),
(104, 101, 1, 0, 10000, 'admin', 'Rbac', 'roleEdit', '', '编辑角色', '', '编辑角色'),
(105, 101, 2, 0, 10000, 'admin', 'Rbac', 'roleEditPost', '', '编辑角色提交', '', '编辑角色提交'),
(106, 101, 2, 0, 10000, 'admin', 'Rbac', 'roleDelete', '', '删除角色', '', '删除角色'),
(107, 101, 1, 0, 10000, 'admin', 'Rbac', 'authorize', '', '设置角色权限', '', '设置角色权限'),
(108, 101, 2, 0, 10000, 'admin', 'Rbac', 'authorizePost', '', '角色授权提交', '', '角色授权提交'),
(109, 100, 1, 1, 90, 'admin', 'Adminstrator', 'index', '', '员工管理', '', '员工管理'),
(110, 109, 1, 0, 10000, 'admin', 'Adminstrator', 'add', '', '添加员工', '', '添加员工'),
(111, 109, 2, 0, 10000, 'admin', 'Adminstrator', 'addPost', '', '添加员工提交', '', '添加员工提交'),
(112, 109, 1, 0, 10000, 'admin', 'Adminstrator', 'edit', '', '编辑员工', '', '编辑员工'),
(113, 109, 2, 0, 10000, 'admin', 'Adminstrator', 'editPost', '', '编辑员工提交', '', '编辑员工提交'),
(114, 109, 1, 0, 10000, 'admin', 'Adminstrator', 'userInfo', '', '个人信息', '', '员工个人信息修改'),
(115, 109, 2, 0, 10000, 'admin', 'Adminstrator', 'userInfoPost', '', '员工个人信息修改提交', '', '员工个人信息修改提交'),
(116, 109, 2, 0, 10000, 'admin', 'Adminstrator', 'delete', '', '删除员工', '', '删除员工'),
(117, 109, 2, 0, 10000, 'admin', 'Adminstrator', 'ban', '', '停用员工', '', '停用员工'),
(118, 109, 2, 0, 10000, 'admin', 'Adminstrator', 'cancelBan', '', '启用员工', '', '启用员工'),
(119, 0, 1, 0, 350, 'user', 'AdminAsset', 'index', '', '资源管理', 'file', '资源管理列表'),
(120, 119, 2, 0, 10000, 'user', 'AdminAsset', 'delete', '', '删除文件', '', '删除文件'),
(121, 0, 0, 1, 100, 'user', 'AdminIndex', 'default', '', '会员管理', 'group', '会员管理'),
(122, 121, 1, 1, 110, 'user', 'AdminIndex', 'index', '', '本站会员', '', '本站会员'),
(123, 122, 2, 0, 10000, 'user', 'AdminIndex', 'ban', '', '本站会员拉黑', '', '本站会员拉黑'),
(124, 122, 2, 0, 10000, 'user', 'AdminIndex', 'cancelBan', '', '本站会员启用', '', '本站会员启用'),
(125, 121, 1, 1, 120, 'user', 'AdminOauth', 'index', '', '第三方用户', '', '第三方用户'),
(126, 125, 2, 0, 10000, 'user', 'AdminOauth', 'delete', '', '删除第三方用户绑定', '', '删除第三方用户绑定'),
(127, 6, 1, 1, 130, 'user', 'AdminUserAction', 'index', '', '用户操作管理', '', '用户操作管理'),
(128, 127, 1, 0, 10000, 'user', 'AdminUserAction', 'edit', '', '编辑用户操作', '', '编辑用户操作'),
(129, 127, 2, 0, 10000, 'user', 'AdminUserAction', 'editPost', '', '编辑用户操作提交', '', '编辑用户操作提交'),
(130, 127, 1, 0, 10000, 'user', 'AdminUserAction', 'sync', '', '同步用户操作', '', '同步用户操作'),
(131, 100, 1, 1, 80, 'market', 'AdminMarketPost','index','', '岗位管理', '', '岗位列表'),
(132, 131, 1, 0, 10000, 'market', 'AdminMarketPost','add','', '添加岗位', '', '添加岗位'),
(133, 131, 2, 0, 10000, 'market', 'AdminMarketPost','addPost','', '添加岗位提交', '', '添加岗位提交'),
(134, 131, 1, 0, 10000, 'market', 'AdminMarketPost','edit','', '编辑岗位', '', '编辑岗位'),
(135, 131, 2, 0, 10000, 'market', 'AdminMarketPost','editPost','', '编辑岗位提交', '', '编辑岗位提交'),
(136, 131, 2, 0, 10000, 'market', 'AdminMarketPost','delete','', '删除岗位', '', '删除岗位'),
(137, 0, 0, 1, 140, 'market', 'AdminMarketAccount','default','', '财务管理', '', '财务管理主页'),
(138, 137, 1, 0, 150, 'market', 'AdminMarketAccountType', 'index', '', '类型管理', '', '类型管理'),
(139, 138, 1, 0, 10000, 'market', 'AdminMarketAccountType', 'add', '', '添加类型', '', '添加类型'),
(140, 138, 2, 0, 10000, 'market', 'AdminMarketAccountType', 'addPost', '','添加类型提交', '', '添加类型提交'),
(141, 138, 1, 0, 10000, 'market', 'AdminMarketAccountType', 'edit', '', '编辑类型', '', '编辑类型'),
(142, 138, 2, 0, 10000, 'market', 'AdminMarketAccountType', 'editPost', '', '编辑类型提交', '', '编辑类型提交'),
(143, 138, 2, 0, 10000, 'market', 'AdminMarketAccountType', 'delete', '', '删除类型', '', '删除类型'),
(144, 137, 1, 1, 160, 'market', 'AdminMarketAccount', 'index', '', '账单', '', '账单'),
(145, 144, 1, 0, 10000, 'market', 'AdminMarketAccount', 'add','', '新增账务', '', '新增账务'),
(146, 144, 2, 0, 10000, 'market', 'AdminMarketAccount', 'addPost', '', '新增账务提交', '', '新增账务提交'),
(147, 137, 1, 0, 170, 'market', 'AdminMarketCheckIn', 'index','', '签到管理', '', '签到列表'),
(148, 147, 1, 0, 10000, 'market', 'AdminMarketCheckIn','add','', '添加签到', '', '添加签到'),
(149, 147, 2, 0, 10000, 'market', 'AdminMarketCheckIn','addPost','', '添加签到提交', '', '添加签到提交'),
(150, 0, 0, 1, 180, 'market', 'AdminMarketStore', 'default', '', '店铺终端管理', '', '店铺终端管理'),
(151, 150, 1, 1, 190, 'market', 'AdminMarketStore', 'index', '', '店铺管理', '', '店铺管理主页'),
(152, 151, 1, 0, 10000, 'market', 'AdminMarketStore', 'add', '', '添加店铺', '', '添加店铺'),
(153, 151, 2, 0, 10000, 'market', 'AdminMarketStore', 'addPost', '', '添加店铺提交', '', '添加店铺提交'),
(154, 151, 1, 0, 10000, 'market', 'AdminMarketStore', 'edit', '', '编辑店铺', '', '编辑店铺'),
(155, 151, 2, 0, 10000, 'market', 'AdminMarketStore', 'editPost', '', '编辑店铺提交', '', '编辑店铺提交'),
(156, 151, 2, 0, 10000, 'market', 'AdminMarketStore', 'delete', '', '删除店铺', '', '删除店铺'),
(157, 151, 2, 0, 10000, 'market', 'AdminMarketStore', 'close', '', '店铺打烊', '', '店铺打烊'),
(158, 151, 2, 0, 10000, 'market', 'AdminMarketStore', 'open', '', '店铺营业', '', '编辑营业'),
(159, 151, 2, 0, 10000, 'market', 'AdminMarketStore', 'permanentClose', '', '永久关闭店铺', '', '永久关闭店铺'),
(160, 150, 1, 1, 200, 'market', 'AdminMarketTerminal', 'index', '', '自助终端管理', '', '自助终端管理主页'),
(161, 160, 1, 0, 10000, 'market', 'AdminMarketTerminal', 'add', '', '添加终端', '', '添加终端'),
(162, 160, 2, 0, 10000, 'market', 'AdminMarketTerminal', 'addPost', '', '添加终端提交', '', '添加终端提交'),
(163, 160, 1, 0, 10000, 'market', 'AdminMarketTerminal', 'edit', '', '编辑终端', '', '编辑终端'),
(164, 160, 2, 0, 10000, 'market', 'AdminMarketTerminal', 'editPost', '', '编辑终端提交', '', '编辑终端提交'),
(165, 160, 2, 0, 10000, 'market', 'AdminMarketTerminal', 'delete', '', 'delete终端', '', 'delete终端'),
(166, 160, 2, 0, 10000, 'market', 'AdminMarketTerminal', 'suspend', '', '停用终端', '', '停用终端'),
(167, 160, 2, 0, 10000, 'market', 'AdminMarketTerminal', 'cancelsuspend', '', '启用终端', '', '启用终端'),
(168, 0, 0, 1, 220, 'market', 'AdminMarketProvider', 'default', '', '进货管理', '', '进货管理'),
(169, 168, 1, 1, 230, 'market', 'AdminMarketProvider', 'default1', '', '供应管理', '', '供应商管理'),
(170, 169, 1, 1, 240, 'market', 'AdminMarketProvider', 'index', '', '供应商管理', '', '供应商管理'),
(171, 170, 1, 0, 10000, 'market', 'AdminMarketProvider', 'add', '', '添加供应商', '', '添加供应商'),
(172, 170, 2, 0, 10000, 'market', 'AdminMarketProvider', 'addPost', '', '添加供应商提交', '', '添加供应商提交'),
(173, 170, 1, 0, 10000, 'market', 'AdminMarketProvider', 'edit', '', '编辑供应商', '', '编辑供应商'),
(174, 170, 2, 0, 10000, 'market', 'AdminMarketProvider', 'editPost', '', '编辑供应商提交', '', '编辑供应商提交'),
(175, 170, 2, 0, 10000, 'market', 'AdminMarketProvider', 'delete', '', '删除供应商', '', '删除供应商'),
(176, 169, 1, 1, 250, 'market', 'AdminMarketProviderGoods', 'index', '', '供应商品管理', '', '供应商品管理'),
(177, 176, 1, 0, 10000, 'market', 'AdminMarketProviderGoods', 'add', '', '添加供应商品', '', '添加供应商品'),
(178, 176, 2,0, 10000, 'market', 'AdminMarketProviderGoods', 'addPost', '', '添加供应商品提交', '', '添加供应商品提交'),
(179, 176, 1, 0, 10000, 'market', 'AdminMarketProviderGoods', 'edit', '', '编辑供应商品', '', '编辑供应商品'),
(180, 176, 2, 0, 10000, 'market', 'AdminMarketProviderGoods', 'editPost', '', '编辑供应商品提交', '', '编辑供应商品提交'),
(181 , 168, 2, 1, 260, 'market', 'AdminMarketGoods', 'default', '', '商品管理', '', '商品管理'),
(182 , 181, 1, 1, 270, 'market', 'AdminMarketGoods', 'indexGoodsType', '', '商品类管理', '', '商品类管理'),
(183 , 182, 1, 0, 10000, 'market', 'AdminMarketGoods', 'addGoodsType', '', '添加商品类', '', '添加商品类'),
(184 , 182, 2, 0, 10000, 'market', 'AdminMarketGoods', 'addGoodsTypePost', '', '添加商品类提交', '', '添加商品类提交'),
(185 , 182, 1, 0, 10000, 'market', 'AdminMarketGoods', 'editGoodsType', '', '编辑商品类', '', '编辑商品类'),
(186 , 182, 2, 0, 10000, 'market', 'AdminMarketGoods', 'editGoodsTypePost', '', '编辑商品类提交', '', '编辑商品类提交'),
(187 , 182, 1, 0, 10000, 'market', 'AdminMarketGoods', 'deleteGoodsType', '', '下架商品类', '', '下架商品类'),
(188 , 181, 1, 1, 280, 'market', 'AdminMarketGoods', 'indexGoods', '', '商品管理', '', '商品管理'),
(189 , 188, 1, 0, 10000, 'market', 'AdminMarketGoods', 'addGoods', '', '添加商品', '', '添加商品'),
(190 , 188, 2, 0, 10000, 'market', 'AdminMarketGoods', 'addGoodsPost', '', '添加商品提交', '', '添加商品提交'),
(191 , 188, 1, 0, 10000, 'market', 'AdminMarketGoods', 'editGoods', '', '编辑商品', '', '编辑商品'),
(192 , 188, 2, 0, 10000, 'market', 'AdminMarketGoods', 'editGoodsPost', '', '编辑商品提交', '', '编辑商品提交'),
(193 , 188, 1, 0, 10000, 'market', 'AdminMarketGoods', 'deleteGoods', '', '下架商品', '', '下架商品'),
(194, 0, 0, 1, 290, 'market', 'AdminMarketSale', 'default', '', '销售管理', '', '销售管理'),
(195, 194, 1, 1, 300, 'market', 'AdminMarketSaleDiscount', 'index', '', '优惠管理', '', '优惠管理'),
(196, 195, 1, 0, 10000, 'market', 'AdminMarketSaleDiscount', 'add', '', '添加优惠', '', '添加优惠'),
(197, 195, 2, 0, 10000, 'market', 'AdminMarketSaleDiscount', 'addPost', '', '添加优惠提交', '', '添加优惠提交'),
(198, 195, 1, 0, 10000, 'market', 'AdminMarketSaleDiscount', 'edit', '', '编辑优惠', '', '编辑优惠'),
(199, 195, 2, 0, 10000, 'market', 'AdminMarketSaleDiscount', 'editPost', '', '编辑优惠提交', '', '编辑优惠提交'),
(200, 195, 1, 0, 10000, 'market', 'AdminMarketSaleDiscount', 'delete', '', '删除优惠', '', '删除优惠'),
(201, 194, 1, 1, 310, 'market', 'AdminMarketSaleDiscountGoods', 'index', '', '优惠商品管理', '', '优惠商品管理'),
(202, 201, 1, 0, 10000, 'market', 'AdminMarketSaleDiscountGoods', 'add', '', '添加优惠商品 ', '', '添加优惠商品'),
(203, 201, 2, 0, 10000, 'market', 'AdminMarketSaleDiscountGoods', 'addPost', '', '添加优惠商品提交', '', '添加优惠商品提交'),
(204, 201, 1, 0, 10000, 'market', 'AdminMarketSaleDiscountGoods', 'edit', '', '编辑优惠商品', '', '编辑优惠商品'),
(205, 201, 2, 0, 10000, 'market', 'AdminMarketSaleDiscountGoods', 'editPost', '', '编辑优惠商品提交', '', '编辑优惠商品提交'),
(206, 201, 1, 0, 10000, 'market', 'AdminMarketSaleDiscountGoods', 'delete', '', '删除优惠商品', '', '删除优惠商品'),
(207, 194, 1, 1, 320, 'market', 'AdminMarketSale', 'index', '', '交易管理', '', '交易管理'),
(208, 207, 0, 0, 10000, 'market', 'AdminMarketSale', 'analysis', '', '销售统计分析', '', '销售统计分析'),
(209, 207, 0, 0, 10000, 'market', 'AdminMarketSale', 'storeSaleDetail', '', '店铺销售详情', '', '店铺销售详情'),
(210, 194, 1, 0, 330, 'market', 'AdminMarketSale', 'cartIndex', '', '购物车管理', '', '购物车管理'),
(211, 210, 1, 0, 10000, 'market', 'AdminMarketSale', 'cartCancel', '', '取消购物车', '', '取消购物车'),
(212, 0, 0, 1, 340, 'portal', 'AdminIndex', 'default', '', '门户管理', 'th', '门户管理'),
(213, 212, 1, 1, 10000, 'portal', 'AdminArticle', 'index', '', '文章管理', '', '文章列表'),
(214, 213, 1, 0, 10000, 'portal', 'AdminArticle', 'add', '', '添加文章', '', '添加文章'),
(215, 213, 2, 0, 10000, 'portal', 'AdminArticle', 'addPost', '', '添加文章提交', '', '添加文章提交'),
(216, 213, 1, 0, 10000, 'portal', 'AdminArticle', 'edit', '', '编辑文章', '', '编辑文章'),
(217, 213, 2, 0, 10000, 'portal', 'AdminArticle', 'editPost', '', '编辑文章提交', '', '编辑文章提交'),
(218, 213, 2, 0, 10000, 'portal', 'AdminArticle', 'delete', '', '文章删除', '', '文章删除'),
(219, 213, 2, 0, 10000, 'portal', 'AdminArticle', 'publish', '', '文章发布', '', '文章发布'),
(220, 213, 2, 0, 10000, 'portal', 'AdminArticle', 'top', '', '文章置顶', '', '文章置顶'),
(221, 213, 2, 0, 10000, 'portal', 'AdminArticle', 'recommend', '', '文章推荐', '', '文章推荐'),
(222, 213, 2, 0, 10000, 'portal', 'AdminArticle', 'listOrder', '', '文章排序', '', '文章排序'),
(223, 212, 1, 1, 10000, 'portal', 'AdminCategory', 'index', '', '分类管理', '', '文章分类列表'),
(224, 223, 1, 0, 10000, 'portal', 'AdminCategory', 'add', '', '添加文章分类', '', '添加文章分类'),
(225, 223, 2, 0, 10000, 'portal', 'AdminCategory', 'addPost', '', '添加文章分类提交', '', '添加文章分类提交'),
(226, 223, 1, 0, 10000, 'portal', 'AdminCategory', 'edit', '', '编辑文章分类', '', '编辑文章分类'),
(227, 223, 2, 0, 10000, 'portal', 'AdminCategory', 'editPost', '', '编辑文章分类提交', '', '编辑文章分类提交'),
(228, 223, 1, 0, 10000, 'portal', 'AdminCategory', 'select', '', '文章分类选择对话框', '', '文章分类选择对话框'),
(229, 223, 2, 0, 10000, 'portal', 'AdminCategory', 'listOrder', '', '文章分类排序', '', '文章分类排序'),
(230, 223, 2, 0, 10000, 'portal', 'AdminCategory', 'delete', '', '删除文章分类', '', '删除文章分类'),
(231, 212, 1, 1, 10000, 'portal', 'AdminPage', 'index', '', '页面管理', '', '页面管理'),
(232, 231, 1, 0, 10000, 'portal', 'AdminPage', 'add', '', '添加页面', '', '添加页面'),
(233, 231, 2, 0, 10000, 'portal', 'AdminPage', 'addPost', '', '添加页面提交', '', '添加页面提交'),
(234, 231, 1, 0, 10000, 'portal', 'AdminPage', 'edit', '', '编辑页面', '', '编辑页面'),
(235, 231, 2, 0, 10000, 'portal', 'AdminPage', 'editPost', '', '编辑页面提交', '', '编辑页面提交'),
(236, 231, 2, 0, 10000, 'portal', 'AdminPage', 'delete', '', '删除页面', '', '删除页面'),
(237, 212, 1, 1, 10000, 'portal', 'AdminTag', 'index', '', '文章标签', '', '文章标签'),
(238, 237, 1, 0, 10000, 'portal', 'AdminTag', 'add', '', '添加文章标签', '', '添加文章标签'),
(239, 237, 2, 0, 10000, 'portal', 'AdminTag', 'addPost', '', '添加文章标签提交', '', '添加文章标签提交'),
(240, 237, 2, 0, 10000, 'portal', 'AdminTag', 'upStatus', '', '更新标签状态', '', '更新标签状态'),
(241, 237, 2, 0, 10000, 'portal', 'AdminTag', 'delete', '', '删除文章标签', '', '删除文章标签');

-- --------------------------------------------------------

--
-- 表的结构 `cmf_asset`
--

CREATE TABLE IF NOT EXISTS `cmf_asset` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户id',
  `file_size` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文件大小,单位B',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上传时间',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态;1:可用,0:不可用',
  `download_times` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '下载次数',
  `file_key` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件惟一码',
  `filename` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名',
  `file_path` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件路径,相对于upload目录,可以为url',
  `file_md5` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件md5值',
  `file_sha1` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `suffix` varchar(10) NOT NULL DEFAULT '' COMMENT '文件后缀名,不包括点',
  `more` text COMMENT '其它详细信息,JSON格式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='资源表';

-- --------------------------------------------------------

--
-- 表的结构 `cmf_hook`
--

CREATE TABLE IF NOT EXISTS `cmf_hook` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '钩子类型(1:系统钩子;2:应用钩子;3:模板钩子;4:后台模板钩子)',
  `once` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否只允许一个插件运行(0:多个;1:一个)',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `hook` varchar(50) NOT NULL DEFAULT '' COMMENT '钩子',
  `app` varchar(15) NOT NULL DEFAULT '' COMMENT '应用名(只有应用钩子才用)',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COMMENT='系统钩子表';

--
-- 转存表中的数据 `cmf_hook`
--

INSERT INTO `cmf_hook` (`id`, `type`, `once`, `name`, `hook`, `app`, `description`) VALUES
(1, 1, 0, '应用初始化', 'app_init', 'cmf', '应用初始化'),
(2, 1, 0, '应用开始', 'app_begin', 'cmf', '应用开始'),
(3, 1, 0, '模块初始化', 'module_init', 'cmf', '模块初始化'),
(4, 1, 0, '控制器开始', 'action_begin', 'cmf', '控制器开始'),
(5, 1, 0, '视图输出过滤', 'view_filter', 'cmf', '视图输出过滤'),
(6, 1, 0, '应用结束', 'app_end', 'cmf', '应用结束'),
(7, 1, 0, '日志write方法', 'log_write', 'cmf', '日志write方法'),
(8, 1, 0, '输出结束', 'response_end', 'cmf', '输出结束'),
(9, 1, 0, '后台控制器初始化', 'admin_init', 'cmf', '后台控制器初始化'),
(10, 1, 0, '前台控制器初始化', 'home_init', 'cmf', '前台控制器初始化'),
(11, 1, 1, '发送手机验证码', 'send_mobile_verification_code', 'cmf', '发送手机验证码'),
(12, 3, 0, '模板 body标签开始', 'body_start', '', '模板 body标签开始'),
(13, 3, 0, '模板 head标签结束前', 'before_head_end', '', '模板 head标签结束前'),
(14, 3, 0, '模板底部开始', 'footer_start', '', '模板底部开始'),
(15, 3, 0, '模板底部开始之前', 'before_footer', '', '模板底部开始之前'),
(16, 3, 0, '模板底部结束之前', 'before_footer_end', '', '模板底部结束之前'),
(17, 3, 0, '模板 body 标签结束之前', 'before_body_end', '', '模板 body 标签结束之前'),
(18, 3, 0, '模板左边栏开始', 'left_sidebar_start', '', '模板左边栏开始'),
(19, 3, 0, '模板左边栏结束之前', 'before_left_sidebar_end', '', '模板左边栏结束之前'),
(20, 3, 0, '模板右边栏开始', 'right_sidebar_start', '', '模板右边栏开始'),
(21, 3, 0, '模板右边栏结束之前', 'before_right_sidebar_end', '', '模板右边栏结束之前'),
(22, 3, 1, '评论区', 'comment', '', '评论区'),
(23, 3, 1, '留言区', 'guestbook', '', '留言区'),
(24, 2, 0, '后台首页仪表盘', 'admin_dashboard', 'admin', '后台首页仪表盘'),
(25, 4, 0, '后台模板 head标签结束前', 'admin_before_head_end', '', '后台模板 head标签结束前'),
(26, 4, 0, '后台模板 body 标签结束之前', 'admin_before_body_end', '', '后台模板 body 标签结束之前'),
(27, 2, 0, '后台登录页面', 'admin_login', 'admin', '后台登录页面'),
(28, 1, 1, '前台模板切换', 'switch_theme', 'cmf', '前台模板切换'),
(29, 3, 0, '主要内容之后', 'after_content', '', '主要内容之后'),
(30, 2, 0, '文章显示之前', 'portal_before_assign_article', 'portal', '文章显示之前'),
(31, 2, 0, '后台文章保存之后', 'portal_admin_after_save_article', 'portal', '后台文章保存之后'),
(32, 2, 1, '获取上传界面', 'fetch_upload_view', 'user', '获取上传界面'),
(33, 3, 0, '主要内容之前', 'before_content', 'cmf', '主要内容之前'),
(34, 1, 0, '日志写入完成', 'log_write_done', 'cmf', '日志写入完成'),
(35, 1, 1, '后台模板切换', 'switch_admin_theme', 'cmf', '后台模板切换');

-- --------------------------------------------------------

--
-- 表的结构 `cmf_hook_plugin`
--

CREATE TABLE IF NOT EXISTS `cmf_hook_plugin` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用)',
  `hook` varchar(50) NOT NULL DEFAULT '' COMMENT '钩子名',
  `plugin` varchar(50) NOT NULL DEFAULT '' COMMENT '插件',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统钩子插件表';

-- --------------------------------------------------------

--
-- 表的结构 `cmf_link`
--

CREATE TABLE IF NOT EXISTS `cmf_link` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态;1:显示;0:不显示',
  `rating` int(11) NOT NULL DEFAULT '0' COMMENT '友情链接评级',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '友情链接描述',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '友情链接地址',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '友情链接名称',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '友情链接图标',
  `target` varchar(10) NOT NULL DEFAULT '' COMMENT '友情链接打开方式',
  `rel` varchar(50) NOT NULL DEFAULT '' COMMENT '链接与网站的关系',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='友情链接表';

--
-- 转存表中的数据 `cmf_link`
--

INSERT INTO `cmf_link` (`id`, `status`, `rating`, `list_order`, `description`, `url`, `name`, `image`, `target`, `rel`) VALUES
(1, 1, 1, 8, 'thinkcmf官网', 'http://www.thinkcmf.com', 'ThinkCMF', '', '_blank', '');

-- --------------------------------------------------------

--
-- 表的结构 `cmf_nav`
--

CREATE TABLE IF NOT EXISTS `cmf_nav` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `is_main` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否为主导航;1:是;0:不是',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '导航位置名称',
  `remark` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='前台导航位置表';

--
-- 转存表中的数据 `cmf_nav`
--

INSERT INTO `cmf_nav` (`id`, `is_main`, `name`, `remark`) VALUES
(1, 1, '主导航', '主导航'),
(2, 0, '底部导航', '');

-- --------------------------------------------------------

--
-- 表的结构 `cmf_nav_menu`
--

CREATE TABLE IF NOT EXISTS `cmf_nav_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nav_id` int(11) NOT NULL COMMENT '导航 id',
  `parent_id` int(11) NOT NULL COMMENT '父 id',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态;1:显示;0:隐藏',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `target` varchar(10) NOT NULL DEFAULT '' COMMENT '打开方式',
  `href` varchar(100) NOT NULL DEFAULT '' COMMENT '链接',
  `icon` varchar(20) NOT NULL DEFAULT '' COMMENT '图标',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '层级关系',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='前台导航菜单表';

--
-- 转存表中的数据 `cmf_nav_menu`
--

INSERT INTO `cmf_nav_menu` (`id`, `nav_id`, `parent_id`, `status`, `list_order`, `name`, `target`, `href`, `icon`, `path`) VALUES
(1, 1, 0, 1, 0, '首页', '', 'home', '', '0-1');

-- --------------------------------------------------------

--
-- 表的结构 `cmf_option`
--

CREATE TABLE IF NOT EXISTS `cmf_option` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `autoload` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否自动加载;1:自动加载;0:不自动加载',
  `option_name` varchar(64) NOT NULL DEFAULT '' COMMENT '配置名',
  `option_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '配置值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='全站配置表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `cmf_plugin`
--

CREATE TABLE IF NOT EXISTS `cmf_plugin` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '插件类型;1:网站;8:微信',
  `has_admin` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否有后台管理,0:没有;1:有',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态;1:开启;0:禁用',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '插件安装时间',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '插件标识名,英文字母(惟一)',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '插件名称',
  `demo_url` varchar(50) NOT NULL DEFAULT '' COMMENT '演示地址,带协议',
  `hooks` varchar(255) NOT NULL DEFAULT '' COMMENT '实现的钩子;以“,”分隔',
  `author` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '插件作者',
  `author_url` varchar(50) NOT NULL DEFAULT '' COMMENT '作者网站链接',
  `version` varchar(20) NOT NULL DEFAULT '' COMMENT '插件版本号',
  `description` varchar(255) NOT NULL COMMENT '插件描述',
  `config` text COMMENT '插件配置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='插件表';

-- --------------------------------------------------------

--
-- 表的结构 `cmf_recycle_bin`
--

CREATE TABLE IF NOT EXISTS `cmf_recycle_bin` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `object_id` int(11) DEFAULT '0' COMMENT '删除内容 id',
  `create_time` int(10) UNSIGNED DEFAULT '0' COMMENT '创建时间',
  `table_name` varchar(60) DEFAULT '' COMMENT '删除内容所在表名',
  `name` varchar(255) DEFAULT '' COMMENT '删除内容名称',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT=' 回收站';

-- --------------------------------------------------------

--
-- 表的结构 `cmf_route`
--

CREATE TABLE IF NOT EXISTS `cmf_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '路由id',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态;1:启用,0:不启用',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'URL规则类型;1:用户自定义;2:别名添加',
  `full_url` varchar(255) NOT NULL DEFAULT '' COMMENT '完整url',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '实际显示的url',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='url路由表';

-- --------------------------------------------------------

--
-- 表的结构 `cmf_slide`
--

CREATE TABLE IF NOT EXISTS `cmf_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态,1:显示,0不显示',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间',
  `name` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '幻灯片分类',
  `remark` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '分类备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='幻灯片表';

-- --------------------------------------------------------

--
-- 表的结构 `cmf_slide_item`
--

CREATE TABLE IF NOT EXISTS `cmf_slide_item` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slide_id` int(11) NOT NULL DEFAULT '0' COMMENT '幻灯片id',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态,1:显示;0:隐藏',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '幻灯片名称',
  `image` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '幻灯片图片',
  `url` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '幻灯片链接',
  `target` varchar(10) NOT NULL DEFAULT '' COMMENT '友情链接打开方式',
  `description` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '幻灯片描述',
  `content` text CHARACTER SET utf8 COMMENT '幻灯片内容',
  `more` text COMMENT '链接打开方式',
  PRIMARY KEY (`id`),
  KEY `slide_id` (`slide_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='幻灯片子项表';

-- --------------------------------------------------------

--
-- 表的结构 `cmf_theme`
--

CREATE TABLE IF NOT EXISTS `cmf_theme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后升级时间',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '模板状态,1:正在使用;0:未使用',
  `is_compiled` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否为已编译模板',
  `theme` varchar(20) NOT NULL DEFAULT '' COMMENT '主题目录名,用于主题的维一标识',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '主题名称',
  `version` varchar(20) NOT NULL DEFAULT '' COMMENT '主题版本号',
  `demo_url` varchar(50) NOT NULL DEFAULT '' COMMENT '演示地址,带协议',
  `thumbnail` varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图',
  `author` varchar(20) NOT NULL DEFAULT '' COMMENT '主题作者',
  `author_url` varchar(50) NOT NULL DEFAULT '' COMMENT '作者网站链接',
  `lang` varchar(10) NOT NULL DEFAULT '' COMMENT '支持语言',
  `keywords` varchar(50) NOT NULL DEFAULT '' COMMENT '主题关键字',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '主题描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `cmf_theme_file`
--

CREATE TABLE IF NOT EXISTS `cmf_theme_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_public` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否公共的模板文件',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `theme` varchar(20) NOT NULL DEFAULT '' COMMENT '模板名称',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '模板文件名',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '操作',
  `file` varchar(50) NOT NULL DEFAULT '' COMMENT '模板文件,相对于模板根目录,如Portal/index.html',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '模板文件描述',
  `more` text COMMENT '模板更多配置,用户自己后台设置的',
  `config_more` text COMMENT '模板更多配置,来源模板的配置文件',
  `draft_more` text COMMENT '模板更多配置,用户临时保存的配置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

