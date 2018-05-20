<?php
/**
 * 优惠控制器
 * author: GT
 * time: 2018.05.19
 */
 
namespace api\market\controller;

use cmf\controller\RestUserBaseController;
use think\Db;

class DiscountController extends RestUserBaseController {
	
	/** 
	 * 优惠广场首页数据接口
	 */
	public function index(){
		$userId = $this->getUserId();
		$where = [
			'b.create_time'	=> ['lt', time()],
			'b.expire_time'	=> ['gt', time()]
		];
		$discounts = Db::name('discount')->alias('b')->where($where)->order("id DESC")->paginate(10);
		$where['a.user_id'] = $userId;
		$userDiscounts = Db::name('discount_user')
							->alias('a')
							->field('discount_id')
							->join('__DISCOUNT__ b', 'a.discount_id=b.id')
							->where($where)
							->select();
		$discounts = $discounts->toArray();
		$data = array(); // 由于foreach中的数据只是拷贝
		foreach($discounts['data'] as $discount){
			$item = $discount;
			if(array_key_exists($item['id'], $userDiscounts)){
				$item['possess'] = 1;
			}else{
				$item['possess'] = 0;
			}
			$data[] = $item;
		}
		$result = [
			'total'				=> $discounts['total'],
			'per_page'			=> $discounts['per_page'],
			'current_page'		=> $discounts['current_page'],
			'last_page'			=> $discounts['last_page'],
			'data'				=> $data
		];
		$this->success('请求成功!', $result);
	}
		
	/**
	 * 获取优惠
	 */
	public function obtain(){
		if($this->request->isPost()){
			$userId = $this->getUserId();
			$discountId = $this->request->param('discount_id');
			if(empty($discountId)){
				$this->error("请传入优惠ID!");
			}
			$where = [
				'user_id'		=> $userId,
				'discount_id'	=> $discountId
			];
			$userDiscounts = Db::name('discount_user')->where($where)->find();
			$res = false;
			$discount = Db::name('discount')->where('id', $discountId)->find();
			if(empty($discount)) $this->error("该优惠不存在!");
			if($discount['rest'] - 1 < 0) $this->error("该优惠已经被抢光了:(");
			if(!empty($userDiscounts)){// 已经有该优惠
				Db::startTrans();
				$update = [
					'count' => $userDiscounts['count']+1, 
					'rest'	=> $userDiscounts['rest']+1
				];
				$res = Db::name('discount_user')->where($where)->update($update);
				if(!$res) {
					Db::rollback();
					$this->error("领取优惠失败!(0x01)");
				}
				$res = Db::name('discount')->where('id', $discountId)->setDec('rest');
				if(!$res) {
					Db::rollback();
					$this->error("领取优惠失败!(0x02)");
				}
				Db::commit();
			} else { // 还没有该优惠
				$data = [
					'user_id'		=> $userId,
					'discount_id'	=> $discountId,
					'count'			=> 1,
					'rest'			=> 1,
					'create_time'	=> time()
				];
				Db::startTrans();
				$res = Db::name('discount_user')->insert($data);
				if(!$res) {
					Db::rollback();
					$this->error("领取优惠失败!(0x03)");
				}
				$res = Db::name('discount')->where('id', $discountId)->setDec('rest');
				if(!$res) {
					Db::rollback();
					$this->error("领取优惠失败!(0x02)");
				}
				Db::commit();
			}
			if($res){
				$this->success("领取优惠成功!");
			}else{
				$this->error("领取优惠失败!");
			}
		}
	}
	
	/**
	 * 会员的优惠列表
	 */
	public function mydiscount(){
		if($this->request->isPost()){
			$userId = $this->getUserId();
			$where = [ 'user_id'	=> $userId ];
			$userDiscounts = Db::name('discount_user')
							->alias('a')
							->field('a.*, b.name, b.extent, b.coin, b.create_time as `discount_create_time`, b.expire_time as `discount_expire_time`, b.remark, b.open')
							->join('__DISCOUNT__ b', 'a.discount_id=b.id')
							->where($where)
							->order('id DESC')
							->paginate(10);
							
			$this->success("请求成功!", $userDiscounts);
		}
	}
	
}
