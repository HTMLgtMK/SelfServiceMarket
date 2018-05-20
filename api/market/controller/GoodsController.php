<?php
/**
 * 商品管理接口
 * author: GT
 * time: 2018-04-07
 */
namespace api\market\controller;

use cmf\controller\RestAdminBaseController;
use think\Db;
use think\Validate;

class GoodsController extends RestAdminBaseController {
	
	/**
	 * 商品列表
	 */
	public function index(){
		$where = [];
		/*搜索条件*/
		$name = $this->request->param('name');
		$keywords = $this->request->param('keywords');
		$date_min = $this->request->param("date_min");
		$date_max = $this->request->param('date_max');
		$batch_number = $this->request->param('batch_number');
		$status = $this->request->param('status',1);
		
		if(!empty($name)){
			$where['b.name'] = ['LIKE', "%$name%"];
		}
		
		if(!empty($keywords)){
			$where['b.address|b.company'] = ['LIKE', "%$keywords%"];			
		}
		
		if(!empty($date_min) && !empty($date_max)){
			$where['a.manufacture_date'] = ['between', ["$date_min","$date_max"]];
		}
		
		if(!empty($batch_number)){
			$where['a.batch_number'] = ['=', "$batch_number"];
		}
		
		if(!empty($status)){
			$where['a.status'] = ['=', "$status"];
		}
		
		$goods = Db::name('goods')
					->alias('a')
					->field("a.*, b.name, b.images, b.price")
					->join('__GOODS_TYPE__ b', 'a.type_id = b.id')
					->where($where)
					->order('manufacture_date desc')
					->paginate(10);
					
		$arr = [
			'name' 			=> "$name",
			'keywords' 		=> "$keywords",
			'date_min'		=> "$date_min",
			'date_max'		=> "$date_max",
			'batch_number'		=> "$batch_number",
			'status'		=> "$status",
		];
		if(!empty($goods)){
			$this->success("请求成功!", ['goods'=>$goods]);
		}else{
			$this->error("请求失败!");
		}
	}
	
	/**
	 * 商品下架
	 * @param id 商品id
	 */
	public function delete(){
		$goods_id = $this->request->param('id');
		if(!empty($goods_id)){
			$status = Db::name("goods")->where('id',"$goods_id")->limit(1)->column('status');
			if(!empty($status)){
				$status = $status['0'];
				if($status == 1){
					$result = Db::name('goods')->where('id',"$goods_id")->delete();
					if($result){
						$this->success("下架成功!");
					}else{
						$this->error("下架失败!");
					}
				}else{//已售
					$this->error("该商品已售 或 被锁定，不可下架!");
				}
			}else{
				$this->error("商品不存在!");
			}
		}else{
			$this->error("请传入商品ID!");
		}
	}
	
	/**
	 * 编辑商品
	 */
	public function edit(){
		$goods_id = $this->request->param('id');
		$goods = Db::name('goods')->where('id',"$goods_id")->find();
		if($goods['status'] != 1){
			return $this->error("商品已售或被锁定，不可修改!");
		}
		$goods_type = Db::name('goods_type')->select();
		$this->success("请求成功!", ['goods_type'=>$goods_type, "goods"=>$goods]);
	}
	
	/**
	 * 编辑商品提交
	 */
	public function editPost(){
		if($this->request->isPost()){
			$validate = new Validate([
				'id' 			=> "require",
				'type_id'		=> "require",
				"manufacture_date"	=> "require",
				"batch_number"	=> "require",
			]);
			$validate->message([
				"id.require"			=> "请指定商品ID",
				"type_id.require"		=> "请指定商品类别",
				"manufacture_date.require"	=> "请输入生产日期",
				"batch_number.require"	=> "请输入生产批号"		
			]);
			$data = $this->request->param();
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//$data['manufacture_date'] = strtotime($data['manufacture_date']);
			if(array_key_exists("status",$data)){
				$data['status'] = 1;
			}
			
			$result = Db::name('goods')->update($data);
			if($result){
				$this->success("修改成功!");
			}else{
				$this->error("修改失败!");
			}
		}
	}
	
	
	
	/**
	 * 根据商品id获取批量商品信息
	 */
	public function getGoodsInfo(){
		$data = $this->request->param();
		//利用json格式传输EPC号
		if(empty($data)){
			$this->error("商品id不能为空!");
		}
		$size = count($data);
		$res = [];
		for($i=0;$i<$size;++$i){
			$id = $data["$i"];
			$goods = Db::name("goods")
						->alias('a')
						->field("a.*, b.name, b.images, b.price, b.address, b.company")
						->join("__GOODS_TYPE__ b", "a.type_id = b.id")
						->where("a.id","$id")
						->find();
			if(!empty($goods)){ // 提交的商品ID不存在
				$res[] = $goods;
			}
		}
		$discount = $this->_getDiscounts();
		$this->success("获取商品信息成功!",["goods"=>$res, "discount"=>$discount]);
	}
	
