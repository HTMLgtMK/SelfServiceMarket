<?php
/**
 * 优惠广场数据测试
 * author: GT
 * time: 2018.05.19
 */
 
 $url = "http://localhost:8888/api/market/Discount/index";
 
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