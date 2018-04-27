<?php
/**
 * 自助终端管理
 * author: GT
 * time: 2018.04.27 09:36
 */
 
namespace app\market\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class AdminMarketTerminalController extends AdminBaseController {
	
	/**
	 * 自助终端列表
	 */
	public function index(){
		$where = array();
		$store_id = $this->request->param('store_id');
		if(!empty($store_id)){
			$where['store_id'] = $store_id;
		}
		$status = $this->request->param('status');
		if(isset($status)){// 1: 正常 2: 停用
			if($status != 0){
				$where['a.status'] = $status;
			}
		}else{
			$where['a.status'] = 1;//默认显示正常状态下的终端
		}
		
		$terminals = Db::name('store_terminal')
						->alias('a')
						->field("a.*, b.name AS store_name")
						->join('__STORE__ b', "a.store_id = b.id", "LEFT")
						->where($where)
						->paginate(10);
		$pages = $terminals->render();
		
		$stores = Db::name('store')->field('id, name')->select();
		$this->assign('terminals', $terminals);
		$this->assign('stores', $stores);
		$this->assign('page', $pages);
		
		return $this->fetch();
	}
	
	/**
	 * 添加自助终端
	 */
	public function add(){
		$stores = Db::name('store')
					->alias('a')
					->field('a.id, a.name, b.name as adminstrator_name, b.mobile as adminstrator_mobile')
					->join('__ADMINSTRATOR__ b', 'a.adminstrator_id=b.id')
					->whereIn('a.status', ['1', '2'])
					->select();
		$this->assign('stores', $stores);
		
		return $this->fetch();
	}
	
	/**
	 * 添加自助终端提交
	 */
	public function addPost(){
		if($this->request->isPost()){
			$data = $this->request->param();
			$result = $this->validate($data, 'AdminMarketTerminal.add');
			if($result !== true){
				$this->error($result);
			}
			if(isset($data['store_id']) && $data['store_id'] == 0){// 未设置店铺
				unset($data['store_id']);
				//$data['store_id'] = null;
			}
			$data['salecount'] = 0;
			$data['status'] = 1;
			
			$result = Db::name('store_terminal')->insert($data);
			if($result){
				$this->success('添加自助终端成功!', url('AdminMarketTerminal/index'));
			}else{
				$this->error('添加自助终端失败!');
			}
		}
	}
	
	/**
	 * 编辑自助终端
	 */
	public function edit(){
		$terminal_id = $this->request->param('id');
		if(empty($terminal_id)){
			$this->error('请传入终端ID!');
		}
		$terminal = Db::name('store_terminal')->where('id',$terminal_id)->find();
		$this->assign('terminal', $terminal);
		
		$stores = Db::name('store')
					->alias('a')
					->field('a.id, a.name, b.name as adminstrator_name, b.mobile as adminstrator_mobile')
					->join('__ADMINSTRATOR__ b', 'a.adminstrator_id=b.id')
					->whereIn('a.status', ['1', '2'])
					->select();
		$this->assign('stores', $stores);
		
		return $this->fetch();
	}
	
	/**
	 * 编辑自助终端提交
	 */
	public function editPost(){
		if($this->request->isPost()){
			$data = $this->request->param();
			$result = $this->validate($data, 'AdminMarketTerminal.edit');
			if($result !== true){
				$this->error($result);
			}
			if(array_key_exists("salecount", $data)){
				unset($data['salecount']);
			}
			if(isset($data['store_id']) && $data['store_id'] == 0){
				$data['store_id'] = null;
			}
			$result = Db::name('store_terminal')->update($data);
			if($result){
				$this->success('编辑自助终端成功!', url('AdminMarketTerminal/index'));
			}else{
				$this->error('编辑自助终端失败!');
			}
		}
	}
	
	/**
	 * 删除终端
	 */
	public function delete(){
		$terminal_id = $this->request->param('id');
		if(empty($terminal_id)){
			$this->error('请传入终端ID!');
		}
		$salecount = Db::name('store_terminal')->where('id',$terminal_id)->limit(1)->column('salecount');
		if(count($salecount) == 0){
			$this->error('终端不存在!');
		}
		$salecount = $salecount['0'];
		if($salecount !== 0){
			$this->error('终端已售出商品，不可被删除!');
		}
		$result = Db::name('store_terminal')->where('id',$terminal_id)->delete();
		if($result){
			$this->success("删除终端成功!");
		}else{
			$this->error("删除终端失败!");
		}
	}
	
	/**
	 * 停用
	 */
	public function suspend(){
		$terminal_id = $this->request->param('id');
		if(empty($terminal_id)){
			$this->error('请传入终端ID!');
		}
		$result = Db::name('store_terminal')->where('id',$terminal_id)->update(['status'=>2]);
		if($result){
			$this->success("停用终端成功!");
		}else{
			$this->error("停用终端失败!");
		}
	}
	
	/**
	 * 启用
	 */
	public function cancelsuspend(){
		$terminal_id = $this->request->param('id');
		if(empty($terminal_id)){
			$this->error('请传入终端ID!');
		}
		$result = Db::name('store_terminal')->where('id',$terminal_id)->update(['status'=>1]);
		if($result){
			$this->success("启用终端成功!");
		}else{
			$this->error("启用终端失败!");
		}
	}
	
}