<?php

namespace app\admin\validate;

use think\Validate;

class AdminstratorValidate extends Validate
{
    protected $rule = [
        'user_login' => 'require|unique:adminstrator,user_login',
        'name'		 => 'require',
        'user_pass'  => 'require',
        'mobile'	 => 'require|number|unique:adminstrator,mobile'
    ];
    protected $message = [
        'user_login.require' => '用户不能为空',
        'user_login.unique'  => '用户名已存在',
        'user_pass.require'  => '密码不能为空',
        'mobile.require' => '手机号不能为空',
        'mobile.mobile'   => '手机号不正确',
        'mobile.unique'  => '手机号已经存在',
    ];

    protected $scene = [
        'add'  => ['user_login','name', 'user_pass', 'mobile'],
        'edit' => ['user_login','name', 'user_pass', 'mobile'],
    ];
}
