<?php
/**
 * 余额支付 预下单请求
 * author: GT
 * time: 2018.05.24 09:14
 */
 
namespace api\market\model\balancepay;

use think\Db;
use think\Validate;

class BalancePayPrecreateRequest {
	
	/** 预下单请求 */
	public static function request($content){
		$validate = new Validate([
			'user_id' 			=> 'require',
			'out_trade_no'		=> 'require',
			'total_amount'		=> 'require',
			'discount_amount'	=> 'require',
			'pay_amount'		=> 'require',
			'time_start'		=> 'require',
			'time_expire'		=> 'require',
			'subject'			=> 'require',
		]);
		$validate->message([
			'user_id.require' 			=> '请传入会员ID!',
			'out_trade_no.require'		=> '请传入商家订单号!',
			'total_amount.require'		=> '请传入总金额!',
			'discount_amount.require'	=> '请传入优惠金额!',
			'pay_amount.require'		=> '请传入支付金额!',
			'time_start.require'		=> '请传入交易开始时间!',
			'time_expire.require'		=> '请传入交易过期时间!',
			'subject.require'			=> '请传入交易标题!'
		]);
		if(!$validate->check($content)){
			$result = BalancePayPrecreateRequest::fillResult(0, $validate->getError(), 
				['user_id'=>$content['user_id'], 'out_trade_no'=>$content['out_trade_no']]);
			return $result;
		}
		if($content['total_amount'] < 1){
			$result = BalancePayPrecreateRequest::fillResult(0 , "总金额不符合要求!",
				['user_id'=>$content['user_id'], 'out_trade_no'=>$content['out_trade_no']]);
			return $result;
		}
		// 是否已经创建订单
		$balancePay = Db::name('balance_pay')->where('out_trade_no', $content['out_trade_no'])->find();
		if(!empty($balancePay)){
			$result = BalancePayPrecreateRequest::fillResult(0, "预下单已经创建!",
				['user_id'=>$content['user_id'], 'out_trade_no'=>$content['out_trade_no']]);
			return $result;
		}
		$token = md5(uniqid());
		$content['token'] = $token;
		$content['status'] = '1'; // 预下单创建, 用户未扫码
		$suffix = BalancePayPrecreateRequest::randSuffix(6);
		$content['id'] = 'BPAY' . date('YmdHis'). $suffix;
		$res = Db::name('balance_pay')->insert($content);
		if(!empty($res)){
			$result = BalancePayPrecreateRequest::fillResult(1, "预下单请求成功!",
				[
					'id'			=> $content['id'],
					'user_id'		=> $content['user_id'],
					'out_trade_no'	=> $content['out_trade_no'],
					'token'			=> $content['token'],
				]);
			return $result;
		}
	}
	
	private static function fillResult($code, $msg, $data){
		$result = [
			'code'	=> $code,
			'msg'	=> $msg,
			'data'	=> $data
		];
		return $result;
	}
	
	private static function randSuffix($len){
		if($len < 10){ // 长度小于int类型最大数
			$min = pow(10, $len);
			$max = $min*10 - 1;
			$n = rand($min, $max);
			return strval($n);
		}else{
			$str = "0123456789";
			$s = '';
			for($i=0; $i<$len; ++$i){
				$s .= $str[rand(0, 9)];
			}
			return $s;
		}
	}
}
