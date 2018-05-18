<?php
/**
 * 用户积分管理
 * author: GT
 * time: 2018.05.18
 */
 
namespace api\user\controller;
use cmf\controller\RestUserBaseController;
use think\Db;

class UserPointController extends RestUserBaseController {
	
	/** 
	 * 获取积分临时接口
	 */
	public function obtain(){
		if($this->request->isPost()){
			$userId = $this->getUserId();
			$point = $this->request->param('point');
			if(empty($point)){
				$this->error("请传入积分数量!");
			}
			// 先写日志，再写入数据表
			$action = $this->request->url();
			$data = [
				'user_id' 	=> $userId,
				'point'		=> $point,
				'create_time'	=> time(),
				'action'	=> $action
			];
			Db::name('user_point_log')->insert($data);
			$res = Db::name("user")->where("id", $userId)->setInc('point', $point);
			if($res){
				$this->success("请求成功!");
			}else{
				$this->error("请求失败!");
			}
		}
	}
	
	/**
	 * 我的积分
	 */
	public function mypoint(){
		if($this->request->isPost()){
			// 直接从父类中获取
			$point = $this->user['point'];
			$this->success("请求成功!", ['point' => $point]);
		}
	}
	
	/**
	 * 积分转余额
	 */
	public function point2balance(){
		if($this->request->isPost()){
			$userId = $this->getUserId();
			$point = $this->request->param('point');
			if(empty($point)){
				$this->error("请传入积分数量!");
			}
			if($point > $this->user['point']){
				$this->error("积分不足!");
			}
			// 积分与余额换算
			$balance = $point;
			// 先写日志
			Db::startTrans(); // 启动事务
			$action = $this->request->url();
			$data = [
				'user_id'		=> $userId,
				'create_time'	=> time(),
				'change'		=> $balance,
				'balance'		=> ($this->user['balance'] + $balance),
				'description'	=> "$action"
			];
			$res = Db::name('user_balance_log')->insert($data);
			if(!$res){
				Db::rollback(); // 回滚事务
				$this->error("请求失败!");
			}
			$data = [
				'user_id'		=> $userId,
				'create_time'	=> time(),
				'action'		=> "$action",
				'point'			=> $point
			];
			$res = Db::name('user_point_log')->insert($data);
			if(!$res){
				Db::rollback(); // 回滚事务
				$this->error("请求失败!");
			}
			$res = Db::name('user')->where('id', $userId)->setInc('balance', $balance);
			if(!$res){
				Db::rollback(); // 回滚事务
				$this->error("请求失败!");
			}
			$res = Db::name('user')->where('id', $userId)->setDec('point', $point);
			if(!$res){
				Db::rollback(); // 回滚事务
				$this->error("请求失败!");
			}
			Db::commit(); // 提交事务
			
			$this->success("请求成功!");
		}
	}
	
	/**
	 * 积分明细
	 */
	public function pointIndex(){
		$userId = $this->getUserId();
		$logs = Db::name('user_point_log')->where('user_id', $userId)->order('id DESC')->paginate(10);
		$this->success("请求成功!", $logs);
	}
}

