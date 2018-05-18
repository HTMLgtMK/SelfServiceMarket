<?php
/**
 * 会员余额
 * author: GT
 * time: 2018.05.18
 */
 
namespace api\user\controller;

use cmf\controller\RestUserBaseController;
use think\Db;

class UserBalanceController extends RestUserBaseController {
	
	/**
	 * 我的余额
	 */
	public function mybalance(){
		if($this->request->isPost()){
			// 直接从父类中获取
			$balance = $this->user['balance'];
			$this->success("请求成功!", ['balance' => $balance]);
		}
	}
	
	/**
	 * 余额明细
	 */
	public function balanceIndex(){
		if($this->request->isPost){
			$userId = $this->getUserId();
			$logs = Db::name('user_balance_log')->where('user_id', $userId)->order("id DESC")->paginate(10);
			$this->success("请求成功!", $logs);
		}
	}
	
}