<?php
/**
 * 支付订单提交接口测试
 * author: GT
 * time: 2018.04.23 09:13
 */
$goods_detail_str = '[{"images":null,"address":"test","batch_number":"123456","manufacture_date":1524240000,"price":13,"type_id":3,"name":"test","goods_id":"20128101000300000000000000000002","company":"test","id":3,"status":1}]';
var_dump($goods_detail_str);echo "</br>";
$goods_detail_str = base64_encode($goods_detail_str);// !important

$arr = [
	'user_id'			=> '1',
	'store_id' 			=> '1',
	'terminal_id'		=> '1',
	'pay_amount'		=> '1',
	'discount_amount'	=> '0',
	'total_amount'		=> '1',
	'goods_detail'		=> "$goods_detail_str"
];

$url = "http://localhost:8888/api/market/Goods_Sale/submit";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($arr));
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$data = curl_exec($ch);
echo curl_error($ch);
curl_close($ch);
echo $data;
?>