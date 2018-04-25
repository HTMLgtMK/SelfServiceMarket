<?php
/**
 * 无人超市优惠管理验证
 * author: GT
 * time: 2018/04/25 11:00
 */
 
namespace app\market\validate;

use think\Validate;

class AdminMarketSaleDiscountValidate extends Validate {

	protected $rule = [
		'id'			=> 'require',
		'name'			=> 'require|unique:discount,name',
		'extent'		=> 'require',
		'coin'			=> 'require|number',
		'create_time'	=> 'require|date',
		'expire_time'	=> 'require|date'
	];
	
	protected $message = [
		'id.require'		=> '请传入优惠ID!',
		'name.require'		=> '请输入优惠名称!',
		'name.unique'		=> '已存在该优惠!',
		'extent.require'	=> '请输入打折比例!',
		'coin.require'		=> '请输入优惠金额!',
		'create_time.require' => '请输入开始时间!',
		'expire_time.require' => '请输入过期时间!'
	];
	
	protected $scene = [
		'add' => ['name', 'extent', 'coin', 'create_time', 'expire_time'],
		'edit' => ['id','extent', 'coin' , 'create_time', 'expire_time']
	];
	
}
