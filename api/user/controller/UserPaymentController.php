<?php 
/**
 * 会员支付控制器
 * author: GT
 * time: 2018.05.24 21:15
 */
 
namespace api\user\controller;

use think\Db;
use think\Validate;
use cmf\controller\RestUserBaseController;

class UserPaymentController extends RestUserBaseController {
	
	/**
	 * 检查余额支付交易状态
	 */
	public function checkBalancePayStatus(){
		if($this->request->isPost()){
			$token = $this->request->param('token');
			if(empty($token)){
				$this->error("请传入支付Token!");
			}
			$userId = $this->getUserId();
			$where = [
				'token' 	=> $token,
				'user_id'	=> $userId
			]; // 可能是另外一位用户的支付请求, 使用`user_id`过滤
			$payment = Db::name('balance_pay')->where($where)->find();
			if(empty($payment)){
				$this->error("不存在该支付请求!");
			}
			//  1:订单尚未创建, 2:等待付款, 3:交易成功, 4:交易关闭, 5:交易取消
			switch($payment['status']){
				case 3:
				case 4:
				case 5:{
					$this->success("请求成功!", $payment);
					break;
				}
				case 1:
				case 2:{
					if(time() > $payment['time_expire']){ // 交易已经过期
						$update = ['status'=>4, 'modify_time'=>time()];
						Db::name('balance_pay')->where('id', $payment['id'])->update($update);
						$payment['status'] = 4;
					}else{
						Db::name('balance_pay')->where('id', $payment['id'])->update(['status'=>2]);
						$payment['status'] = 2;
					}
					$this->success("请求成功!", $payment);
					break;
				}
				default:{
					// 未知
					$this->success("请求成功!", $payment);
				}
			}
		}
	}
	
	/**
	 * 余额支付提交
	 */
	public function balance_pay(){
		if($this->request->isPost()){
			$data = $this->request->param();
			$validate = new Validate([
				'pay_password'		=> 'require',
				'token'				=> 'require',
			]);
			$validate->message([
				'pay_password.require' 		=> '请传入支付密码!',
				'token.require'				=> "请传入支付Token!"
			]);
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			$userId = $this->getUserId();
			$where = [
				'token'		=> $data['token'],
				'user_id'	=> $userId
			];
			$payment = Db::name('balance_pay')->where($where)->find();
			if(empty($payment)){
				$this->error("不存在该支付请求!");
			}
			// 验证支付密码
			$userPayShadow = Db::name('user_pay_shadow')->where('user_id', $userId)->find();
			if(empty($userPayShadow)){
				$this->error("用户未设置支付密码!");
			}
			if($userPayShadow != $data['pay_password']){
				$this->error("支付密码不正确!");
			}
			if($this->user['balance'] < $payment['pay_amount']){
				$this->error('余额不足!');
			}
			// 开启事务
			Db::startTrans();
			$res = Db::name('balance_pay')->where('id', $payment['id'])->update(['status'=>3, 'modify_time'=>time()]);
			if(!$res){
				Db::rollback();
				$this->error("支付失败!");
			}
			$action = $this->request->url();
			$userRestBalance = $user['balance']-$payment['pay_amount'];
			$balanceLog = [
				'user_id'		=> $userId,
				'create_time'	=> time(),
				'change'		=> (-1)*$payment['pay_amount'],
				'balance'		=> $userRestBalance,
				'description'	=> $action,
				'remark'		=> '余额支付'
			];
			$res = Db::name('user_balance_log')->insert($balanceLog);
			if(!$res){
				Db::rollback();
				$this->error("支付失败!");
			}
			$res = Db::name('user')->where('id', $userId)->update(['balance'=>$userRestBalance]);
			if(!$res){
				Db::rollback();
				$this->error("支付失败!");
			}
			Db::commit();
			$this->success("支付成功!");
		}
	}
}
