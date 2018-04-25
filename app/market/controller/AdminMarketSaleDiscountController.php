<?php
/**
 * 无人超市系统 商家优惠管理
 * author: GT
 * time: 2018/04/18 20:35
 */

namespace app\market\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class AdminMarketSaleDiscountController extends AdminBaseController {
	
	/**
	 * 商家优惠列表
	 */
	public function index(){
		$where = [];
		$data = $this->request->param();
		if(!empty($data['name'])){
			$name = $data['name'];
			$where['name'] = ['LIKE', "%$name%"];
		}
		if(!empty($data['extent_min']) && !empty($data['extent_max'])){
			$extent_min = $data['extent_min'];
			$extent_max = $data['extent_max'];
			$where['extent'] = ['BETWEEN', ["$extent_min", "$extent_max"]];
		}
		if(!empty($data['coin_min']) && !empty($data['coin_max'])){
			$coin_min = $data['coin_min'];
			$coin_max = $data['coin_max'];
			$where['coin'] = ['BETWEEN', ["$coin_min", "$coin_max"]];
		}
		if(!empty($data['count'])){
			$count = $data['count'];
			$where['count'] = "$count";
		}
		if(!empty($data['rest'])){
			$rest = $data['rest'];
			$where['rest'] = "$rest";
		}
		if(!empty($data['create_time'])){
			$create_time = $data['create_time'];
			$where['create_time'] = ['>=', "$create_time"];
		}
		if(!empty($data['expire_time'])){
			$expire_time = $data['expire_time'];
			$where['expire_time'] = ['<=', "$expire_time"];
		}
		
		$discounts = Db::name('discount')->where($where)->paginate(10);
		$discounts->appends($data);
		
		//获取分页显示
		$pages = $discounts->render();
		
		$this->assign('page',$pages);
		$this->assign('discounts', $discounts);
		
		return $this->fetch();
	}
	
	/**
	 * 添加折扣
	 */
	public function add(){
		return $this->fetch();
	}
	
	/**
	 * 添加折扣提交
	 */
	public function addPost(){
		if($this->request->isPost()){
			$data = $this->request->param();
			$result = $this->validate($data,"AdminMarketSaleDiscount.add");
			if($result !== true){
				$this->error($result);
			}
			if($data['coin'] > 0){
				$this->error("优惠金额为负!");
			}
			$data['create_time'] = strtotime($data['create_time']);
			$data['expire_time'] = strtotime($data['expire_time']);
			$data['rest'] = $data['count'];
			$result = Db::name('discount')->insert($data);
			if($result){
				$this->success("添加优惠成功!", url("AdminMarketSaleDiscount/index"));
			}else{
				$this->error("添加优惠失败!");
			}
		}
	}
	
	/**
	 * 编辑优惠
	 */
	public function edit(){
		$id = $this->request->param('id');
		if(empty($id)){
			return $this->error("请传入优惠ID!");
		}
		$discount = Db::name("discount")->where("id","$id")->find();
		if(empty($discount)){
			return $this->error("该优惠不存在!");
		}
		if($discount['rest'] !=  $discount['count']){
			return $this->error("该优惠已经售出，不可再修改!");
		}
		$this->assign("discount", $discount);
		return $this->fetch();
	}
	
	
	/**
	 * 编辑优惠提交
	 */
	public function editPost(){
		if($this->request->isPost()){
			$data = $this->request->param();
			$result = $this->validate($data, "AdminMarketSaleDiscount.edit");
			if($result !== true){
				$this->error($result);
			}
			if($data['coin'] > 0){
				$this->error("优惠金额为负!");
			}
			// name 不可被修改
			if(array_key_exists("name", $data)){
				unset($data['name']);
			}
			$data['create_time'] = strtotime($data['create_time']);
			$data['expire_time'] = strtotime($data['expire_time']);
			$data['rest'] = $data['count'];//剩余数和总数相同
			$result = Db::name('discount')->update($data);
			if($result){
				$this->success("修改优惠成功!", url('AdminMarketSaleDiscount/index'));
			}else{
				$this->error("修改优惠失败!");
			}
		}
	}
	
	/**
	 * 删除优惠
	 */
	public function delete(){
		$id = $this->request->param("id");
		if(empty($id)){
			$this->error("请传入优惠ID!");
		}
		$discount = Db::name('discount')->where('id', "$id")->find();
		if(empty($discount)){
			$this->error("该优惠不存在!");
		}
		if($discount['rest'] !=  $discount['count']){
			$this->error("该优惠已经售出，不可再删除!");
		}
		$count = Db::name('discount_goods')->where('discount_id',"$id")->count();
		if($count > 0){
			$this->error("该优惠下含有商品, 不可删除!");
		}
		$result = Db::name("discount")->where("id","$id")->delete();
		if($result){
			$this->success("删除优惠成功!");
		}else{
			$this->success("删除优惠失败!");
		}
	}
}