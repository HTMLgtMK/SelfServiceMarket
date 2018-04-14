<?php
/**
 * 支付宝支付预下单接口测试
 * author: GT
 * time: 2018.04.14 10:11
 */

class GoodsDetail {
	
    // 商品编号(Ecode)
    private $goodsId;

    //支付宝定义的统一商品编号
    private $alipayGoodsId;

    // 商品名称
    private $goodsName;

    // 商品数量
    private $quantity;

    // 商品价格，此处单位为元，精确到小数点后2位
    private $price;

    // 商品类别
    private $goodsCategory;

    // 商品详情
    private $body;

    private $goodsDetail = array();

    //单个商品json字符串
    //private $goodsDetailStr = NULL;

    //获取单个商品的json字符串
    public function getGoodsDetail()
    {
        return $this->goodsDetail;
        /*$this->goodsDetailStr = "{";
        foreach ($this->goodsDetail as $k => $v){
            $this->goodsDetailStr.= "\"".$k."\":\"".$v."\",";
        }
        $this->goodsDetailStr = substr($this->goodsDetailStr,0,-1);
        $this->goodsDetailStr.= "}";
        return $this->goodsDetailStr;*/
    }

    public function setGoodsId($goodsId)
    {
        $this->goodsId = $goodsId;
        $this->goodsDetail['goods_id'] = $goodsId;
    }

    public function getGoodsId()
    {
        return $this->goodsId;
    }

    public function setAlipayGoodsId($alipayGoodsId)
    {
        $this->alipayGoodsId = $alipayGoodsId;
        $this->goodsDetail['alipay_goods_id'] = $alipayGoodsId;
    }

    public function getAlipayGoodsId()
    {
        return $this->alipayGoodsId;
    }

    public function setGoodsName($goodsName)
    {
        $this->goodsName = $goodsName;
        $this->goodsDetail['goods_name'] = $goodsName;
    }

    public function getGoodsName()
    {
        return $this->goodsName;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        $this->goodsDetail['quantity'] = $quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        $this->goodsDetail['price'] = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setGoodsCategory($goodsCategory)
    {
        $this->goodsCategory = $goodsCategory;
        $this->goodsDetail['goods_category'] = $goodsCategory;
    }

    public function getGoodsCategory()
    {
        return $this->goodsCategory;
    }

    public function setBody($body)
    {
        $this->body = $body;
        $this->goodsDetail['body'] = $body;
    }

    public function getBody()
    {
        return $this->body;
    }


}
// 创建一个商品信息，参数含义分别为商品id（使用国标）、名称、单价（单位为分）、数量，如果需要添加商品类别，详见GoodsDetail
$goods1 = new GoodsDetail();
$goods1->setGoodsId("apple-01");
$goods1->setGoodsName("iphone");
$goods1->setPrice(3000);
$goods1->setQuantity(1);
//得到商品1明细数组
$goods1Arr = $goods1->getGoodsDetail();

// 继续创建并添加第一条商品信息，用户购买的产品为“xx牙刷”，单价为5.05元，购买了两件
$goods2 = new GoodsDetail();
$goods2->setGoodsId("apple-02");
$goods2->setGoodsName("ipad");
$goods2->setPrice(1000);
$goods2->setQuantity(1);
//得到商品1明细数组
$goods2Arr = $goods2->getGoodsDetail();

$goodsDetailList = array($goods1Arr,$goods2Arr);

$goods_detail_str = json_encode($goodsDetailList);

echo json_last_error(),"</br>";

$arr = [
	'user_id'			=> '1',
	'store_id' 			=> '1',
	'terminal_id'		=> '1',
	'pay_amount'		=> '18',
	'discount_amount'	=> '2',
	'total_amount'		=> '20',
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
var_dump($data);
?>