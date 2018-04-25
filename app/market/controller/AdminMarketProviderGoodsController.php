<?php
/**
 * 供应商品管理
 * author: GT
 * time: 2018/04/24 16:00
 */
 
namespace app\market\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\Validate;

class AdminMarketProviderGoodsController extends AdminBaseController {
	
	/**
	 * 供应商品列表
	 */
	public function index(){
		$where = array();
		$data = $this->request->param();
		if(!empty($data['provider_id']) && $data['provider_id']!=0){
			$where['provider_id'] = $data['provider_id'];
		}
		if(!empty($data['type_id']) && $data['type_id']!=0 ){
			$where['type_id'] = $data['type_id'];
		}
		if(isset($data['status']) && $data['status']!=-1 ){
			$where['status'] = $data['status'];
		}
		$provider_goods = Db::name('provider_goods')
							->alias('a')
							->field("a.*, b.name as provider_name, c.name as type_name")
							->join("__PROVIDER__ b", 'a.provider_id = b.id')
							->join("__GOODS_TYPE__ c", 'a.type_id = c.id')
							->where($where)->paginate(10);
		$provider_goods->appends($where);
		$pages = $provider_goods->render();
		
		$providers = Db::name('provider')->field('id,name')->select();
		$types = Db::name('goods_type')->field('id,name')->select();
		
		$this->assign("provider_goods", $provider_goods);
		$this->assign("page", $pages);
		$this->assign("providers", $providers);
		$this->assign("types", $types);
		return $this->fetch();
	}
	
	/**
	 * 添加供应商品
	 */
	public function add(){
		$providers = Db::name('provider')->field('id,name')->select();
		$types = Db::name('goods_type')->field('id,name,price')->select();
		
		$this->assign("providers", $providers);
		$this->assign("types", $types);
		
		return $this->fetch();
	}
	
	/**
	 * 添加供应商品提交
	 */
	public function addPost(){
		if($this->request->isPost()){
			$validate = new Validate([
				'provider_id' 	=> "require",
				'type_id'		=> "require",
				'price'			=> "require|number",
				'count'			=> "require|number"
			]);
			$validate->message([
				'provider_id.require'	=> '请选择供应商!',
				'type_id.require'		=> '请选择商品类别!',
				'price.require'			=> '请输入商品价格!',
				'count.require'			=> '请输入供应总量!'
			]);
			$data = $this->request->param();
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			$data['handover_num'] = 0;
			$data['create_time'] = time();
			$data['status'] = 2;//正在供应
			
			$result = Db::name('provider_goods')->insert($data);
			if($result){
				$this->success("添加供应成功!", url('AdminMarketProviderGoods/index'));
			}else{
				$this->error("添加供应失败!");
			}
		}
	}
	
	/**
	 * 递交供应商品
	 */
	public function handover(){
		if($this->request->isPost()){
			$data = $this->request->param();
			$id = $data['id'];
			if(empty($id)){
				$this->error('请传入供应商品ID!');
			}
			$handover_num = $data['handover_num'];
			if(empty($handover_num)){
				$this->error("请输入本次供应量!");
			}
			$status = Db::name('provider_goods')->where('id',"$id")->limit(1)->column("status");
			$status = $status['0'];
			if($status == 1){
				$this->error("该供应商品已完成全部供应!");
			}else if($status == 0){
				$this->error("该供应商品处于中断供应状态!");
			}
			
			$result = Db::name('provider_goods')->where('id',"$id")->setInc('handover_num',$handover_num);
			if($result){
				$vo = Db::name('provider_goods')->where('id',"$id")->find();
				if($vo['count'] == $vo['handover_num']){//递交量和总量相同
					Db::name('provider_goods')->where('id',"$id")->update(['status'=>1]);
					$this->success("添加递交成功!且已完成全部递交!");
				}else{
					$this->success("添加递交成功!");
				}
			}else{
				$this->error("添加递交失败!");
			}
		}else{
			$id = $this->request->param('id');
			$provider_goods = Db::name('provider_goods')
									->alias('a')
									->field('a.*, b.name as provider_name, c.name as type_name')
									->join("__PROVIDER__ b", 'a.provider_id = b.id')
									->join("__GOODS_TYPE__ c", 'a.type_id = c.id')
									->where('a.id', "$id")
									->find();
			$this->assign("provider_goods", $provider_goods);
			return $this->fetch();
		}
	}
	
	/**
	 * 中断某个供应商品
	 */
	public function suspend(){
		$id = $this->request->param('id');
		$result = Db::name('provider_goods')->where('id',"$id")->update(['status'=>0]);
		if($result){
			$this->success("中断供应商品成功!");
		}else{
			$this->error("中断供应商品失败!");
		}
	}
	
	/**
	 * 取消中断某个商品供应
	 */
	public function cancelsuspend(){
		$id = $this->request->param('id');
		$result = Db::name('provider_goods')->where('id',"$id")->update(['status'=>2]);
		if($result){
			$this->success("取消中断供应商品成功!");
		}else{
			$this->error("取消中断供应商品失败!");
		}
	}
	
	/**
	 * 删除某个商品供应
	 */
	public function delete(){
		$id = $this->request->param('id');
		$handover_num = Db::name('provider_goods')->where('id',"$id")->limit(1)->column("handover_num");
		$handover_num = $handover_num['0'];
		if($handover_num>0){
			$this->error("已有商品供应，不可删除!");
		}else{
			$result = Db::name('provider_goods')->where('id',"$id")->delete();
			if($result){
				$this->success("删除商品供应成功!");
			}else{
				$this->error("删除商品供应失败!");
			}
		}
	}
}