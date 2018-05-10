<?php
/**
 * 用户授权请求测试
 * author: GT
 * time: 2018.05.08 21:53
 */
 
 $url = "http://localhost:8888/api/user/User_Grant/grantReq";
 
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_POST, 1);
 curl_setopt($ch, CURLOPT_HEADER, 0);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 $data = curl_exec($ch);
 curl_close($ch);
 
 echo $data;
?>