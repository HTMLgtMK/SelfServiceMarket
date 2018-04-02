<?php
/**
 * 用户登录脚本
 * author: GT
 * time: 2018-04-01 20:23
 */
 
 
 $user_data = [
 		'username'		 => "GT_GameEmail@163.com",
 		'password'		 => '123456',
 		'device_type'	=> "pc"
 ];
 $url = "http://localhost:8888/api/user/public/login";
 
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
