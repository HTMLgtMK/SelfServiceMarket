<?php
/**
 * 用户授权请求控制器
 * author: GT
 * time: 2018.05.08 20:38
 */
 
namespace api\user\controller;

use think\Db;
use think\Validate;
use cmf\controller\RestBaseController;

class UserGrantController extends RestBaseController {
	
	private $constStr = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	private $constLength = 62;// 常量串长度为(26+26+10)
	private $tokenLength = 32;// token串长度
	
	/**
	 * 用户授权请求
	 */
	public function grantReq(){
		if($this->request->isPost()){
			// 生成授权token
			$result = "";
			for($i=0; $i<$this->tokenLength; ++$i){
				$pos = rand(0, $this->constLength-1);
				$result = $result . $this->constStr[$pos];
			}
			// 将token 插入到数据库中
			$url = $this->request->url();
			$time = time();
			$data = [
				'token'		=> "$result",
				'action'	=> "$url",
				'status'	=> "1",
				'create_time' => $time,
				'expire_time' => $time+5*60*1000
			];
			$id = Db::name('user_grant')->insertGetId($data);
			if(!empty($id)){
				$token = Db::name('user_grant')->where('id', $id)->find();
				$this->success("请求授权码成功!", ['token' => $token]);
			}else{
				$this->error("请求授权码失败!");
			}
		}
	}
	
	/**
	 * 查询授权状态
	 */
	public function queryGrantStatus(){
		if($this->request->isPost()){
			$id = $this->request->param('id');
			if(empty($id)){
				$this->error("请传入授权请求ID!");
			}
			$req = Db::name('user_grant')->where('id',$id)->find();
			if(!empty($req)){
				$status = $req['status'];
				$expire_time = $req['expire_time'];
				$now = time();
				if($now > $expire_time){
					if($req['status'] == 1){// 仍然出处于待授权，则修改为超时关闭
						Db::name('user_grant')->where('id', $id)->update("status", "3");
					}
					$this->success("请求成功!", ['status' => 3, 'status_detail' => '授权已超时!']);//所有
				}else{
					$status_detail = "";
					$user = null;
					switch($status){
						case 1:{
							$status_detail = "等待授权";
							break;
						}
						case 2:{
							$status_detail = "已授权!";
							$user = Db::name('user')
										->field("id, name, user_login, avatar, user_nickname, point, balance, user_level")
										->where('id', $req['user_id'])
										->find();
							$where = array();
							$where['b.create_time'] = ['<', time()];
							$where['b.expire_time'] = ['>', time()];
							$where['a.rest'] = ['>', '0'];
							$where['a.user_id'] = $user['id'];
							$discount = Db::name('discount_user')
											->alias('a')
											->field('a.id, a.discount_id, a.count, a.rest, a.create_time')
											->join("__DISCOUNT__ b", "a.discount_id = b.id")
											->where($where)
											->select();
							$user['discount'] = $discount;
							break;
						}
						case 3:{
							$status_detail = "授权已关闭!";
							break;
						}
						case 4:{
							$status_detail = "已扫描! 等待确认授权!";
							break;
						}
					}
					$this->success("请求成功!", ['status' => $status, 'status_detail' => $status_detail, 'user' => $user]);
				}
			}else{
				$this->error("授权请求不存在!");
			}
		}
	}
	
	/**
	 * 用户授权方法
	 */
	public function grant(){
		if($this->request->isPost()){
			if(empty($this->user)){
				$this->error("登陆已失效!");
			}
			$data = $this->request->param();
			$validate = new Validate([
				'token'	=> 'require'
			]);
			$validate->message([
				'token.require'		 => '请传入授权请求token串!'
			]);
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			$userId = $this->getUserId();
			$req = Db::name('user_grant')->where('token', $data['token'])->find();
			if(!empty($req)){
				//检查授权请求是否已经过期
				$now = time();
				if($now > $req['expire_time']){
					$this->error("授权请求已经过期!");
				}
				if($req['user_id'] != $userId){
					$this->error("非扫描用户, 不可授权!");
				}
				if($req['status'] == 2){// 已经授权，并且还在有效期内
					$this->success("授权成功!");
				}
				// 将用户id用于更新授权表
				$update = [
					'user_id' 	=> $userId,
					'status'	=> '2'
				];
				$result = Db::name('user_grant')->where('id', $req['id'])->update($update);
				if($result){
					$this->success("授权成功!");
				}else{
					$this->error("授权失败!");
				}
			}else{
				$this->error("授权请求不存在!");
			}
		}
	}
	
	/**
	 * 用户扫描二维码，检查授权请求状态
	 */
	public function scan(){
		if($this->request->isPost()){
			if(empty($this->user)){
				$this->error("登陆已失效!");
			}
			$data = $this->request->param();
			$validate = new Validate([
				'token'	=> 'require'
			]);
			$validate->message([
				'token.require'		 => '请传入授权请求token串!'
			]);
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			$userId = $this->getUserId();
			$req = Db::name('user_grant')->where('token', $data['token'])->find();
			if(!empty($req)){
				if($req['user_id'] !=0 && $req['user_id'] != $userId){// 已经被别的会员扫描
					$this->error("二维码已失效!");
				}
				$now = time();
				if($now > $req['expire_time']){
					$this->error("二维码已过期!");
				}
				if($req['status'] == 4){// status = 4 表示二维码已被扫描, 不需要更新数据库
					$this->success("状态正常!", ['req' => $req]);
				}// else 
				// 将用户插入到数据库中
				$update = [
					'user_id' 	=> $userId,
					'status'	=> '4'
				];// 
				$result = Db::name('user_grant')->where('id', $req['id'])->update($update);
				if($result){
					$this->success("状态正常!", ['req' => $req]);
				}else{
					$this->error("扫描失败!");
				}
			}else{
				$this->error("授权请求不存在!");
			}
		}
	}
	
	/**
	 * 关闭授权
	 */
	public function closeGrant(){
		if($this->request->isPost()){
			$id = $this->request->param("id");
			$token = $this->request->param("token");
			if(empty($id) && empty($token)){
				$this->error("授权请求ID和授权请求Token不能同时为空!");
			}
			$where = array();
			if(!empty($id)){
				$where['id'] = $id;
			}
			if(!empty($token)){
				$where['token'] = $token;
			}
			$req = Db::name('user_grant')->where($where)->find();
			if(empty($req)){
				$this->error("授权请求不存在!");
			}
			if($req['status'] != 1 && $req['status'] != 4){// status = 1, 4 表示等待授权
				$this->error("授权请求已授权或已关闭");
			}
			// 二维码已经超时, 也是继续关闭...
			$update = [
				'status' => '3'
			];
			$result = Db::name('user_grant')->where($where)->update($update);
			if($result){
				$this->success("关闭授权请求成功!");
			}else{
				$this->error("关闭授权请求失败!");
			}
		}
	}
}