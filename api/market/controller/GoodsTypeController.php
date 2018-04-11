<?php
/**
 * 商品类别接口
 * author:GT
 * time: 2018-04-06 23:32
 */

namespace api\market\controller;

use cmf\controller\RestAdminBaseController;
use think\Db;

class GoodsTypeController extends RestAdminBaseController{
	
	public function index(){
		$where = [];
		$keyword = $this->request->param('keyword');
		if(!empty($keyword)){
			$where['name|address|company'] = ['LIKE', "%$keyword%"];
		}
		$goodsType = Db::name('goods_type')
						->where($where)
						->order('id DESC')
						->select();
		if(!empty($goodsType)){
			$this->success("获取商品分类成功!", ['goods_type'=>$goodsType]);
		}else{
			$this->error("获取商品分类失败!");
		}
	}
}