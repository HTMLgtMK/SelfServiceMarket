## 无人超市开发日志

```
/**
 *                             _ooOoo_
 *                            o8888888o
 *                            88" . "88
 *                            (| -_- |)
 *                            O\  =  /O
 *                         ____/`---'\____
 *                       .'  \\|     |//  `.
 *                      /  \\|||  :  |||//  \
 *                     /  _||||| -:- |||||-  \
 *                     |   | \\\  -  /// |   |
 *                     | \_|  ''\---/''  |   |
 *                     \  .-\__  `-`  ___/-. /
 *                   ___`. .'  /--.--\  `. . __
 *                ."" '<  `.___\_<|>_/___.'  >'"".
 *               | | :  `- \`.;`\ _ /`;.`/ - ` : | |
 *               \  \ `-.   \_ __\ /__ _/   .-` /  /
 *          ======`-.____`-.___\_____/___.-`____.-'======
 *                             `=---='
 *          ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
 *                     佛祖保佑        永无BUG
*/
```

-------------------------------------------------

2018.05.24 23:14

1. 添加了会员支付密码数据表`tb_user_pay_shadow`. 在提交余额支付时会用到该密码, 
	会员在支付前必须先设置支付密码.
	**支付密码部分暂时未完成**.

2. 添加了余额支付数据表`tb_balance_pay`, 用于余额支付。
	数据表内容参考了支付宝支付和微信支付内容, 使用建造者模式.
	
3. 添加了余额支付预下单请求接口: `/api/market/Goods_Sale/balance_qrpay`,</br>
	添加了余额支付交易结果查询接口: `/api/market/Goods_Sale/balancePayQuery`
	
4. 添加了会员余额支付交易状态查询接口: `/api/user/User_Payment/checkBalancePayStatus`,</br>
	添加了会员余额支付提交支付接口: `/api/user/User_Payment/balance_pay` (**未做验证**)

-------------------------------------------------

2018.05.21 23:00

1. 完成了用户头像上传接口和用户头像获取接口。
	奇怪的是使用`curl`请求用户头像不能直接解码为图片而是乱码。

-------------------------------------------------

2018.05.20 18:33

1. 修改了获取商品详情接口，拆分成获取优惠和获取商品。
	当商品不存在时(可能是别的标签)，需要返回传入的商品ID.

2. 在后台`base64_decode`之前，需要把从POST中获取得到的数据中的
	空格' '替换成加号'+', 否则会出现乱码。
	eg:
	```php
	// php 后台获取得到的base64编码: W3siaW1hZ2VzIjoiIiwiYWRkcmVzcyI6IuWxseS4nOWogea1tyIsImJhdGNoX251bWJlciI6IjEyMzQ1NiIsInR5cGVfaWQiOjIsImRpc2NvdW50Ijp7ImV4dGVudCI6MSwicmVzdCI6MjE0NzQ4MzY0NywibmFtZSI6Iueri WHjzXliIYiLCJnb29kc190eXBlX2lkIjoyLCJpZCI6NCwib3BlbiI6MSwiY29pbiI6LTUsImRpc2NvdW50X2lkIjoxMX0sImdvb2RzX2lkIjoiMjAxMjgxMDEwMDAyMDAwMDAwMDAwMDAwMDAwMDAwMTEiLCJtYW51ZmFjdHVyZV9kYXRlIjoxNTI2NzQ1NjAwLCJwcmljZSI6MzAwLCJuYW1lIjoi6Zu25bqm5Y v5LmQIiwiY29tcGFueSI6ImhpdHdoLWd0IiwiaWQiOjIsInN0YXR1cyI6MX1d
	// PC端传送的base64编码: W3siaW1hZ2VzIjoiIiwiYWRkcmVzcyI6IuWxseS4nOWogea1tyIsImJhdGNoX251bWJlciI6IjEyMzQ1NiIsInR5cGVfaWQiOjIsImRpc2NvdW50Ijp7ImV4dGVudCI6MSwicmVzdCI6MjE0NzQ4MzY0NywibmFtZSI6Iueri+WHjzXliIYiLCJnb29kc190eXBlX2lkIjoyLCJpZCI6NCwib3BlbiI6MSwiY29pbiI6LTUsImRpc2NvdW50X2lkIjoxMX0sImdvb2RzX2lkIjoiMjAxMjgxMDEwMDAyMDAwMDAwMDAwMDAwMDAwMDAwMTEiLCJtYW51ZmFjdHVyZV9kYXRlIjoxNTI2NzQ1NjAwLCJwcmljZSI6MzAwLCJuYW1lIjoi6Zu25bqm5Y+v5LmQIiwiY29tcGFueSI6ImhpdHdoLWd0IiwiaWQiOjIsInN0YXR1cyI6MX1d
	
	// 替换
	$data['goods_detail'] = str_replace(' ', '+', $data['goods_detail']);
	$data['discount_detail'] = str_replace(' ', '+', $data['discount_detail']);
	```
	
