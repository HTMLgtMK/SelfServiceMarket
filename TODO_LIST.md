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

6. 管理员管理
	修改\app\admin\controller\UserController.php 为 AdminstratorController.php
	修改\app\admin\model\UserModel.php 为 AdminstratorModel.php
	**注:文件内部名称也需要修改，包括 `view` 中的文件 及其 `url`**

	明天增加管理员岗位，以及条件查询。
	
7. 管理员管理前，先完成角色管理以及岗位管理。。。

8. empty() 检测值余返回值
	isset() 检测值余返回值
	
9. 利用ThinkCMF5 新增的api技术，为用户注册，登录提供数据接口。

10. 添加商品流程:
	1. 选择商品类信息
	2. 启动RFID读写器，程序自动检测连接状态 ( 显示连接提示信息 )
	3. 生成商品ID信息 ( 自动生成，不可修改，由ecode编码和数据库已有id确定 )
	4. 将标签放在识别范围内，点击写入 ( 调用外部程序和驱动程序，显示写入状态： 准备中，写入中，写入完成  )
	5. 点击提交，将数据提交到数据库保存。
	6. 将标签移离识别范围
	
11. InnoDB引擎的索引键值长度不能超过255
	ERROR 1071 (42000): Specified key was too long; max key length is 767 bytes
	
12. 产生商品ID的算法有问题
	由于商品ID有20位，是一个大数，因此要使用大数加法。
	
13. 编写硬件相关内容：
	1. 先使用VS编写功能函数，测试RFID读写器是否能够正常连接并按程序工作
	2. 再使用JNI， 将写好的中间件利用javah工具转换成头文件，然后实现调用
	
14.编写微信支付接口
	预下单 和 支付宝预下单 统一接口
	支付结果查询 单独查询
	
15. 编写店铺管理, 自助终端管理

16. 去掉签到管理, 完成财务管理, 交易管理
	交易管理首页:
		1. 实时交易信息(交易成功)
		2. 交易额显示: 显示今日交易量， 年,季度,月交易量链接 (统计分析)  曲线图
		3. 店铺交易额显示 柱图
		
17. 完成 "我的消费" 功能。
	1. 首页 最近一周的消费， 用 RecycleView 显示。
	2. "我的消费" 功能， 分页展示账单。
	
18. 完善用户支付功能
	1. 修改商品优惠方式，每个用户有不同的优惠，需要新增优惠用户表，优惠表只用于存储
		优惠信息，优惠包括电子现金券(coin), 折扣券(extent)
	2. 用户每购买一次按照金额可获取积分。
	
19.自助收银系统 测试 多个标签是否能够正确工作!!!
