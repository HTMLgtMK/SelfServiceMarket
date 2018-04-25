<?php
/** 
 * 优惠商品管理
 * author: GT
 * time: 2018/04/25 16:37
 */
 
namespace app\market\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class AdminMarketSaleDiscountGoodsController extends AdminBaseController {
	
	/**
	 * 为某一优惠添加商品类别
	 */
	public function add(){
		$discount_id = $this->request->param('id');
		if(empty($discount_id)){
			$this->error("请传入优惠ID!");
		}
		$discount = Db::name('discount')->where('id',"$discount_id")->find();
		if(empty($discount)){
			$this->error("优惠不存在!");
		}
		$discount_goods = Db::name('discount_goods')
							->field('id, goods_type_id')
							->where('discount_id', "$discount_id")
							->select();
		$goods_type = Db::name('goods_type')->field("id, name, price")->select();
		$this->assign("discount", $discount);
		$this->assign("discount_goods",$discount_goods);
		$this->assign("goods_type",$goods_type);
		
		return $this->fetch();
	}
	
	/**
	 * 添加提交
	 */
	public function addPost(){
		if($this->request->isPost()){
			$data = $this->request->param();
			$discount_id = $data['discount_id'];
			if(array_key_exists("goods_type",$data)){
				$goods_type = $data['goods_type'];
			}else{
				$goods_type = [];
			}
			// TODO 事务操作
			//先删除原来该优惠下的所有商品
			Db::name("discount_goods")->where('discount_id', "$discount_id")->delete();
			//再添加选中的商品
			foreach($goods_type as $type_id){
				$arr = ['discount_id'=>"$discount_id", "goods_type_id"=>"$type_id"];
				$result = Db::name("discount_goods")->insert($arr);
				if(!$result){
					$this->error("添加失败!");
				}
			}
			$this->success("添加成功!", url('AdminMarketSaleDiscount/index'));
		}
	}
	
}