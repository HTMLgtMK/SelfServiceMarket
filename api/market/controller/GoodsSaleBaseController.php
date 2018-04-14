<?php
/**
 * 商品交易基础类
 * author: GT
 * time: 2018.04.13 20:03
 */
 
namespace api\market\controller;

use think\Controller;
use think\Config;

class GoodsSaleBaseController extends Controller {
	
	/**
	 * 支付宝 客户端
	 */
	protected static $aop;
	
	protected function _initialize() {
		if(empty(self::$aop)){
			//require_once(VENDOR_PATH . DIRECTORY_SEPARATOR . "alipay ". DIRECTORY_SEPARATOR ."AopSdk.php");
			vendor("alipay.AopSdk");
			self::$aop = new \AopClient();
			$alipayConfig = Config::get('alipay');
			self::$aop->gatewayUrl = $alipayConfig['gatewayUrl'];
			self::$aop->appId = $alipayConfig['app_id'];
			self::$aop->rsaPrivateKey = $alipayConfig['merchant_private_key'];
			self::$aop->alipayrsaPublicKey= $alipayConfig['alipay_public_key'];
			self::$aop->apiVersion = $alipayConfig['api_version'];
			self::$aop->signType = $alipayConfig['sign_type'];
			self::$aop->postCharset= $alipayConfig['charset'];
			self::$aop->notify_url = $alipayConfig['notify_url'];
			self::$aop->format= $alipayConfig['format'];
			self::$aop->debugInfo=true;
		}
	}
}