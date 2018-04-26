<?php
/**
 * 无人超市店铺管理
 * author: GT
 * time: 2018.04.26 15:27
 */

namespace app\market\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class AdminMarketStoreController extends AdminBaseController {
	
	/**
	 * 店铺列表
	 */
	public function index(){
		$where = array();
		$keyword = $this->request->param("keyword");
		$status = $this->request->param('status');
		if(!empty($keyword)){
			$where['a.name | a.address'] = ['LIKE', "%$keyword%"];
		}
		if(isset($status) && $status != -1){
			$where['status'] = $status;
		}
		$stores = Db::name('store')
					->alias('a')
					->field("a.*,b.id as adminstrator_id, b.name as adminstrator_name, b.user_login, b.mobile")
					->join("__ADMINSTRATOR__ b", "a.adminstrator_id = b.id")
					->where($where)
					->paginate(10);
		$stores->appends(['keyword'=>"$keyword",'status'=>$status]);
		$pages = $stores->render();
		
		$this->assign('stores', $stores);
		$this->assign('page', $pages);
		
		return $this->fetch();
	}
	
	/**
	 * 添加店铺
	 */
	public function add(){
		$adminstrators = Db::name('adminstrator')->field('id,name,mobile')->select();
		$this->assign("adminstrators", $adminstrators);
		return $this->fetch();
	}
	
	/**
	 * 添加店铺提交
	 */
	public function addPost(){
		if($this->request->isPost()){
			$data = $this->request->param();
			$result = $this->validate($data, "AdminMarketStore.add");
			if($result !== true){
				$this->error($result);
			}
			$data['create_time'] = time();
			$result = Db::name('store')->insert($data);
			if($result){
				$this->success("添加店铺成功!", url('AdminMarketStore/index'));
			}else{
				$this->error("添加店铺失败!");
			}
		}
	}
	
	/**
	 * 编辑店铺
	 */
	public function edit(){
		$store_id = $this->request->param('id');
		if(empty($store_id)){
			return $this->error("请传入店铺ID!");
		}
		$store = Db::name('store')
					->alias('a')
					->field("a.*,b.id as adminstrator_id, b.name as adminstrator_name, b.user_login, b.mobile")
					->join("__ADMINSTRATOR__ b", "a.adminstrator_id = b.id")
					->where("a.id", "$store_id")
					->find();
		if(empty($store)){
			return $this->error("店铺不存在!");
		}
		$adminstrators = Db::name('adminstrator')->field('id,name,mobile')->select();
		$this->assign("adminstrators", $adminstrators);
		$this->assign("store", $store);
		
		return $this->fetch();
	}
	
	/**
	 * 编辑店铺提交
	 */
	public function editPost(){
		if($this->request->isPost()){
			$data = $this->request->param();
			$result = $this->validate($data, "AdminMarketStore.edit");
			if($result !== true){
				$this->error($result);
			}
			$result = Db::name('store')->update($data);
			if($result){
				$this->success("修改店铺成功!", url('AdminMarketStore/index'));
			}else{
				$this->error("修改店铺失败!");
			}
		}
	}
	
	/**
	 * 永久关闭店铺
	 */
	public function permanentClose(){
		$store_id = $this->request->param('id');
		if(empty($store_id)){
			$this->error('请传入店铺ID!');
		}
		{
			// TODO 验证店主的手机验证码
		}
		$result = Db::name('store')->where('id',"$store_id")->update(['status'=>0]);
		if($result){
			$this->success("永久关闭店铺成功!");
		}else{
			$this->error("永久关闭店铺失败!");
		}
	}
	
	/**
	 * 打烊
	 */
	public function close(){
		$store_id = $this->request->param('id');
		if(empty($store_id)){
			$this->error('请传入店铺ID!');
		}
		{
			// TODO 验证店铺管理员的手机验证码
		}
		$result = Db::name('store')->where('id',"$store_id")->update(['status'=>2]);
		if($result){
			$this->success("店铺打烊成功!");
		}else{
			$this->error("店铺打烊失败!");
		}
	}
	
	/**
	 * 营业
	 */
	public function open(){
		$store_id = $this->request->param('id');
		if(empty($store_id)){
			$this->error('请传入店铺ID!');
		}
		{
			// TODO 验证店铺管理员的手机验证码
		}
		$result = Db::name('store')->where('id',"$store_id")->update(['status'=>1]);
		if($result){
			$this->success("店铺营业成功!");
		}else{
			$this->error("店铺营业失败!");
		}
	}
	
}