-------------------------------------------------

2018.05.19 23:06

1. 完成了优惠广场接口，会员优惠接口: `/api/market/controller/DiscountController.php`

-------------------------------------------------

2018.05.18 09:24

1. 添加了会员交易成功添加积分。在微信交易查询和支付宝交易查询时，若检查到交易成功，
	则为会员添加积分。( total_amount / 500 + 1).

20:10 

2. 添加了会员积分获取，会员积分转余额，积分明细接口。

3. 添加了会员余额获取，会员余额明细接口。

-------------------------------------------------

2018.05.17 

1. 添加了优惠会员数据表`tb_discount_user`，用于存储仅限会员使用的优惠信息。

2. 对优惠表`tb_discount` 添加列`open`, 用来区分是否向普通顾客开放优惠。

3. 修改了订单提交逻辑，将对总体优惠信息修改变成会员优惠信息修改。

-------------------------------------------------

2018.05.12 12:47

1. 添加了会员账单查询接口。
	`api/client/controller/AccountController.php`
	
------------------------------------------------

2018.05.10 09:55

1. 完成了用户授权后台逻辑: `/api/user/controller/UserGrantController.php`
	添加了授权状态4，表示当前二维码已被用户扫描，但未确认授权，
	用户扫描后，授权的接口只能由扫描二维码的用户授权。

-------------------------------------------------

2018.05.08 23:11

1. 添加了用户授权数据表`tb_user_grant`, 用于无人超市会员扫描二维码授权终端。
	授权流程：
	1. 终端向商家后台请求授权码，将授权码用二维码展示。
	2. 用户使用移动端扫描二维码，解析二维码。
	3. 用户确认授权，将token和用户id提交给商家后台。
	4. 商家对授权请求表修改状态为已授权，并保存用户id。
	5. 终端轮询授权状态，当授权完成时，获取用户信息，进行后续操作。

-------------------------------------------------

2018.05.04

这几天感觉都没有什么很大的进展了，主要是修修补补。。。之前埋下的坑有点多

1. 修改了商品列表的排序，改成由生产日期倒序。

2. 修改了商品类型列表的排序，改成由创建日期倒序。

今天是五四青年节，作为一名新青年，更是感受到了时代和祖国的鼓舞！
下面是 2018年5月2日，习近平总书记在北京大学对青年的寄语：
> 要爱国，忠于祖国，忠于人名。
> 要励志，立鸿鹄志，做奋斗者。
> 要求真，求真学问，练真本领。
> 要力行，知行合一，做实干家。
> ---- 2018.05.02 习近平总书记北大寄语青年

自我反省，第1条最基本，我也做到了；
第2条，最近半年确实惭愧，接下来确实要确定目标，鉴定自己的理想；
第3条，自我认为求学贵在坚持，我应该拥有坚持的品质；
第4条，对程序员来讲，我自认为是要在各个领域的技术都要了解，并且达到一定水平。

青年是祖国未来的支柱，国家需要优秀的青年！加油！

-------------------------------------------------

2018.04.30 

