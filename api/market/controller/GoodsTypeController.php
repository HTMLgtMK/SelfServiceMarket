<?php
/**
 * 商品类别接口
 * author:GT
 * time: 2018-04-06 23:32
 */

namespace api\market\controller;

use cmf\controller\RestAdminBaseController;
use think\Db;
use think\Validate;

class GoodsTypeController extends RestAdminBaseController{
	
	public function index(){
		$where = [];
		$keyword = $this->request->param('keyword');
		$page = $this->request->param('page', 1, 'intval');
		$PAGE_COUNT = 10;
		if(!empty($keyword)){
			$where['name|address|company'] = ['LIKE', "%$keyword%"];
		}
		$goodsType = Db::name('goods_type')
						->where($where)
						->order('id DESC')
						->paginate(10);
		if(!empty($goodsType)){
			$this->success("获取商品分类成功!", ['goods_type'=>$goodsType]);
		}else{
			$this->error("获取商品分类失败!");
		}
	}
	
	/**
	 * 添加商品类
	 */
	 public function add(){
		 $this->success("请求成功!");
	 }
	 
	 /**
	  * 添加商品类提交
	  */
	 public function addPost(){
	 	if($this->request->isPost()){
	 		$data = $this->request->param();
	 		//验证提交的数据
	 		$validate = new Validate([
	 			'name' 		=> "require",
	 			'price' 	=> "require",
	 			'address' 	=> "require",
	 			'company' 	=> "require"
	 		]);
	 		$validate->message([
	 			'name.require' 	=> "请输入商品名!",
	 			'price.require' => "请输入价格!",
	 			'address.require' 	=> '请输入生产地址!',
	 			'company.require'	=> '请输入生产公司!'
	 		]);
	 		if(!$validate->check($data)){
	 			$this->error($validate->getError());
	 		}
			$data['images'] = '';
	 		$result = Db::name('goods_type')->insert($data);
	 		if($result){
	 			$this->success('添加商品类成功:'.$data['name']);
	 		}else{
	 			$this->error("添加商品类失败:".$data['name']);
	 		}
	 	}//isPost()
	 }
	 
	 /**
	  * 编辑商品类别
	  */
	 public function edit(){
		 $type_id = $this->request->param("id");
		 $type = Db::name('goods_type')->where("id","$type_id")->find();
		 $this->success("请求成功!", ['id'=>$type_id, 'type'=>$type]);
	 }
	 
	 /**
	  * 编辑商品类别提交
	  */
	 public function editPost(){
		 if($this->request->isPost()){
			 $data = $this->request->param();
	 		//验证提交的数据
	 		$validate = new Validate([
				'id'		=> "require",
	 			'name' 		=> "require",
	 			'price' 	=> "require",
	 			'address' 	=> "require",
	 			'company' 	=> "require"
	 		]);
	 		
	 		$validate->message([
				'id.require'	=> "请传入商品类别ID",
	 			'name.require' 	=> "请输入商品名!",
	 			'price.require' => "请输入价格!",
	 			'address.require' 	=> '请输入生产地址!',
	 			'company.require'	=> '请输入生产公司!'
	 		]);
	 		
	 		if(!$validate->check($data)){
	 			$this->error($validate->getError());
	 		}
			
			$result = Db::name('goods_type')->update($data);
			if($result){
				$this->success("修改商品类别成功!");
			}else{
				$this->error("修改商品类别失败!");
			}
		 }
	 }
	 
	 /**
	  * 删除商品类别
	  */
	 public function delete(){
		 $type_id = $this->request->param('id');
		 if(empty($type_id)){
			 $this->error("请传入商品类别ID!");
		 }
		 $count = Db::name('goods')->where('type_id',"$type_id")->count();
		 if($count == 0){
			 $result = Db::name('goods_type')->where("id","$type_id")->delete();
			 if($result){
				 $this->success("删除商品类别成功!");
			 }else{
				 $this->error("删除商品类别失败!");
			 }
		 }else{
			 $this->error("该类别下含有商品，不可删除!");
		 }
	 }
}