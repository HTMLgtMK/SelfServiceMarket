<?php
/**
 * 用户头像获取测试
 * author: GT
 * time: 2018.05.21 16:10
 */
 
 $url = "http://localhost:8888/api/user/User_Info/avatar";
 
 $header[] = "XX-Token:04eb5d1c288c0a7fc4f57dd57fc8ea129fd8515916717749b9fc3108b29f50d3";
 $header[] = "XX-Device-Type:android";
 $header[] = "Content-Length:0";
 
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_POST, 1);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
 curl_setopt($ch, CURLOPT_HEADER, 0);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
 
 $data = curl_exec($ch);
 curl_close($ch);
?>