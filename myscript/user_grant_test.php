<?php
/**
 * 用户授权结果测试
 * author: GT
 * time: 2018.05.08 22:31
 */
 
 $url = "http://localhost:8888/api/user/User_Grant/grant";
 $arr = ['token' => 'Yi2nPbNO37DnRQNHVL2Q5NzPYcum85XZ', 'user_id'=>"4"];
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