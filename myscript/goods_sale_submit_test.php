<?php
/**
 * 支付订单提交接口测试
 * author: GT
 * time: 2018.04.23 09:13
 */
$goods_detail_str = '[{"images":"","address":"山东威海","batch_number":"123456","type_id":2,"discount":{"extent":1,"rest":2147483647,"name":"立减5分","goods_type_id":2,"id":4,"open":1,"coin":-5,"discount_id":11},"goods_id":"20128101000200000000000000000011","manufacture_date":1526745600,"price":300,"name":"零度可乐","company":"hitwh-gt","id":2,"status":1},{"images":"","address":"山东威海","batch_number":"1233456","type_id":30,"discount":null,"goods_id":"20128101003000000000000000000002","manufacture_date":1526745600,"price":250,"name":"卫龙大面筋106g","company":"hitwh-gt","id":30,"status":1}]';
var_dump($goods_detail_str);echo "</br>";
$goods_detail_str = base64_encode($goods_detail_str);// !important


// php 后台获取得到的base64编码: W3siaW1hZ2VzIjoiIiwiYWRkcmVzcyI6IuWxseS4nOWogea1tyIsImJhdGNoX251bWJlciI6IjEyMzQ1NiIsInR5cGVfaWQiOjIsImRpc2NvdW50Ijp7ImV4dGVudCI6MSwicmVzdCI6MjE0NzQ4MzY0NywibmFtZSI6Iueri WHjzXliIYiLCJnb29kc190eXBlX2lkIjoyLCJpZCI6NCwib3BlbiI6MSwiY29pbiI6LTUsImRpc2NvdW50X2lkIjoxMX0sImdvb2RzX2lkIjoiMjAxMjgxMDEwMDAyMDAwMDAwMDAwMDAwMDAwMDAwMTEiLCJtYW51ZmFjdHVyZV9kYXRlIjoxNTI2NzQ1NjAwLCJwcmljZSI6MzAwLCJuYW1lIjoi6Zu25bqm5Y v5LmQIiwiY29tcGFueSI6ImhpdHdoLWd0IiwiaWQiOjIsInN0YXR1cyI6MX1d
// PC端传送的base64编码: W3siaW1hZ2VzIjoiIiwiYWRkcmVzcyI6IuWxseS4nOWogea1tyIsImJhdGNoX251bWJlciI6IjEyMzQ1NiIsInR5cGVfaWQiOjIsImRpc2NvdW50Ijp7ImV4dGVudCI6MSwicmVzdCI6MjE0NzQ4MzY0NywibmFtZSI6Iueri+WHjzXliIYiLCJnb29kc190eXBlX2lkIjoyLCJpZCI6NCwib3BlbiI6MSwiY29pbiI6LTUsImRpc2NvdW50X2lkIjoxMX0sImdvb2RzX2lkIjoiMjAxMjgxMDEwMDAyMDAwMDAwMDAwMDAwMDAwMDAwMTEiLCJtYW51ZmFjdHVyZV9kYXRlIjoxNTI2NzQ1NjAwLCJwcmljZSI6MzAwLCJuYW1lIjoi6Zu25bqm5Y+v5LmQIiwiY29tcGFueSI6ImhpdHdoLWd0IiwiaWQiOjIsInN0YXR1cyI6MX1d

$arr = [
	'user_id'			=> '1',
	'store_id' 			=> '1',
	'terminal_id'		=> '1',
	'pay_amount'		=> '535',
	'discount_amount'	=> '15',
	'total_amount'		=> '550',
	'goods_detail'		=> "$goods_detail_str",
	'discount_detail'	=> 'W10='
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