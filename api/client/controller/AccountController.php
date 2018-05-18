<?php
/**
 * 我的消费 功能接口
 * author: GT
 * time: 2018.05.11
 */

namespace api\client\controller;

use cmf\controller\RestUserBaseController;
use think\Db;

class AccountController extends RestUserBaseController {
	
	/*最近一周的消费情况*/
	public function index(){
		$now = time();
		$weekago = strtotime("-7 days");
		$userId = $this->getUserId();
		$where = [
			'a.user_id'		=> $userId,
			'a.create_time'	=> ['between', [$weekago, $now]]
		];
		$sales = Db::name('sale')
					->alias('a')
					->field('a.*, b.name as `store_name`')
					->join('__STORE__ b', "a.store_id = b.id")
					->where($where)
					->order('a.id desc')
					->select();
		$this->success("请求成功!", ['sales' => $sales]);
	}
	
	/*分页账单*/
	public function account(){
		$userId = $this->getUserId();
		$where = [
			'a.user_id'		=> $userId
		];
		$sales = Db::name('sale')
					->alias('a')
					->field('a.*, b.name as `store_name`')
					->join('__STORE__ b', "a.store_id = b.id")
					->where($where)
					->order('a.id desc')
					->paginate(10);
		$this->success("请求成功!", ['sales'=> $sales]);
	}
	
}