<?php
/**
 * 自助终端管理数据验证
 * author: GT
 * time: 2018.04.27 09:49
 */
namespace app\market\validate;

use think\Validate;

class AdminMarketTerminalValidate extends Validate {
		
	protected $rule = [
		'id' 			=> 'require',
		'ip'			=> 'require | unique:store_terminal, ip'
	];
	protected $message = [
		'id.require' 	=> '请传入终端ID!',
		'ip.require'	=> '请输入终端IP!',
		'ip.unique'		=> '请确认终端IP唯一!'
	];
	protected $scene = [
		'add'			=> ['name'],
		'edit'			=> ['id', 'name.require']
	];
}