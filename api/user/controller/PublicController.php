<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace api\user\controller;

use think\Db;
use think\Validate;
use cmf\controller\RestBaseController;

class PublicController extends RestBaseController{
	
	
	//发送验证码
	public function send_verify_code(){
		
		$validate = new Validate([
			'username' 		=> 'require'
		]);
		
		$validate->message([
			'username.require' => '请输入手机号或邮箱!'
		]);
		
		$data = $this->request->param();
		if(!$validate->check($data)){
			$this->error($validate->getError());
		}
		
		/**
		 * 账户类型
		 * 1. 邮箱
		 * 2. 手机号
		 */
		$useType = 0;

        $findUserWhere = [];
        
        if (Validate::is($data['username'], 'email')) {
            $useType 					 = 1;
            $findUserWhere['user_email'] = $data['username'];
        } else if(preg_match('/(^(13\d|15[^4\D]|17[013678]|18\d)\d{8})$/', $data['username'])) {
            $useType 				 = 2;
            $findUserWhere['mobile'] = $data['username'];
        } else {
            $this->error("请输入正确的手机或者邮箱格式!");
        }
        
        $findUserCount = Db::name("user")->where($findUserWhere)->count();

        if ($findUserCount > 0) {
            $this->error("此账号已存在!");
        }
        
        //获取验证码
        $verification_code = cmf_get_verification_code($data['username'],6);
        //写入数据库
        $result = cmf_verification_code_log($data['username'], $verification_code);
        //根据类型发送到设备
        switch($useType){
        case 1:{//发送到邮箱
        	$message = "亲爱的用户：\r\n \t您好！感谢您使用无人超市系统! \r\n \t";
        	$message .= "您的验证码为: $verification_code , 请在30min内完成验证。";
        	$result = cmf_send_email($data['username'],
        				'[Market] 无人超市注册验证' , $message);
        	if($result['error']){
        		$this->error("发送失败!" . $result['message']);
        	} else {
        		$this->success("发送验证码成功!",['token' => $verification_code, 
        			'username' => $data['username']]);
        	}
        	break;
        }
        case 2:{//发送到手机, 直接返回验证码
        	$this->success("发送验证码成功!", ['token' => $verification_code, 
        			'username' => $data['username']]);
        	break;
        }
        }
		
	}

    // 用户注册
    public function register(){
    
        $validate = new Validate([
        	'user_login'		=> 'require|unique:user,user_login|max:30',
        	'name'				=> 'require|max:25',
        	'username'			=> 'require',
            'user_pass'          => 'require',
            'verification_code' => 'require'
        ]);

        $validate->message([
        	'user_login.require'		=> '请输入用户帐号!',
        	'user_login.unique'			=> '用户帐号已存在!',
        	'name.require'				=> '请输入用户实名!',
        	'username'					=> '请输入手机号，邮箱!',
            'user_pass.require'         => '请输入您的密码!',
            'verification_code.require' => '请输入数字验证码!'
        ]);

        $data = $this->request->param();
        if (!$validate->check($data)) {
            $this->error($validate->getError());
        }


		$user = [];
        $findUserWhere = [];
        
        if (Validate::is($data['username'], 'email')) {
            $user['user_email']          = $data['username'];
            $findUserWhere['user_email'] = $data['username'];
        } else if(preg_match('/(^(13\d|15[^4\D]|17[013678]|18\d)\d{8})$/', $data['username'])) {
            $user['mobile']          = $data['username'];
            $findUserWhere['mobile'] = $data['username'];
        } else {
            $this->error("请输入正确的手机或者邮箱格式!");
        }

        $errMsg = cmf_check_verification_code($data['username'], $data['verification_code']);
        if (!empty($errMsg)) {
            $this->error($errMsg);
        }
        
        $findUserCount = Db::name("user")
        					->where('user_login',$data["user_login"])
        					->count();

        if ($findUserCount > 0) {
            $this->error("此用户名已存在!");
        }

        $findUserCount = Db::name("user")->where($findUserWhere)->count();

        if ($findUserCount > 0) {
            $this->error("此账号已存在!");
        }


		$user['user_login'] = $data['user_login'];
		$user['name'] = $data['name'];
		$data['user_activation_key'] = $data['verification_code'];
        $user['create_time'] = time();
        $user['user_status'] = 1;
        $user['user_pass']   = cmf_password($data['user_pass']);
        $user['last_login_ip'] = get_client_ip();
        $user['point'] = '0';
        $user['balance'] = '0';
        $user['sex'] = '0';
        $user['avatar'] = '';
        $user['birthday'] = '0';
        $user['user_level'] = '1';
        $user['user_nickname'] = $data['username'];
        $user['last_login_time'] = time();
        
        $result = Db::name("user")->insert($user);

        if (empty($result)) {
            $this->error("注册失败,请重试!");
        }


		//清除verification_code
		cmf_clear_verification_code($data['username']);
		
        $this->success("注册并激活成功,请登录!");

    }

