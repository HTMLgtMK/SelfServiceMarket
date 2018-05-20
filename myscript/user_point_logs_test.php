<?php
/**
 * 用户授权结果测试
 * author: GT
 * time: 2018.05.08 22:31
 */
 
 $url = "http://localhost:8888/api/user/User_Point/pointIndex";
 
 $header[] = "XX-Token:1f431343e11227553a235582b098b238954ef38c11e659ca7f20974819131e7a";
 $header[] = "XX-Device-Type:android";
 $header[] = "Content-Length:0";
 
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_POST, 1);

 $data = curl_exec($ch);
 curl_close($ch);
 
 echo $data;
?>