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
use api\market\model\common\builder\GoodsDetail;//将支付宝的商品详情类改造公用
use api\market\model\alipay\builder\AlipayTradePrecreateContentBuilder;
use api\market\model\wxpay\NativePay;//使用example中的类

class GoodsSaleController extends GoodsSaleBaseController {
	
	/**
	 * 取消交易
	 */
	public function revokeDeal(){
		if($this->request->isPost()){
			$outTradeNo = $this->request->param("out_trade_no");
			if(empty($outTradeNo)){
				$this->error("请传入商户订单号!");
			}
			$status = Db::name("sale")->where('id',"$outTradeNo")->limit(1)->column("status");
			if(empty($status)){
				$this->error("交易不存在!");
			}
			$status = $status['0'];
			if($status == 2 || $status == 3){
				$this->error("交易关闭或交易成功!");
			}
			$arr = [
				'status' 		=> 4,
				'modify_time'	=> time()
			];
			$result = Db::name("sale")->where('id',"$outTradeNo")->update($arr);//4 取消交易
			if($result){
				//改变商品的状态
				$goods_detail = Db::name('sale')->where('id',"$outTradeNo")->limit(1)->column('goods_detail');
				if(count($goods_detail)>0){
					$goods_detail = $goods_detail['0'];
					$goods_detail = json_decode($goods_detail,true);
					foreach($goods_detail as $goods){
						$goods_id = $goods['id'];
						Db::name('goods')->where('id',"$goods_id")->update(['status'=>1]);//1:待售
					}
				}
				$this->success("取消交易成功!");
			}else{
				$this->error("取消交易失败!");
			}
		}else{
			$thisi->error("请求方式错误!");
		}
	}
	
	/**
	 * 微信支付线下交易查询
	 */
	public function wxpayQuery(){
		if($this->request->isPost()){
			$data = $this->request->param();
			$validate = new Validate([
				"out_trade_no" 		=> "require"
			]);
			$validate->message([
				"out_trade_no.require" 	=> "请传入商户订单号!"
			]);
			if(!($validate->check($data))){
				$this->error($validate->getError());
			}
			$outTradeNo = $data['out_trade_no'];//商户订单号, 即为交易表中的id
			$input = new \WxPayOrderQuery();
			$input->SetOut_trade_no($outTradeNo);
				$result = \WxPayApi::orderQuery($input);
				if($result['return_code'] == "SUCCESS"){
					if($result['result_code'] == "SUCCESS"){
						$trade_status = $result['trade_state'];//交易状态
						/**
						 * SUCCESS—支付成功 -- 3 
						 * REFUND—转入退款  -- 2
						 * NOTPAY—未支付    -- 1
						 * CLOSED—已关闭    -- 2
						 * REVOKED—已撤销（刷卡支付） -- 2
						 * USERPAYING--用户支付中     -- 1
						 * PAYERROR--支付失败(其他原因，如银行返回失败) -- 1
						 */
						if( ($trade_status == "NOTPAY") || ($trade_status == "PAYERROR")) {
							$this->success("请求成功!", '', 
								["code"=>1, "status"=>"$trade_status", "out_trade_no"=>"$outTradeNo"]);
						}else {
							$code = 0;
							if($trade_status == "CLOSED" || $trade_status == "REFUND" 
								|| $trade_status == "REVOKED" ){
								$code = 2;
							}
							if($trade_status == "SUCCESS") {
								$code = 3;
								$res  = $this->updateGoodsStatus($outTradeNo, $code);
							}
							//更新数据库
							$wxpay_detail = json_encode($result);
							$deal = Db::name('sale')->where('id',"$outTradeNo")->find();
							if(!empty($deal['pay_detail'])){
								$pay_detail = json_decode($deal['pay_detail']);
								$pay_detail['wxpay'] = $wxpay_detail;
							}else{
								$pay_detail = array();
								$pay_detail['wxpay'] = $wxpay_detail;
							}
							$arr = [
								"pay_detail" => json_encode($pay_detail),
								"modify_time" => time()
							];
							if($deal['status'] == 2){// 若原来的交易状态为关闭，则修改为现在的状态
								$arr['status'] = $code;
							}
							$res = Db::name('sale')->where('id',"$outTradeNo")->update($arr);
							if($res){
								$this->success("请求成功!", '',
									['code'=>"$code", "status"=>"$trade_status", "out_trade_no"=>"$outTradeNo"]);
							}else{// 支付成功，但是数据库更新失败!
								$this->success("交易成功,但更新数据库失败!", '',
									["code"=>"-1","status"=>"UPDATE_DB_ERR","out_trade_no"=>"$outTradeNo"]);
							}
						}
					}else{//业务请求错误 result_code == FAIL
						$this->error("请求错误", '', ['code'=>"-1", 'status'=>"QUERY_FAIL", "out_trade_no"=>"$outTradeNo"]);
					}
				}else{//通信错误  return_code == FAIL
					$this->error("请求错误", '', ['code'=>"-1", 'status'=>"UNKONE_STATUS", "out_trade_no"=>"$outTradeNo"]);
				}
		}else{
			$this->error("请求方式错误!");
		}
	}
	
