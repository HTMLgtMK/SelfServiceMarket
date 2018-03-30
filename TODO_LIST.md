## 这里写着需要做的事情

1. 修改安装第一步时的说明
	MYSQL TEXT,JSON,BLOB格式数据不允许有默认值
	MYSQL 中 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci 在NOT NULL之前
	MYSQL char类型最大值为255,max=255。大于255的要使用 text类型
	MYSQL 中关于约束， 每个表只能有一个约束语句，但可以有复合约束条件

2. tb_market_salary_accouting 需要在tb_adminstrator建立后执行
	tb_market_daily_checkin 需要在tb_adminstrator建立后执行
	tb_store需要在tb_adminstrator建立后执行

3. 修改 install/controller/IndexController.class.php 中的 setSite()部分
	由于 管理员 数据表修改啦，所以要改
	修改install/step3页面中的管理员信息，添加项即可

4. 修改BaseController.class.php 以及 AdminBaseConstroller.class.php
	AdminBaseController.class.php  _initialize() 修改Db::name()

5. 修改登录控制
	PublicController.class.php
	修改 \cmf\lib\Auth\check()




