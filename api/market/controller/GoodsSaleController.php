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
	public function submit() {
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
			$data['goods_detail'] = base64_decode($data['goods_detail']);
			//将数据插入数据库中
			$data['create_time'] = time();
			$data['pay_detail'] = "";//暂时无内容
			$data['modify_time'] = 0;
			$data['status'] = 1; //订单已生成，等待付款
			
			$deal_id = Db::name('sale')->insertGetId($data);
			if(empty($deal_id)){
				$this->error("预下单失败!");
			}
			//请求预下单结果
			$result = array();
			
			//支付宝预下单
			$alipayResult = $this->qrPay($data, $deal_id);
			$result['alipay'] = $alipayResult;
			
			$this->success("请求成功!", '' ,$result);
		}else{
			$this->error("请求方式错误!");
		}
	}
	
	/**
	 * 支付宝预下单
	 * @param $postData 上传的数据
	 * @param $dbId 插入数据库中的数据
	 */
	private function qrPay($postData, $dbId) {
		//解析上传的goods_detail数据,生成适合支付宝的数据
		$goodsDetailList = $this->myGoodsObjs2GoodsDetail($postData['goods_detail']);
		$timeExpress = "5m";//5min超时
		$subject = "deal".$dbId;
		
		// 创建请求builder，设置请求参数
		$qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
		$qrPayRequestBuilder->setOutTradeNo($dbId);// $outTradeNo
		$qrPayRequestBuilder->setTotalAmount($postData['total_amount']);
		$qrPayRequestBuilder->setTimeExpress($timeExpress);
		$qrPayRequestBuilder->setSubject($subject);
		$qrPayRequestBuilder->setDiscountableAmount($postData['discount_amount']);
		$qrPayRequestBuilder->setGoodsDetailList($goodsDetailList);
		$qrPayRequestBuilder->setStoreId($postData['store_id']);
		$qrPayRequestBuilder->setTerminalId($postData['terminal_id']);
		
		$bizContent = $qrPayRequestBuilder->getBizContent();
		//请求支付宝API, 请求二维码
		$alipyRequest = new \AlipayTradePrecreateRequest();//预下单
		$alipyRequest->setBizContent($bizContent);
		
		$result = self::$aop->execute($alipyRequest);
		return $result;
	}
	
	/**
	 * 自定义的商品类转换成支付宝接口可接受的商品类
	 * @param $goodsObjs 数组类型的商品类json字符串
	 * @return array 
	 */
	private function myGoodsObjs2GoodsDetail($goodsObjs){
		$goodsArr = json_decode($goodsObjs,true);//强制转换成数组
		$goodsDetailList = array();
		foreach($goodsArr as $goods){
			$goodsDetail = new GoodsDetail();
			$goodsDetail->setGoodsId($goods['goods_id']);
			$goodsDetail->setGoodsName($goods['name']);
			$goodsDetail->setPrice($goods['price']);
			$goodsDetail->setQuantity(1);//默认1
			$goodsDetailList[] = $goodsDetail->getGoodsDetail();//是需要调用这个方法，否则获取为空
		}
		return $goodsDetailList;
	}
}
 
 
