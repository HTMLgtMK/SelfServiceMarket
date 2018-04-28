<?php
/**
 * 销售管理
 * author :GT
 * time: 2018.04.28 09:18
 */
 
namespace app\market\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\Config;

class AdminMarketSaleController extends AdminBaseController {
	
	/**
	 * 销售管理首页
	 */
	public function index(){
		//实时交易信息
		$today0 = strtotime(date("Y-m-d", time()));
		$today1 = strtotime(date("Y-m-d", strtotime("+1 day")));
		$deals = Db::name('sale')
					->alias('a')
					->field('a.*, b.name as store_name, c.user_login as user_login')
					->join('__STORE__ b', 'a.store_id = b.id', "LEFT")
					->join('__USER__ c', 'a.user_Id = c.id', "LEFT")
					->order('a.id desc')
					->limit(10)
					->select();
		$this->assign("deals", $deals);
		
		//今日交易额
		$tb_store = Config::get('prefix').'store';//tb_store的数据库名
		$view_store_sale = "view_store_sale"; // view_store_sale 的视图名
		$sql = "SELECT `a`.`id`, `a`.`name`, IFNULL(`b`.`store_id`, `a`.`id`) as `store_id`, IFNULL(`b`.`sale_total_amount`, 0) as `sale_total_amount`
					FROM `tb_store` as `a` 
					LEFT JOIN `view_store_sale` as `b` ON `a`.`id`=`b`.`store_id`
					WHERE `a`.`status`='1' OR `a`.`status`='2' ; ";
		$store_deals = Db::query($sql);
		$sale_today_total_amount = 0;
		if(!empty($store_deals)){
			foreach($store_deals as $deal){
				$sale_today_total_amount += $deal['sale_total_amount'];
			}
		}
		$this->assign("store_deals", $store_deals);
		$this->assign("sale_today_total_amount", $sale_today_total_amount);
		
		return $this->fetch();
	}
	
	/**
	 * 销售数据统计分析
	 */
	public function analysis(){
		
	}
	
	/**
	 * 店铺销售数据详情
	 */
	public function storeSaleDetail(){
		
	}
	
}