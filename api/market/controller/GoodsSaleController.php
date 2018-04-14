<?php
/**
 * 商品交易接口控制器
 * author: GT
 * time: 2018.04.13 19:30
 */

namespace api\market\controller;

use api\market\controller\GoodsSaleBaseController;
use think\Db;
use think\Validate;
use api\market\model\builder\GoodsDetail;
use api\market\model\builder\AlipayTradePrecreateContentBuilder;

class GoodsSaleController extends GoodsSaleBaseController {
	
	/**
	 * 预下单, 提交交易信息,
	 * 只接受POST方法的变量
	 */
	function submit(){
		if($this->request->isPost()){
			$data = $this->request->param();
			$validate = new Validate([
				'user_id'			=> 'require',
				'store_id' 			=> 'require',
				'terminal_id'		=> 'require',
				'pay_amount'		=> 'require',
				'discount_amount'	=> 'require',
				'total_amount'		=> 'require',
				'goods_detail'		=> 'require'
			]);
			$validate->message([
				'user_id.require'			=> '请先登陆vip或选择用户类型!',
				'store_id.require'			=> '请传入商铺id',
				'terminal_id.require'		=> '请传入收银终端ID',
				'pay_amount.require'		=> '请传入支付金额!',
				'discount_amount.require'	=> '请传入折扣金额!',
				'total_amount.require'		=> '请传入总金额!',
				'goods_detail.require'		=> '请传入商品详情!'
			]);
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			
			//将数据插入数据库中
			$data['create_time'] = time();
			$data['pay_detail'] = "";//暂时无内容
			$data['modify_time'] = 0;
			$data['status'] = 1; //订单已生成，等待付款
			
			$deal_id = Db::name('sale')->insertGetId($data);
			if(empty($deal_id)){
				$this->error("预下单失败!");
			}
			$this->qrPay($data, $deal_id);
		}else{
			$this->error("请求方式错误!");
		}
	}
	
	private function qrPay($postData, $dbId){
		//解析上传的goods_detail数据,生成适合支付宝的数据
		$goodsDetailList = json_decode($postData['goods_detail'], JSON_UNESCAPED_UNICODE);
		$timeExpress = "5m";//5min超时
		$subject = "deal".$dbId;
		
		// 创建请求builder，设置请求参数
		$qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
		$qrPayRequestBuilder->setOutTradeNo($dbId);// $outTradeNo
		$qrPayRequestBuilder->setTotalAmount($postData['total_amount']);
		$qrPayRequestBuilder->setTimeExpress($timeExpress);
		$qrPayRequestBuilder->setSubject($subject);
		//$qrPayRequestBuilder->setBody($body);
		$qrPayRequestBuilder->setDiscountableAmount($postData['discount_amount']);
		//$qrPayRequestBuilder->setExtendParams($extendParamsArr);
		$qrPayRequestBuilder->setGoodsDetailList($goodsDetailList);
		//$qrPayRequestBuilder->setStoreId($storeId);
		//$qrPayRequestBuilder->setOperatorId($operatorId);
		//$qrPayRequestBuilder->setAlipayStoreId($alipayStoreId);
		
		$bizContent = $qrPayRequestBuilder->getBizContent();
		//请求支付宝API, 请求二维码
		$alipyRequest = new \AlipayTradePrecreateRequest();//预下单
		$alipyRequest->setBizContent($bizContent);
		
		$result = self::$aop->execute($alipyRequest);
		//获取返回值
		$responseNode = str_replace(".", "_", $alipyRequest->getApiMethodName()) . "_response";
		
		$resultCode = $result->$responseNode->code;
		$resultMsg = $result->$responseNode->msg;
		$sign = $result->sign;
		
		echo $resultCode,"</br>";
		echo $resultMsg,"</br>";
		echo $sign,"</br>";
			
		if(!empty($resultCode)&&$resultCode == 10000){
			echo "成功";
		} else {
			$resultSubCode = $result->$responseNode->sub_code;
			$resultSubMsg = $result->$responseNode->sub_msg;
			echo $resultSubCode,"</br>";
			echo $resultSubMsg,"</br>";
			echo "失败";
		}
	}
}
 
 
