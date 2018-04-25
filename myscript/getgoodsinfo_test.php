<?php
/**
 * 模拟终端获取商品详情和优惠信息
 * author: GT
 * time :2018/04/25 21:18
 */

$url = "http://localhost:8888/api/market/Goods/getGoodsInfo";

$arr = ['20128101000100000000000000000008', '20128101000100000000000000000007'];
 
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