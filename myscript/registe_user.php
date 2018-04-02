<?php
/**
 * 注册新用户脚本
 * author: GT
 * time: 2018-04-01 20:16
 */
 
 
 $user_data = [
 		'username'		 => "GT_GameEmail@163.com",
 		'name'			 => "GT",
 		'user_login'	 => "gt0001",
 		'user_pass'		 => '123456',
 		'verification_code'	=> "238282"
 ];
 $url = "http://localhost:8888/api/user/public/register";
 
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL,$url);
 curl_setopt($ch, CURLOPT_POST, 1);
 curl_setopt($ch, CURLOPT_HEADER, 0);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($user_data));
 $data = curl_exec($ch);
 
 curl_close($ch);
 
echo $data;

?>