    // 用户登录 TODO 增加最后登录信息记录,如 ip
    public function login()
    {
        $validate = new Validate([
            'username' => 'require',
            'password' => 'require',
            'device_type' => 'require'
        ]);
        $validate->message([
            'username.require' => '请输入手机号,邮箱或用户名!',
            'password.require' => '请输入您的密码!',
            'device_type.require' => '请选择您的设备类型!'
        ]);

        $data = $this->request->param();
        if (!$validate->check($data)) {
            $this->error($validate->getError());
        }

        $findUserWhere = [];

        if (Validate::is($data['username'], 'email')) {
            $findUserWhere['user_email'] = $data['username'];
        } else if (preg_match('/(^(13\d|15[^4\D]|17[013678]|18\d)\d{8})$/', $data['username'])) {
            $findUserWhere['mobile'] = $data['username'];
        } else {
            $findUserWhere['user_login'] = $data['username'];
        }

        $findUser = Db::name("user")->where($findUserWhere)->find();
        if (empty($findUser)) {
            $this->error("用户不存在!");
        } else {
            switch ($findUser['user_status']) {
                case 0:
                    $this->error('您已被拉黑!');
                case 2:
                    $this->error('账户还没有验证成功!');
            }
			
            if (!cmf_compare_password($data['password'], $findUser['user_pass'])) {
                $this->error("密码不正确!");
            }
        }

        $allowedDeviceTypes = ['mobile', 'android', 'iphone', 'ipad', 'web', 'pc', 'mac'];

        if (empty($data['device_type']) || !in_array($data['device_type'], $allowedDeviceTypes)) {
            $this->error("请求错误,未知设备!");
        }

        $token = cmf_generate_user_token($findUser['id'],$data['device_type']);

        if (empty($token)) {
            $this->error("登录失败!token生成失败!");
        }
        
        //更新用户登录信息
        $new_data['last_login_ip'] = get_client_ip();
        $new_data['last_login_time'] = time();
        Db::name('user')->where('id',$findUser['id'])->update($new_data);
		
		$token = Db::name('user_token')->where('user_id', $findUser['id'])->find();
        $this->success("登录成功!", ['token' => $token, 'user' => $findUser]);
    }

    // 用户退出
    public function logout()
    {
        $userId = $this->getUserId();
        Db::name('user_token')->where([
            'token'       => $this->token,
            'user_id'     => $userId,
            'device_type' => $this->deviceType
        ])->update(['token' => '']);

        $this->success("退出成功!");
    }

    // 用户密码重置
    public function passwordReset()
    {
        $validate = new Validate([
            'username'          => 'require',
            'password'          => 'require',
            'verification_code' => 'require'
        ]);

        $validate->message([
            'username.require'          => '请输入手机号,邮箱!',
            'password.require'          => '请输入您的密码!',
            'verification_code.require' => '请输入数字验证码!'
        ]);

        $data = $this->request->param();
        if (!$validate->check($data)) {
            $this->error($validate->getError());
        }

        $userWhere = [];
        if (Validate::is($data['username'], 'email')) {
            $userWhere['user_email'] = $data['username'];
        } else if (preg_match('/(^(13\d|15[^4\D]|17[013678]|18\d)\d{8})$/', $data['username'])) {
            $userWhere['mobile'] = $data['username'];
        } else {
            $this->error("请输入正确的手机或者邮箱格式!");
        }

        $errMsg = cmf_check_verification_code($data['username'], $data['verification_code']);
        if (!empty($errMsg)) {
            $this->error($errMsg);
        }

        $userPass = cmf_password($data['password']);
        Db::name("user")->where($userWhere)->update(['user_pass' => $userPass]);

        $this->success("密码重置成功,请使用新密码登录!");

    }
}
