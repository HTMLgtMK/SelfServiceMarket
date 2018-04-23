<?php
/**
 * 无人超市系统 商家优惠管理
 * author: GT
 * time: 2018/04/18 20:35
 */

namespace market\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\Validate;

class AdminMarketSaleDiscountController extends AdminBaseController {
	
	/**
	 * 商家优惠列表
	 */
	public function index(){
		$where = [];
		$data = $this->request->param();
		$name = $data['name'];
		if(!empty($data['name'])){
			$where['name'] = ['LIKE', "%$name%"];
		}
		$extent_min = $data['extent_min'];
		$extent_max = $data['extent_max'];
		if(!empty($data['extent_min']) && !empty($data['extent_max'])){
			$where['extent'] = ['BETWEEN', ["$extent_min", "$extent_max"]];
		}
		$coin_min = $data['coin_min'];
		$coin_max = $data['coin_max'];
		if(!empty($data['coin_min']) && !empty($data['coin_max'])){
			$where['coin'] = ['BETWEEN', ["$coin_min", "$coin_max"]];
		}
		$count = $data['count'];
		if(!empty($data['count'])){
			$where['count'] = "$count";
		}
		$rest = $data['rest'];
		if(!empty($data['rest'])){
			$where['rest'] = "$rest";
		}
		$create_time = $data['create_time'];
		if(!empty($data['create_time'])){
			$where['create_time'] = ['>=', "$create_time"];
		}
		$expire_time = $data['expire_time'];
		if(!empty($data['expire_time'])){
			$where['expire_time'] = ['<=', "$expire_time"];
		}
		
		$discounts = Db::name('sale')->where($where)->paginate(10);
		$arr = ['name'=>"$name", "extent_min"=>"$extent_min", "extent_max"=>"$extent_max",
					"coin_min"=>"$coin_min", "coin_max"=>"coin_max", "count"=>"$count", "rest"=>"$reset",
					"create_time"=>"$create_time", "expire_time"=>"$expire_time"];
		$discounts->appends($arr);
		
		//获取分页显示
		$pages = $discounts->render();
		
		$this->assign('page',$pages);
		$this->assign('discouns', $discounts);
		
		return $this->fetch();
	}
	
	
	
	
}


