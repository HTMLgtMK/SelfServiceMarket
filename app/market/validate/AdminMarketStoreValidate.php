<?php
/**
 * 店铺验证
 * author: GT
 * time: 2018.04.06 16:06
 */
namespace app\market\validate;

use think\Validate;

class AdminMarketStoreValidate extends Validate {
	
	protected $rule = [
		'id'					=> 'require',
		'name' 					=> 'require',
		'address'				=> 'require',
		'adminstrator_id'		=> 'require'
	];
	protected $message = [
		'id.require'			=> '请传入店铺ID!',
		'name.require'			=> '请输入店铺名称!',
		'address.require'		=> '请输入店铺地址',
		'adminstrator_id.require' => '请选择店铺管理员!'
	];
	protected $scene = [
		'add' 		=> ['name', 'address', 'adminstrator_id'],
		'edit'	 	=> ['id', 'name', 'address', 'adminstrator_id']
	];
}