	/**
	 * 根据商品id获取批量商品信息, 只返回商品信息
	 */
	public function getGoodsInfo2(){
		$data = $this->request->param();
		//利用json格式传输EPC号
		if(empty($data)){
			$this->error("商品id不能为空!");
		}
		$size = count($data);
		$res = [];
		for($i=0;$i<$size;++$i){
			$id = $data["$i"];
			$goods = Db::name("goods")
						->alias('a')
						->field("a.*, b.name, b.images, b.price, b.address, b.company")
						->join("__GOODS_TYPE__ b", "a.type_id = b.id")
						->where("a.id","$id")
						->find();
			if(!empty($goods)){
				$res[] = $goods;
			} else { // 提交的商品ID存在, 创建一个空的Goods, type_id = 0, status = 4
				$goods = ["id"=>$id, 'type_id'=>'0', 'manufacture_date'=>0, 'batch_number'=>'','status'=>4, 
					'name'=>'', 'images'=>'', 'price'=>0, 'address'=>'', 'company'=>''];
				$res[] = $goods;
			}
		}
		$this->success("获取商品信息成功!", ['goods'=>$res]);
	}
	
	/*获取优惠信息*/
	public function getDiscounts(){
		$discount = $this->_getDiscounts();
		$this->success("获取商品优惠信息成功!", ['discount'=>$discount]);
	}
	
	/*获取商品优惠信息*/
	private function _getDiscounts(){
		$where = array(
			// 'b.rest' 			=> ['gt', 0], // 只需要会员有该优惠就行
			'b.create_time'		=> ['lt', time()],
			'b.expire_time'		=> ['gt', time()]
		);
		$result = Db::name('discount_goods')
						->alias('a')
						->field('a.id, a.discount_id, a.goods_type_id, b.name, b.extent, b.coin, b.rest, b.open')
						->join('__DISCOUNT__ b', "a.discount_id = b.id")
						->where($where)
						->select();
		return $result;
	}
	
	/**
	 * 提交商品id
	 */
	public function submit(){
		$validate = new Validate([
			'id'			 => "require",
			'batch_number' 	 => "require",
			'type_id'		 => "require",
			'manufacture_date' 	=> "require"
		]);
		$validate->message([
			'id.require'	 			=> "请先获取商品ID!",
			'batch_number.require' 		=> "请输入生产批号!",
			'type_id.require'			=> "请先选择商品类别!",
			'manufacture_date.require'	=> "请选择商品生产日期!"
		]);
		$data = $this->request->param();
		if(!$validate->check($data)){
			$this->error($validate->getError());
		}
		$result = Db::name("goods")->insert($data);
		if(!empty($result)){
			$this->success("添加商品成功!");
		}else{
			$this->error("添加商品失败!");
		}
	}
	
	/**
	 * 获取商品ID
	 */
	public function getGoodsId(){
		$goodsType = $this->request->param('goodsType');
		if(empty($goodsType)){
			$this->error("请选择商品类别!");
		}else{
			$goods_id = Db::name('goods')
						->where('type_id',"$goodsType")
						->limit(1)
						->order("id DESC")
						->column('id');
			$id = "20128101";//v:2 NSI:0128 DC:1 AC:01
			$id .= $this->goodsType2Str($goodsType);
			if(empty($goods_id)){//还没有该类型商品
				$id .= $this->generateGoodsId('00000000000000000000');
			}else{
				$goods_id = $goods_id['0'];
				$id_last =substr($goods_id,-20);//从结尾开始截取20位
				$id .= $this->generateGoodsId2($id_last);
			}
			$this->success("获取成功!",['goods_id'=>$id]);
		}
	}
	
	/**
	 * 将商品类别id转化成4位的字符串
	 */
	private function goodsType2Str($goodsType){
		$str = strval($goodsType);
		$str = sprintf("%04s", $str);//字符串自动补全
		return $str;
	}
	
	/**
	 * 产生商品ID
	 */
	private function generateGoodsId($now){
		$now_id = intval($now);
		$id = $now_id+1;
		$str = strval($id);
		$str = sprintf("%020s",$str);
		return $str;
	} 
	
	private function generateGoodsId2(string $now){
		$b = "00000000000000000001";
		$len = 20;//二级商品id共20位
		$c = 0;// 进位
		$result  = '';// 结果
		while($len--){
			$t1 = $now[$len];
			$t2 = $b[$len];
			$t = $t1 +$t2 + $c;
			$c = intval($t)/10;
			$result = ($t%10) . $result;
		}
		return $result;
	}
}