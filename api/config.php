<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用命名空间
    'app_namespace'          => 'api',
    // 应用模式状态
    'app_status'             => APP_DEBUG ? 'debug' : 'release',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => ['cmf' => CMF_PATH, 'plugins' => PLUGINS_PATH, 'app' => CMF_ROOT . 'app/'],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT, CMF_PATH . 'common' . EXT],
    // 默认输出类型
    'default_return_type'    => 'json',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => 'htmlspecialchars',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => true,
    // 控制器类后缀
    'controller_suffix'      => true,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'home',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    'pathinfo_depr'        => '/',
    // URL伪静态后缀
    'url_html_suffix'      => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'     => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'       => 0,
    // 是否开启路由
    'url_route_on'         => true,
    // 路由配置文件（支持配置多个）
    'route_config_file'    => ['route'],
    // 是否强制使用路由
    'url_route_must'       => false,
    // 域名部署
    'url_domain_deploy'    => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'      => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'          => true,
    // 默认的访问控制器层
    'url_controller_layer' => 'controller',
    // 表单请求类型伪装变量
    'var_method'           => '_method',

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'              => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 视图根目录
        'view_base'    => '',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '<',
        // 标签库标签结束标记
        'taglib_end'   => '>',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'      => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl' => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'   => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'        => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'         => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'        => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'      => '\\api\\common\\exception\\Http',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'   => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => [],
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace' => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache' => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session' => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'think',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'  => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    // +----------------------------------------------------------------------
    // | 数据库设置
    // +----------------------------------------------------------------------

    'database'                => [
        // 数据库调试模式
        'debug'           => true,
        // 数据集返回类型
        'resultset_type'  => 'collection',
        // 自动写入时间戳字段
        'auto_timestamp'  => false,
        // 时间字段取出后的默认时间格式
        'datetime_format' => false,
        // 是否需要进行SQL性能分析
        'sql_explain'     => false,
    ],

    //分页配置
    'paginate'                => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
    //图片验证码
    'captcha'                 => [
        // 验证码字符集合
        'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        // 验证码字体大小(px)
        'fontSize' => 25,
        // 是否画混淆曲线
        'useCurve' => true,
        // 验证码图片高度
        'imageH'   => 30,
        // 验证码图片宽度
        'imageW'   => 100,
        // 验证码位数
        'length'   => 5,
        // 验证成功后是否重置
        'reset'    => true
    ],

    // +----------------------------------------------------------------------
    // | CMF 设置
    // +----------------------------------------------------------------------
    'cmf_theme_path'          => 'themes/home/',
    'cmf_default_theme'       => 'simpleboot3',
    'cmf_admin_theme_path'    => 'themes/admin/',
    'cmf_admin_default_theme' => 'simpleboot3',
	
	// +----------------------------------------------------------------------
	// | 支付宝 配置
    // +----------------------------------------------------------------------
	'alipay'				  => [
		//签名方式,默认为RSA2(RSA2048)
		'sign_type' => "RSA2",
		//支付宝公钥
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA8YQtTMmecP1fUBCUBrBERsiSuqp5nMK/wZXg7Lcq+NYlmOHh+DQUZQgfo8i55g4+typUW7VfyJg+jGk2p5ACfSwkNS67ec5r8EGin3L8i+f8EmaRfNtR3a4rY4a2dEtXEhphppO0YTC65Etpmn9YRA3rwEkaanL4+XV/HWaTekfu1jkwgcF2vsGpUYdEg6zC2LT88qPXPVS//AIgxCAT6PBUtxKT1OFYX8ctjm9Ecti0FTGWPMT8H3FCUgXgNMp+ke2Eq0pNcRC4XysioH7eTpTgQfvpaiKH/kjf/b4xgdt4c1mg5/X7GzcKFKqMOlp3R5Wx7ytP21pjI9vaLWZeuQIDAQAB",
		//商户私钥
		'merchant_private_key' => "MIIEogIBAAKCAQEAsi9Vn+Yum/Od+3YObkwRZtbFmUMlg7ZikNgKivSb0L5EgOIMEWJ7V6/RkFMg0Mb1iRtIZ/IbXtNfIToUit58JAT6BnGaSTRx5zmILo0sPbWOlfUfMmGRERm7/XrEfho2FvIpagDVjz2WSs/J7ap4Jzbl9Um9NfvG3yqwrp6NLjSVV9z2f2k14B2NL9m1ikSKsfkvdDfkqEf2e1lGaEelFqOzBYKhYCMXsfkDqLcQlqON87mclrJMgOJeXezWdmBd0vrFxoxcNwzGiGG4QV5L+qgjpNiY73tLUd0REmay3+SnpS/wbYlSsJBB0xKXqaDvHaIbwe+7vreTPgsqkYlmtwIDAQABAoIBACU4khBWywG0wBmZLaaIqVHsJ1a+mgWLgcdz+a/RLQNL2494qMCw68cDaSlW1BIInZ3IXzWnc2Q5jzOnqEbh9tinWXsjG/GSzQBaGkJvJwC0/lYA4EVr8Bu8XnKyxHz7CLV+XHxSNEo5uT1jbnEBHRxD8YFtp+Kw93gCuOmhISi3ZgM4/7vjh8Paqd2g7cyzOrVfdIbPzkEFl4cC4ItVerSnK+8dHeYGpmrY5LLGRbO9Tzpe6ogk/F6Bs9M3g6wLKGw3hvNF1OCVQ00aM73abp+3LbgDIkFNjj8PCfxhBJcyztFIXD9ZmqorVnmzWujFePtBDZbToQovmIclUS26rDECgYEA2hOIKWmHXfnEMSeI7twYHqLOkC7wOyl1SgMO7JtM2BRvXdECdWDeMPwf5keK1mNbC0hDjj9GzErLiiX4pf5SSjK0nt+4iuO/AjJUT0hobo8PgXzSE+A4waeQ/jMq6N/lAWpkacB2gZNvOIgsqlm64oBSVWvU3BHOJWr9xp7sBEMCgYEA0Svj8uE8xKaJkqFgiDFQhhP86/jdzSi7ZqI1MOGy1+OwTSipqpxWSKG9cpT9izUiYdbEZCdVRqAdl5Oh7lrUlbaj0Oe125o/hRYu536C0KB7nzeHxIS8SCE0J+bE/WubwTr/VVBmcSaXUa2/lL9hLFpnNWC9tsOG1JWlJtrLRn0CgYBDe3fPGNDCy99iNpGxGHp+QHMbOusr45Bf7sJXhVcqJmiv51WTbP5UujBE2i6kWOp7e0ksY3hND3gcr9NZynE8dKRf5Wr9z6vzyg882XM3gx2RPEblz6TAiFHd14eXuHDtFzcrYltJjY4FOF5Z+JpULNFNjai40OmxuoH9TIBMcQKBgDW8FL8cZcQPFGB63JWgNZN8Jwln0XMW962SFiAMmyki8POhcpTFvNTD2CtLYycT61U/GwyvnhSapX9/CaZ15w7e8I6GOA3GPnMuE7acpXZ7A8cPOO2fO8872G43tntz3XtycI22Ldr7kvwEqqbH02rVfpYT2CjPah/KobatlnLxAoGASHzeLXyIbqEiZzl9/ZL8yBCZ0eosm194AYP4AdbUcqKiUrIveGTtugXLwwZ1itC6n90+7z5ajtvz3zruW06UT5bcOBmL1I0o8T5rLlDd6FvKte2tr5YvM5S/CEpmQ4hl3BMi+8ucU7RkipomqVGRUmTm0LMYaGsJXMZVCs32SqQ=",
		//编码格式
		'charset' => "UTF-8",
		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",
		//应用ID
		'app_id' => "2016091500519992",
		//异步通知地址,只有扫码支付预下单可用
		'notify_url' => "http://www.baidu.com",
		//最大查询重试次数
		'MaxQueryRetry' => "10",
		//查询间隔
		'QueryDuration' => "3",
		//API版本
		'api_version' => "1.0",
		//格式
		'format' => "json"
	],
];
