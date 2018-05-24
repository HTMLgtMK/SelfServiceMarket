<?php 
/**
 * 余额支付预下单 测试
 * author: GT
 * time: 2018.05.23
 */
$url = "http://localhost:8888/api/market/Goods_Sale/balance_qrpay";
$arr = [
	'user_id'		=> '4',
	'out_trade_no'	=> '20180524095400807052',
];

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