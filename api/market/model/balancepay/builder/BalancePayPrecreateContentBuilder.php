<?php
/**
 * 余额支付 内容构造器
 * author: GT
 * time: 2018.05.24 
 */
 
namespace api\market\model\balancepay\builder;

class BalancePayPrecreateContentBuilder {
	/** 会员ID */
	private $userId;
	/** 交易Token */
	private $token;
	/** 商户交易单号 */
	private $outTradeNo;
	/** 总金额 */
	private $totalAmount;
	/** 优惠金额 */
	private $discountAmount;
	/** 支付金额 */
	private $payAmount;
	/** 交易开始时间 */
	private $timeStart;
	/** 交易过期时间 */
	private $timeExpire;
	/** 交易标题 */
	private $subject;
	/** 商品详情, json格式数据 */
	private $goodsDetail;
	/** 店铺ID */
	private $storeId;
	/** 终端ID */
	private $terminalId;
	// private $status
	
	/** 内容构造器 */
	private $content = array();
	
	public function setUserId($userId){
		$this->userId  = $userId;
		$this->content['user_id'] = $userId;
	}
	
	public function setOutTradeNo($outTradeNo){
		$this->outTradeNo = $outTradeNo;
		$this->content['out_trade_no'] = $outTradeNo;
	}
	
	public function setTotalAmount($totalAmount){
		$this->totalAmount = $totalAmount;
		$this->content['total_amount'] = $totalAmount;
	}
	
	public function setDiscountAmount($discountAmount){
		$this->discountAmount = $discountAmount;
		$this->content['discount_amount'] = $discountAmount;
	}
	
	public function setPayAmount($payAmount){
		$this->payAmount = $payAmount;
		$this->content['pay_amount'] = $payAmount;
	}
	
	public function setTimeStart($timeStart){
		$this->timeStart = $timeStart;
		$this->content['time_start'] = $timeStart;
	}
	
	public function setTimeExpire($timeExpire){
		$this->timeExpire = $timeExpire;
		$this->content['time_expire'] = $this->timeStart + $timeExpire;
	}
	
	public function setSubject($subject){
		$this->subject = $subject;
		$this->content['subject'] = $subject;
	}
	
	public function setGoodsDetailList($goodsDetail){
		$this->goodsDetail = $goodsDetail;
		$this->content['goods_detail'] = json_encode($goodsDetail);
	}
	
	public function setStoreId($storeId){
		$this->storeId = $storeId;
		$this->content['store_id'] = $storeId;
	}
	
	public function setTerminalId($terminalId){
		$this->terminalId = $terminalId;
		$this->content['terminal_id'] = $terminalId;
	}
	
	public function getContent(){
		return $this->content;
	}
	
}