1. 完成批量数据初始化，建站完毕后可以直接使用数据接口初始化。
	直接使用php+mysql导入数据出现了字符集不匹配的情况，后来发现本地服务器中数据库的字符集情况如下:
	```sql
	mysql > show variables like "%char%";
	```
	+--------------------------+---------------------------------------------+
	| Variable_name            | Value                                       |
	+--------------------------+---------------------------------------------+
	| character_set_client     | gbk                                         |
	| character_set_connection | gbk                                         |
	| character_set_database   | utf8mb4                                     |
	| character_set_filesystem | binary                                      |
	| character_set_results    | gbk                                         |
	| character_set_server     | latin1                                      |
	| character_set_system     | utf8                                        |
	| character_sets_dir       | D:\CodeSoft\Amp\xampp\mysql\share\charsets\ |
	+--------------------------+---------------------------------------------+

	而php+mysql 操作连接是utf8的，因此会出现字符集不匹配的错误。
	解决方法:
	```php
	mysqli_query($conn, "set names 'gbk';");
	```
	
	最后，还是采用了ThinkCMF5里面封装好的Db，编写脚本作为初始化数据接口: 
	`/api/init/controller/IndexController.php`
	
2. 交易成功给店铺终端添加销量。
	其实店铺也可以采用这种方法，但是只能获取总销量，要获取当日销量的话还是要使用view。
	
-------------------------------------------------

2018.04.29 15:54

1. 去掉了签到表(`tb_market_daily_checkin`)和员工工资财务表(`tb_market_salary_accounting`)，
	添加了无人超市财务类型表(`tb_market_account_type`)和无人超市财务表(`tb_market_account`)。
	财务类型表有默认类型，后面不做修改。

