<?php
/**
 * 申请验证码脚本
 * author: GT
 * time: 2018-04-01 20:08
 */
 
 if(empty($_GET['username'])){
 	echo '{"code":"0","msg":"请给定手机号或邮箱!"}';
 	return;
 }
 
 
 $username = $_GET['username'];
 $url = "http://localhost:8888/api/user/public/send_verify_code";
 
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL,$url);
 curl_setopt($ch, CURLOPT_POST,1);
 curl_setopt($ch, CURLOPT_HEADER, 0);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['username'=>"$username"]));
 $data = curl_exec($ch);
 
 curl_close($ch);
 
echo $data;

?>
