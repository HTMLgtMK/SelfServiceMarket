<?php
/**
 * 用户授权关闭测试
 * author: GT
 * time: 2018.05.09 21:44
 */
 
 $url = "http://localhost:8888/api/user/User_Grant/closeGrant";
 $arr = ['token' => 'B38t2hWNlM47TgWCfSNIt6csEmnDtmFk'];
 
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_POST, 1);
 curl_setopt($ch, CURLOPT_HEADER, 0);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arr));
 $data = curl_exec($ch);
 curl_close($ch);
 
 echo $data;
?>