2. 添加了财务管理(非自动管理, 店主也可以考虑不使用...)
	可以打印报表，尝试了两种方法: `window.print()`, 和 `jquery.print()`方法.
	1. `window.print()`参考baidu
	2. `jquery.print()` 参考 [jQuery.print](http://doersguild.github.io/jQuery.print/)
	
3. window.open()的用法，由第二个参数可以指定打开窗口的类型。

-------------------------------------------------

2018.04.28 20:36

今天完成了销售管理主界面的设计和编写。

1. 新增视图`view_store_sale`, 用来表示店铺当日的销售额。
	用到了Mysql中的一些函数, 下面是当天日期函数小结:
	1. `now()` 获取当前时间
	```sql
	mysql > select now();
	```
	+---------------------+
	| now()               |
	+---------------------+
	| 2018-04-28 20:41:03 |
	+---------------------+
	1 row in set (0.00 sec)

	2. `curdate()` 获取当前日期
	```sql
	mysql > select curdate();
	```
	+------------+
	| curdate()  |
	+------------+
	| 2018-04-28 |
	+------------+
	1 row in set (0.00 sec)
	
	3. `curtime()` 获取当前时间
	```sql
	mysql > select curtime();
	```
	+-----------+
	| curtime() |
	+-----------+
	| 20:43:44  |
	+-----------+
	1 row in set (0.00 sec)
	
	4. 时间戳（timestamp, 非毫秒时间戳）转换、增、减函数：
	``timestamp(date)`` -- date to timestamp 
	``timestamp(dt,time)`` -- dt + time 
	``timestampadd(unit,interval,datetime_expr)``
	
	5. Unix 时间戳、日期）转换函数：
	``unix_timestamp()`` -- 当前时间的unix时间戳
	``unix_timestamp(date)`` -- date to unix_timestamp
	
	```sql
	mysql > select  timestamp(curdate());
	```
	+----------------------+
	| timestamp(curdate()) |
	+----------------------+
	| 2018-04-28 00:00:00  |
	+----------------------+
	1 row in set (0.00 sec)
	
2. MYSQL 对查询为 `NULL` 的值赋默认值
	执行`tb_store`表对`view_store_sale`左连接(`LEFT JOIN`), 使得一些当日没有交易的店铺的销售额为 `NULL`,
	为解决这个问题, 可以使用 `IFNULL`函数对`NULL`值赋默认值。
	eg:
	```sql
	mysql > SELECT `a`.`id`, `a`.`name`, IFNULL(`b`.`store_id`, `a`.`id`) as `store_id`, IFNULL(`b`.`sale_total_amount`, 0) as `sale_total_amount`
					FROM `tb_store` as `a` 
					LEFT JOIN `view_store_sale` as `b` ON `a`.`id`=`b`.`store_id`
					WHERE `a`.`status`='1' OR `a`.`status`='2' ;
	```
	+----+---------+----------+-------------------+
	| id | name    | store_id | sale_total_amount |
	+----+---------+----------+-------------------+
	|  1 | store 1 |        1 |              1204 |
	|  2 | store 2 |        2 |                 0 |
	+----+---------+----------+-------------------+
	2 rows in set (0.00 sec)
	可完美解决默认值的问题。
	
3. 使用了 `Chart.js` 表现店铺交易量。
	`Chart.js`地址为[Chart.js](http://www.chartjs.org/), 下载后存放的地址为: `/public/static/js/chart/Chart.js`。

-------------------------------------------------

2018.04.27 09:21

1. 完成了店铺管理的全部功能。
	1. 验证码验证问题：使用javascript:parent.openIframeDialog()显示一个对话框， 
		修改了验证码生成方法，若验证码已经存在且在有效期内，则直接返回验证码。
	2. 操作完成后更新列表问题：
		首先需要使用ajax手动提交表单，然后根据返回状态操作。
		获取浏览器中`窗口对象`，由`parent`可找到父窗口，
		再由`window.frames[index]`获取子窗口对象。
		**注: `Window`属性和`window`属性是不同的，Window属性获取不到。**
	
	存在的问题：操作完成后对话框js代码 `window.close()`不能生效，对话框不能自动关闭。
		原因是js代码关闭的窗口只能是由js代码打开的窗口。暂时没有解决这个问题。
		
2. Wind.js是一个异步加载js 和 css 的类库，核心是基于 head.js 的，
	phpwind 团队在 head.js 基础上封装了一些方法，可以更加方便的异步加载 js 和 css。
	具体使用参考文档。
	
	`noty`是一个类似Toast的提示框，位置为: `/public/static/js/noty/noty.js`。
	
3. 完成了自助终端管理功能。修改了自助终端数据表中外键store_id可为null，表示当前未分配给店铺。
	插入时为`null`。
-------------------------------------------------

2018.04.26 15:23

1. 完成订单提交时,优惠使用使用信息的修改.

2. 完成店铺管理部分后台逻辑.考虑到操作的危险性,需要验证手机验证码.
	遇到的问题是:如何在操作完成后,及时更新列表中店铺的状态.

-------------------------------------------------

2018.04.25 23:24位

1. 完成供应商品的前台显示界面。

2. 完成折扣管理。`app\market\controller\AdminMarketSaleDiscountController.php`.
	这里用了控制器验证，编写代码的时候老是找不到AdminMarketSaleDiscountValidate.php类，后来也没有发现是哪里错了。
	不过知道问题出在 文件名 or 命名空间 or 类名。最后是重命名了文件名，才解决，可能是哪个字母写错了。
	
3. 完成了折扣商品管理。
	直接在折扣管理列表中操作，类似权限管理，直接勾选商品类别。
	这里折扣商品表的字段只用了id, discount_id, goods_type_id三个，其它的暂时没用。
	
4. 修改了扫描标签时获取商品信息的接口，同时返回优惠信息(所有符合条件的优惠)。

-------------------------------------------------

2018.04.24 17:00

1. 完成了商品类别的编辑和删除。
	删除商品类别前判断该商品类下是否含有商品，若有，则不予删除。

2. 完成了商品的修改和下架。
	下架商品前判断该商品是否已经已售或者被锁定，若是已售或被锁定，则不可下架。

3. 商品表的`status`字段添加状态 3 -- 锁定，
	表示该商品正在购物车中，不可以再被售出或者被下架。
	
4. 添加了撤销交易接口: `api/market/Goods_Sale/revokeDeal` . 
	自助收银系统中，支付界面返回购物车界面需要撤销此次交易。
	
5. 添加了商品的状态改变。在提交订单时，锁定订单中的商品。
	在撤销订单时，取消商品的锁定。
	
6. 完成了供应商的增删改查 `app\market\controller\AdminMarketProviderController`

7. 完成了供应商品管理的增删改查的后台逻辑 `app\market\controller\AdminMarketProviderGoodsController`

-------------------------------------------------

2018.04.23 17:06

这几天没有完成什么内容，主要是添加了微信支付。
本来以为微信支付不可以再测试了，没想到官网上面给了一个服务号的账号，但是不是自己的，所以每次的价格最多也是0.01元。。。

1. 添加了微信支付sdk。/simplewind/vendor/`目录下，修改名字为wxpay。
	1. API接口封装代码
	* WxPay.Api.php 包括所有微信支付API接口的封装
	* WxPay.Config.php  商户配置
	* WxPay.Data.php   输入参数封装
	* WxPay.Exception.php  异常类
	* WxPay.Notify.php    回调通知基类

	2. cert
	证书存放路径，证书可以登录商户平台https://pay.weixin.qq.com/index.php/account/api_cert下载

2. 正式环境使用微信支付需要开通服务号，开通服务号需要营业执照。。。微信支付没有开通沙箱环境。
	正式使用需要修改 `wxpay/lib/WxPay.Config.php` 为自己申请的商户号的信息（配置详见说明）, 另外下载证书替换cert下的文件。
	
3. 修改了数据库中的与价格相关的字段类型为`int`,修改单位由`元`为`分`。
	原因: 使用`float`或者是`double`存储金额都会表示不准确！！！

4. 添加了提交订单数据的接口 `/api/market/Goods_Sale/submit`, 返回商户后台订单号。

5. 添加了支付宝支付预下单和支付结果查询的接口。`/api/market/Goods_Sale/alipay_qrpay`,`/api/market/Goods_Sale/alipayQuery`。

6. 添加了微信支付预下单和支付结果查询的接口。`/api/market/Goods_Sale/wxpay_qrpay`,`/api/market/Goods_Sale/wxpayQuery`。

把订单数据提交和预下单分开的原因是，同时请求提交订单数据以及支付宝、微信支付的预下单会导致接口请求时间过长。
在支付结果查询时，也有这样的问题，并且有时不能正常返回查询结果。而分开请求可以保证能正确获取得到数据，并且逻辑跟家清晰。

-------------------------------------------------

2018.04.15 19:25

今天总算是把支付宝预下单部分搞定了。。

1. 修改预下单提交接口，goods_detail字段由urlencode编码改成base64编码。
	urlencode() 提交后还是会有**转义的符号！** base64编码后没有这个问题。。
	并且，若出现 `Internal Server Error` 错误，多数也是这个部分的问题。。
	
2. 预下单接口中，将自定义商品详情转换成支付宝接口支持的商品详情，使用GoodsDetails类，
	获取要使用`getGoodsDetail()`方法。否则添加的是GoodsDetail Object, json_encode后所有私有变量为空，
	bizContent中goods_detail部分为]``[{},{}]``。支付宝接口提示"参数错误: 商品标题不能为空!"错误。

目前，可以正常提交预下单请求，给客户端返回qrcode字符串。
接下来应该完成的内容是查询支付状态接口。

-------------------------------------------------
2018.04.14 23:51

今天一整天都在搞支付宝的接口，不得不吐槽php版本SDK的难用。。。不过还好终于搞定了！


1. 先下载了官方的php sdk，这个sdk真的是恶评如潮啊！没法composer,没有namespace, 使用框架lotusphp_runtime框架。。
	然后把`alipay-sdk-PHP.zip`解压放到`/simplewind/vendor/`目录下，修改名字为alipay。
2. 修改lotusphp_runtime框架的部分内容:
	1. 修改运行时目录 在`AopSdk.php`文件中，修改为如下代码:
		```php
		if (!defined("AOP_SDK_WORK_DIR"))
		{
			define("AOP_SDK_WORK_DIR", RUNTIME_PATH."/alipay/");//此处修改路径,手动创建runtime\api\alipay目录
		}
		```
		修改后的alipay运行目录为:`/data/runtime/api/alipay`，需要手动创建`alipay`目录，否则会报错。
		
	2. 修改`/vendor/alipay/lotusphp_runtime/shortcut.php`，修改函数名`C`为`CC`，否则会和ThinkCMF5冲突。	
		```php
		function CC($className)
		{
			return LtObjectUtil::singleton($className);
		}
		```
		
3. 使用支付宝提供的沙箱环境，下载沙箱版支付宝。
	**注:沙箱配置时使用的RSA2生成工具没有问题，但是在线验证公钥的正确性却始终提示"您上传的公钥校验失败，请重新上传!"。这里不要管，直接保存就好了。 **
	由上传的RSA2公钥，支付宝会自动生成支付宝公钥，点击查看保存到本地。
	
	这个问题卡了好久。。。
	
4. 支付宝SDK的基本使用 
	具体代码见`/api/market/controller/GoodsSaleBaseController.php` 和 `GoodsSaleController.php`。
	1. 先引入AopSdk.php,执行会自动加载aop下的类。
		方法:
		```php
		//法1 使用ThinkPHP5 提供的vendor函数
		vendor(alipay.AopSdk);// 符号用'/'代替
		//法2 使用require_once 引入
		require_once VENDOR_PATH . DIRECTORY_SEPARATOR . "alipay" . DIRECTORY_SEPARATOR ."AopSdk.php";
		```
		
	2. 新建`AopClient`对象
		**注: 这个对象可重复使用，不必在每次请求时都初始化，声明为静态变量！**
		```php
		self::$aop = new \AopClient();
		self::$aop->gatewayUrl = $alipayConfig['gatewayUrl'];//支付宝网关，注意正式环境网关和沙箱的不一样，沙箱的多个dev..
		self::$aop->appId = $alipayConfig['app_id'];//应用ID，沙箱应用会提供。
		self::$aop->rsaPrivateKey = $alipayConfig['merchant_private_key'];//商户私钥 字符串
		self::$aop->alipayrsaPublicKey= $alipayConfig['alipay_public_key'];//支付宝公钥 字符串
		self::$aop->apiVersion = $alipayConfig['api_version'];
		self::$aop->signType = $alipayConfig['sign_type'];// 签名类型 RSA (1024长度) 或 RSA2(2048长度)
		self::$aop->postCharset= $alipayConfig['charset'];// 字符集 utf-8
		self::$aop->notify_url = $alipayConfig['notify_url'];// 扫码付款的异步通知url地址
		self::$aop->format= $alipayConfig['format'];//json
		self::$aop->debugInfo=true;
		```
		我将alipay的配置保存到了`api/config.php`中，便于修改！
		
	3. 可以新建不同种类型的对象，然后执行
		```php
		$result = self::$aop->execute($request);
		```
		
4. 接口参数问题
	1. 一开始测试的参数是自己按着API文档上面写的，结果出现"参数不正确"错误。
		然后开始排查，以为是密钥问题，又弄了好久。
		最后下载了当面付的php demo， 把里面`f2pay/model/builder`中的类拷贝到market的model/builder中，
		添加文件的命名空间和修改引入其他文件的方式(require -> use)。。
	2. 最后发现`GoodsDetail`居然是array类型的，然后`GoodsDetailList`也是array类型的。。。
		```php
		private $goodsDetail = array();//GoodsDetail.php
		...
		$goodsDetailList = array($goods1Arr,$goods2Arr);//qrpay_test.php
	```
	3. 然后设置给`AlipayTradePrecreateContentBuilder`对象，使用`$qrPayRequestBuilder->getBizContent();`方法获取bizContent。
		```php
		public function getBizContent()
		{
			/*$this->bizContent = "{";
			foreach ($this->bizParas as $k=>$v){
				$this->bizContent.= "\"".$k."\":\"".$v."\",";
			}
			$this->bizContent = substr($this->bizContent,0,-1);
			$this->bizContent.= "}";*/
			if(!empty($this->bizParas)){
				$this->bizContent = json_encode($this->bizParas,JSON_UNESCAPED_UNICODE);
			}//仅仅是转换成json格式了而已
			return $this->bizContent;
		}
		```
	4. $aop->execute($request)方法调用后，再`AopClient.php`文件503行处，有对各个参数进行`urlencode`
		```php
			//系统参数放入GET请求串
			$requestUrl = $this->gatewayUrl . "?";
			foreach ($sysParams as $sysParamKey => $sysParamValue) {
				$requestUrl .= "$sysParamKey=" . urlencode($this->characet($sysParamValue, $this->postCharset)) . "&";
			}
			$requestUrl = substr($requestUrl, 0, -1);
		```

虽然目前还没有完成前台的展示和支付状态的回调，但是后面的东西都应该不难了。
看到 code = 10000的心情真是太好了!
	
2018.04.14 11:46

1. nginx bug: 当使用php脚本远程访问本地另一个php页面时，会出现无响应。
	当前使用的是项目A，但是A调用了项目B（权限系统） 但是配置的Nginx时只固定了一个9000端口 A占用后；B就无法访问
	解决方案:
	```
	# nginx.conf
	upstream fastcgi_backend {
		server 127.0.0.1:9000;
		server 127.0.0.1:9001;
		server 127.0.0.1:9002;
    }
	server{
		...
		location ~ \.php$ {
				fastcgi_pass   fastcgi_backend;
				fastcgi_index  index.php;
				fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
				fastcgi_split_path_info ^(.+\.php)(.*)$;
				include        fastcgi_params;
		}
	}
	```
	参考: [BUG:upstream timed out (10060: A connection attempt failed because the connected party did not 
			properly respond after a period of time, or established connection failed because connected ](https://www.cnblogs.com/attitudeY/p/6798956.html)

-------------------------------------------------
2018.04.13 19:19

1. 修改交易数据表，修改交易详情为商品详情和支付详情，
	添加修改时间字段和交易状态字段, 去掉商品ID字段。

2. 为用户表添加默认用户id=1, 表示非vip用户。


-------------------------------------------------
2018.04.11 21:02

1. 新增添加商品接口 `/api/market/controller/GoodsController.php`

2. 新增获取商品类别接口 `/api/market/controller/GoodsTypeController.php`

3. 新增获取商品ID接口 `/api/market/controller/Goods/getGoodsId`
	修改ID生成算法，采用大数的加法算法。

4. 新增添加商品ID等完整信息接口 `/api/market/controller/Goods/submit`
	注: 没有检测ID是否会重复!
	
5. 新增获取商品信息接口 `/api/market/controller/Goods/getGoodsInfo`

-------------------------------------------------
2018.04.06 09:37

1. 添加员工客户端登录token表

2. 修改api/admin/controller/PublicController.php
	作为员工登录接口
	
3. 修改/cmf/controller/RestAdminBaseController.php
	作为PublicController的基类。


-------------------------------------------------
2018.04.03 19:48

1. 完成商品管理的主界面。。。

2. 完成添加商品的后台逻辑
	添加商品流程:
	1. 选择商品类别，获取商品ID
	2. 填写商品生产日期和生产批号
	3. 写入标签
	4. 数据写入数据库
	
	商品ID结构:
	 V | NSI |        MD          
	   |     |
	   |     |  DC  | AC |   IC    
	---|-----|------|----|---------
	 2 | 0128|  1  | 2  | 4位|20位
	
	说明:(十进制)
	* V: 2 版本 1位
	* NSI: Ecode128 编码体系 4位
	* DC: 分区码 1位
	* AC: 应用码 2位
	* IC: 二级分类 24位
	* IC::type: 商品分类 4位
	* IC::id  : 商品ID 20位

由于给定的RFIDReader的驱动程序只有dll, 故添加商品只能在windows环境下完成。

另外，JS似乎调用本地程序的方法((new ActiveXObject("wscript.shell")).Run(filePath))似乎不适用，
可以执行本地程序却无法获得需要的返回值。

考虑搭建windows环境下的商品管理服务程序，包括添加商品，添加到购物车，自助收银。
利用JavaFX搭建界面，但是由于给的是动态库，因此后面会用到JNI。。。

换环境啦~linux 再见!

-------------------------------------------------
2018.04.02 16:30

1. 修改了部分数据表
	* 新增购物车表
	* 去掉积分和余额管理
	* 新增店铺管理，自助终端管理，供应管理，商品管理以及销售管理目录
	* 新增以上目录下路由权限

为了能够顺利通过中期检查，先编写商品管理部分(进货)代码，再写销售管理(添加购物车, 售卖收银)。

-------------------------------------------------
2018.04.01 17:51

1. 新增 [thinkcmfapi](https://github.com/thinkcmf/thinkcmfapi)
	覆盖项目根目录，修改 `api/user/controller/PublicController.php`。。。
	还好上传到啦github, 不然README.md被覆盖，日志就丢掉了。
	
2. 基于API完成用户注册，用户登录，用户登出等功能。


3. 完成验证码发送功能
	1. 生成verification_code, 调用`cmf_get_verification_code($account)`
	2. 将verification_code写入数据库, 调用 `cmf_verification_code_log($account, $code, $expireTime = 0)`
	3. 将verification_code发送给用户
		* 邮箱注册用户
			1. 配置好网站的邮箱配置，注意发送用户是发送邮箱名，密码是邮箱授权码。
			2. 安装 配置 `PHPMailer` 发送邮件，直接composer require
			3. 调用 cmf_send_email($account,$subject,$message) 发送邮件
		* 手机注册用户
			直接使用`success()`的时候返回数据。。。因为手机短信验证码收费啊啊啊啊。
			
4. 完成用户注册功能
	1. 使用验证器验证数据是否合法。
	2. 检测用户名和用户帐号是否已经存在。
	3. 检测验证码是否正确。调用`cmf_check_verification_code($account, $code, $clear = false)`
	4. 插入新用户数据，修改`verification_code`表中的验证码。调用`cmf_clear_verification_code($account)`
	5. 返回注册结果
	
5. 完成用户登录功能
	1. 检测数据是否合法
	2. 检测用户状态，是否正常
	3. 匹配密码，利用 `cmf_compare_password()`
	4. 生成token, 利用 `cmf_generate_user_token($userId,$device_Type)`生成。
	5. 更新用户最后登录状态
	6. 返回token以及用户信息

6. 用户登出
7. 用户密码重置

**注: 这里的上传数据使用GET方法和POST方法均可。测试使为简单起使用GET方法。**

-------------------------------------------------
2018.04.01 10:54

1. 编辑管理员新增修改管理员岗位

2. 管理员列表新增删除功能，新增员工状态过滤条件

3. 管理员列表新增管理员离职、启用功能。

到这里，管理员管理就算完成啦。

-------------------------------------------------
2018.03.31 23:30

1. 完成了管理员部分管理: 添加管理员

2. 完善管理员列表，显示员工岗位，增加岗位过滤条件

-------------------------------------------------
2018.03.31 16:52

1. 新建了 `market` 应用模块，使的模块间更加清楚。
	具体如下:
	1.在 app\market 目录下 新建目录 `controller`,`lang`,`validate`, 分别存放 控制器，语言包和验证类。
	2.在 public\themes\admin_simpleboot3 目录下 新建目录 `market`，存放market模块后台管理的View文件。

2. 完成岗位管理(`market\controller\AdminMarketPostController`)，包括岗位列表(`index`)，岗位添加(`add`)，岗位编辑(`edit`)，岗位删除(`delete`)

-------------------------------------------------
2018.03.30

1. 修改员工登录控制
修改登录选择，用户名或者手机号登录;
修改`AdminBaseController.class.php`中的权限检查;

2. 修改后台菜单中无人超市应用的子菜单项的显示选项。

目前，网站可以正常安装并且进入后台。
但是，员工管理、用户管理 以及 无人超市应用均未完成。


-------------------------------------------------
2018.03.29

完成 E-R图到关系数据库的转化

执行顺序：
1. thinkcmf.sql ThinkCMF管理后台基础
2. role.sql 角色管理 和 权限管理
3. market.sql 无人超市全局配置
4. user.sql 部门管理 和 会员管理
5. market_account.sql 无人超市帐务管理
6. market_store.sql 无人超市商店具体管理
7. goods.sql 商品管理
8. sale.sql 交易管理
9. portal.sql 门户网站

共49个数据表
目前，数据库已经成功建立，但是管理网站后台仍然安装失败！

-------------------------------------------------
2018.03.27
搭建ubuntu开发环境，下载ThinkCMF框架，部署到服务器，上传github保存。


