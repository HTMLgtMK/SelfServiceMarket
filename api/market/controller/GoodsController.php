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
	 * 根据商品id获取商品信息
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
			$res[] = $goods;
		}
		$discount = $this->getDiscounts();
		$this->success("获取商品信息成功!",["goods"=>$res, "discount"=>$discount]);
	}
	
	/*获取商品优惠信息*/
	private function getDiscounts(){
		$where = array(
			'b.rest' 			=> ['gt', 0],
			'b.create_time'		=> ['lt', time()],
			'b.expire_time'		=> ['gt', time()]
		);
		$result = Db::name('discount_goods')
						->alias('a')
						->field('a.id, a.discount_id, a.goods_type_id, b.name, b.extent, b.coin, b.rest')
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