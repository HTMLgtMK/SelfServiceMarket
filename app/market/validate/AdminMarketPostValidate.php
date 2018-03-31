<?php
/**
 * 无人超市岗位验证
 */

namespace app\market\validate;

use think\Validate;

class AdminMarketPostValidate extends Validate {
	
	protected $rule = [
		"name" 		=> 'require',
		"salary" 	=> 'require',
		"address"	=> 'require',
		"role"		=> 'require|number'
	];
	
	protected $message = [
		"name.require" 		=> "岗位名称不能为空!",
		"salary.require"	=> "薪资不能为空",
		"address.require"	=> "地址不能为空",
		"role.require"		=> "角色不能空"
	];
	
	protected $scene = [
		"add" 	=> ['name','salary','address','role'],
		"edit" 	=> ['name','salary','address','role']
	];
	
}

