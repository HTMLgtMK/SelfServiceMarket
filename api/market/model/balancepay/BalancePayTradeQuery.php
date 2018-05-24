<?php
/**
 * 余额支付 交易状态查询
 * author: GT
 * time: 2018.05.24 10:22
 */
 
namespace api\market\model\balancepay;

use think\Db;
use think\Validate;

class BalancePayTradeQuery {
	
	/** 支付结果查询 */
	public static function query($data){
		$validate = new Validate([
			'out_trade_no'	=> 'require',
			'token'			=> 'require'
		]);
		$validate->message([
			'out_trade_no.require'	=> '请传入商户订单号!',
			'token.require'			=> '请传入交易Token!'
		]);
		$result = array();
		if(!$validate->check($data)){
			$result['code'] = 0;
			$result['msg'] = $validate->getError();
			$result['data'] = ['out_trade_no'=>$data['out_trade_no']];
			return $result;
		}
		$pay = Db::name('balance_pay')->where($data)->find();
		if(empty($pay)){
			$result['code'] = 0;
			$result['msg'] = "交易不存在!";
			$result['data'] = ['out_trade_no'=>$data['out_trade_no']];
			return $result;
		}
		$status = $pay['status'];
		if($status == 1 || $status == 2){
			if(time() > $pay['time_expire']){ // 交易已经过期
				$update = ['modify_time'=>time(), 'status'=>4];
				$res = Db::name('balance_pay')->where($data)->update($update);
				// if $res
				$status = 4; // 强行过期
			}
		}
		$status_detail = '';
		switch($status){
			case 1:{
				$status_detail = "预下单请求成功, 用户未扫码!";
				break;
			}
			case 2:{
				$status_detail = "用户已扫码, 等待付款!";
				break;
			}
			case 3:{
				$status_detail = "交易成功!";
				break;
			}
			case 4:{
				$status_detail = "交易关闭!";
				break;
			}
			case 5:{
				$status_detail = "交易取消!";
				break;
			}
			case 6:{
				$status_detail = "未知状态...";
				break;
			}
		}
		$result['code'] = 1;
		$result['msg'] = '交易状态请求成功!';
		$result['data'] = [
			'out_trade_no'		=> $data['out_trade_no'], 
			'status'			=> $status,
			'status_detail'		=> $status_detail
		];
		return $result;
	}
	
}