	/**
	 * 支付宝线下交易查询
	 */
	public function alipayQuery(){
		if($this->request->isPost()){
			$data = $this->request->param();
			$validate = new Validate([
				"out_trade_no" 		=> "require"
			]);
			$validate->message([
				"out_trade_no.require" => "请传入商户订单号!"
			]);
			if(!($validate->check($data))){
				$this->error($validate->getError());
			}
			$outTradeNo = $data['out_trade_no'];//商户订单号, 即为交易表中的id
			$bizContent = "{\"out_trade_no\":\"".$outTradeNo."\"}";
			
			$request = new \AlipayTradeQueryRequest();
			$request->setBizContent($bizContent);
			$result = self::$aop->execute($request);
			$responseNode = $result->alipay_trade_query_response;
			$code = $responseNode->code;
			if($code == "10000"){// 请求返回正常
				$trade_status = $responseNode->trade_status;//交易状态
				if($trade_status == "WAIT_BUYER_PAY"){//交易创建，等待买家付款
					$this->success("查询成功", '', 
						["code"=>"1","status"=>"$trade_status","out_trade_no"=>"$outTradeNo"]);
				}else if($trade_status == "TRADE_CLOSED" 
					|| $trade_status == "TRADE_SUCCESS" 
					|| $trade_status == "TRADE_FINISHED" ){
						// TRADE_CLOSED: 未付款交易超时关闭，或支付完成后全额退款
						// TRADE_SUCCESS: 交易支付成功
						// TRADE_FINISHED: 交易结束，不可退款
						// 将数据库中的数据状态修改成对应状态，将pay_detail赋值为result
						if($trade_status == "TRADE_CLOSED"){
							$arr['status'] = '2';
						}else if( $trade_status == "TRADE_SUCCESS" ){
							$arr['status'] = '3';
						}else if( $trade_status == "TRADE_FINISHED" ){
							$arr['status'] = '3';
						}
						$res_code = $arr['status'];//返回客户端的码
						$json_result = json_encode($result);
						$arr['pay_detail'] = json_encode(['alipay' => $json_result ]);
						$arr['modify_time'] = time();
						$res = Db::name('sale')->where("id","$outTradeNo")->update($arr);//更新交易表
						if(!empty($res)){//更新交易表成功
							//更新商品表，修改交易涉及的商品
							$res  = $this->updateGoodsStatus($outTradeNo, $res_code);
							if($res){
								$this->success("查询成功!",'',
									["code"=>"$res_code","status"=>"$trade_status","out_trade_no"=>"$outTradeNo"]);
							}else{
								$this->success("交易成功,但更新数据库失败!", '',
									["code"=>"-1","status"=>"UPDATE_DB_ERR","out_trade_no"=>"$outTradeNo"]);
							}
						}else{//交易成功,但更新数据库失败
							$this->success("交易成功,但更新数据库失败!", '',
								["code"=>"-1","status"=>"UPDATE_DB_ERR","out_trade_no"=>"$outTradeNo"]);
						}
				}else{
					$this->success("未知状态!", '',
						["code"=>"-1","status"=>"UNKONE_STATUS","out_trade_no"=>"$outTradeNo"]);
				}
			}else{
				$subCode = $responseNode->sub_code;
				if($subCode == "ACQ.TRADE_NOT_EXIST"){
					//还没有顾客扫描二维码，支付宝订单尚未创建
					//"二维码被扫过一次后，订单即会创建，不能再次被扫。"
					$this->success("查询成功", '',
						["code"=>"1","status"=>"$subCode","out_trade_no"=>"$outTradeNo"]);
				}
				$this->error("请求错误", '', ['response'=>json_encode($responseNode)]);
			}
		}else{
			$this->error("请求方式错误!");
		}
	}
	
	
	/**
	 * 提交订单信息
	 * @return 返回商户订单号
	 * 业务流程:
	 * 1. 提交订单到商户后台, 获取商户订单号
	 * 2. 支付宝支付或微信支付 根据商户订单号 预下单，获取二维码信息
	 */
	public function submit(){
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
			$discount_detail = base64_decode($data['discount_detail']);
			unset($data['discount_detail']);
			//将数据插入数据库中
			$id = date("YmdHis",time()). strval(rand(100000,999999));//交易单号
			$data['id'] = $id;
			$data['create_time'] = time();
			$data['pay_detail'] = "";//暂时无内容
			$data['modify_time'] = 0;
			$data['status'] = 1; //订单已生成，等待付款
			
			$res = Db::name('sale')->insert($data);
			if(empty($res)){
				$this->error("提交订单失败!");
			}else{
				//将所提交的订单中的全部商品锁定
				$goods_detail = json_decode($data['goods_detail'],true);
				foreach($goods_detail as $goods){
					$goods_id = $goods['id'];
					Db::name('goods')->where('id',"$id")->update(['status'=>"3"]);//3:锁定
				}
				//修改优惠使用信息(撤销时也不恢复优惠的使用)
				$discount_detail = json_decode($discount_detail, true);
				foreach($discount_detail as $discount){
					$discount_id = $discount['id'];
					if($discount['rest'] == 2147483647){//表示不减少优惠数量
						continue;
					}
					$rest = $discount['rest'] - $discount['use'];
					Db::name('discount')->where('id',"$discount_id")->update(['rest'=>$rest]);
				}
				$this->success("提交订单成功!",'' ,['out_trade_no'=>"$id"]);
			}
		}else{
			$this->error("请求方式错误!");
		}
	}

	/**
	 * 微信支付预下单 -- 模式二
	 * @return 微信支付预下单请求结果
	 */
	public function wxpay_qrpay(){
		/**
		 * 流程：
		 * 1、调用统一下单，取得code_url，生成二维码
		 * 2、用户扫描二维码，进行支付
		 * 3、支付完成之后，微信服务器会通知支付成功
		 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
		 */
		if($this->request->isPost()){
			$outTradeNo = $this->request->param("out_trade_no");
			if(empty($outTradeNo)){
				$this->error("请传入商户订单号!");
			}
			$deal = Db::name('sale')->where('id', "$outTradeNo")->find();
			if(empty($deal)){
				$this->error("订单不存在!");
			}
			$goodsDetailList = $this->myGoodsObjs2GoodsDetail($deal['goods_detail']);
			$goods_detail_str = json_encode($goodsDetailList);
			$body = $outTradeNo;
			$client_ip = get_client_ip();//获取终端IP地址
			$notify = new NativePay();
			$input = new \WxPayUnifiedOrder();
			$input->SetBody($body);//商品标题
			//$input->SetAttach();
			$input->SetOut_trade_no($outTradeNo);//商家订单号
			$input->SetTotal_fee(intval($deal['pay_amount']));//支付额, 微信支付以分为单位
			$input->SetTime_start(date("YmdHis"));//交易开始时间
			$input->SetTime_expire(date("YmdHis", time() + 600));//交易结束时间
			$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");//通知回调接口,必须设置。。
			$input->SetTrade_type("NATIVE");
			$input->SetProduct_id($outTradeNo);//trade_type=NATIVE时（即扫码支付），此参数必传。此参数为二维码中包含的商品ID，商户自行定义。
			$input->SetSpbill_create_ip($client_ip);//终端ip
			$input->setDetail($goods_detail_str);//商品详情json数组格式
			
			$result = $notify->GetPayUrl($input);//执行 WxPayApi::unifiedOrder($input);
			if(empty($result)){
				$this->error("预下单请求失败!");
			}else{
				$result['out_trade_no'] = $outTradeNo;//手动返回商户生成的订单号
				$this->success("预下单请求成功!", '', ['wxpay'=>$result]);
			}
		}else{
			$this->error("请求方式错误!");
		}
	}
	
	/**
	 * 支付宝预下单
	 * @return 支付宝预下单结果
	 */
	public function alipay_qrpay() {
		if($this->request->isPost()){
			$outTradeNo = $this->request->param("out_trade_no");
			if(empty($outTradeNo)){
				$this->error("请传入商户订单号!");
			}
			$deal = Db::name('sale')->where('id', "$outTradeNo")->find();
			if(empty($deal)){
				$this->error("订单不存在!");
			}
			//解析上传的goods_detail数据,生成适合支付宝的数据
			$goodsDetailList = $this->myGoodsObjs2GoodsDetail($deal['goods_detail']);
			$timeExpress = "5m";//5min超时
			$subject = $outTradeNo;
			
			// 创建请求builder，设置请求参数
			$qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
			$qrPayRequestBuilder->setOutTradeNo($outTradeNo);// $outTradeNo
			$qrPayRequestBuilder->setTotalAmount(sprintf("%.2f",$deal['total_amount']/100));
			$qrPayRequestBuilder->setTimeExpress($timeExpress);
			$qrPayRequestBuilder->setSubject($subject);
			$qrPayRequestBuilder->setUndiscountableAmount(sprintf("%.2f",($deal['total_amount']-$deal['discount_amount'])/100));
			$qrPayRequestBuilder->setDiscountableAmount(sprintf("%.2f",$deal['discount_amount']/100));
			$qrPayRequestBuilder->setGoodsDetailList($goodsDetailList);
			$qrPayRequestBuilder->setStoreId($deal['store_id']);
			$qrPayRequestBuilder->setTerminalId($deal['terminal_id']);
			
			$bizContent = $qrPayRequestBuilder->getBizContent();
			//请求支付宝API, 请求二维码
			$alipyRequest = new \AlipayTradePrecreateRequest();//预下单
			$alipyRequest->setBizContent($bizContent);
			
			$result = self::$aop->execute($alipyRequest);
			if(empty($result)){
				$this->error("预下单请求失败!");
			}else{
				$this->success("预下单请求成功!", '', ['alipay'=>$result]);
			}
		}else{
			$this->error("请求方式错误!");
		}
	}
	
	/**
	 * 支付成功修改商品表中商品状态
	 * @param $deal_id 交易ID
	 * @param $status 1:待支付, 2:交易关闭, 3:交易成功, 4:交易关闭
	 * @return mix 
	 */
	private function updateGoodsStatus($deal_id, $status){
		$goods_status = 0;
		switch($status){
			case 1:
			case 2:{
				$goods_status = 1;break;
			}
			case 3:
			case 4:{
				$goods_status = 2;break;
			}
		}
		$deal = Db::name('sale')->where("id","$deal_id")->find();
		if(!empty($deal)){
			$goods_detail = $deal['goods_detail'];
			$goods_detail = json_decode($goods_detail, true);//强制转成数组
			foreach($goods_detail as $goods){
				$goods_id = $goods['goods_id'];
				Db::name("goods")->where('id',"$goods_id")->update(['status'=>"$goods_status"]);
			}
			//给店铺和终端添加销量
			Db::name('store_terminal')->where('id', $deal['terminal_id'])->setInc('salecount', $deal['total_amount']);
			return true;
		}else{
			return false;
		}
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
 
 
