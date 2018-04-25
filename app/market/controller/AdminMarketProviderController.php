<?php
/**
 * 无人超市供应商管理
 * author: GT
 * time: 2018/04/24 14:44
 */

namespace app\market\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\Validate;

class AdminMarketProviderController extends AdminBaseController {
	
	/**
	 * 供应商列表
	 */
	public function index(){
		$where = array();
		$keyword = $this->request->param("keyword");
		if(!empty($keyword)){
			$where['name | address'] = ['LIKE', "%$keyword%"];
		}
		
		$providers = Db::name("provider")->where($where)->paginate(10);
		$providers->appends($keyword);
		$pages = $providers->render();
		
		$this->assign("providers", $providers);
		$this->assign("page", $pages);
		
		return $this->fetch();
	}
	
	/**
	 * 添加商品
	 */
	public function add(){
		return $this->fetch();
	}
	
	/**
	 * 添加商品提交
	 */
	public function addPost(){
		if($this->request->isPost()){
			$validate = new Validate([
				'name'		=> 'require|unique:provider,name',
				'address'	=> 'require'
			]);
			$validate->message([
				'name.require' 		=> "请输入供应商名称!",
				'name.unique'		=> "供应商已存在!",
				'address.require'	=> "请输入供应商地址!"
			]);
			$data = $this->request->param();
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			$result = Db::name('provider')->insert($data);
			if($result){
				$this->success("添加供应商成功!");
			}else{
				$this->error("添加供应商失败!");
			}
		}
	}
	
	/**
	 * 编辑供应商
	 */
	public function edit(){
		$provider_id = $this->request->param('id');
		if(empty($provider_id)){
			$this->error("请传入供应商ID!");
		}
		$provider = Db::name('provider')->where('id',"$provider_id")->find();
		$this->assign("provider", $provider);//! 供应商名称不可以修改
		return $this->fetch();
	}
	
	/**
	 * 编辑商品提交
	 */
	public function editPost(){
		if($this->request->isPost()){
			$validate = new Validate([
				'id'		=> 'require',
				'name'		=> 'require',
				'address'	=> 'require'
			]);
			$validate->message([
				'id.require'		=> "请传入供应商ID",
				'name.require' 		=> "请输入供应商名称!",
				'address.require'	=> "请输入供应商地址!"
			]);
			$data = $this->request->param();
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			$result = Db::name('provider')->update($data);
			if($result){
				$this->success("编辑供应商成功!", url('AdminMarketProvider/index'));
			}else{
				$this->error("编辑供应商失败!");
			}
		}
	}
	
	/**
	 * 删除供应商
	 */
	public function delete(){
		$provider_id = $this->request->param('id');
		if(empty($provider_id)){
			$this->error("请传入供应商ID!");
		}
		$count = Db::name('provider_goods')->where('provider_id',"$provider_id")->count();
		if($count == 0){
			$result = Db::name('provider')->where('id',"$provider_id")->delete();
			if($result){
				$this->success("删除供应商成功!");
			}else{
				$this->error("删除供应商失败!");
			}
		}else{
			$this->error("该供应商已有供应商品!");
		}
	}
	
}