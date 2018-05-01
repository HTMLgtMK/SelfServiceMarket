<?php
/**
 * 将menu列表数据转换成rule中的数据
 * author : GT
 * time: 2018.04.30 14:12
 * 注： 完成转换后, 数据会有空格, 再利用记事本去掉即可 '[\s] -> ' , /[\s] -> /
 */
 
$file_menu = "temp2.sql";
$file_rule = "temp_auth.sql";
$menufilehandle = fopen($file_menu, "r");
$rulefilehandle = fopen($file_rule, "w");//自动创建文件
$index = 1;
while(!feof($menufilehandle)){
	$line = fgets($menufilehandle);
	$part = explode(",",$line);
	/*array(13) {
	  [0]=>
	  string(4) "(241"
	  [1]=>
	  string(4) " 237"
	  [2]=>
	  string(2) " 2"
	  [3]=>
	  string(2) " 0"
	  [4]=>
	  string(6) " 10000"
	  [5]=>
	  string(9) " 'portal'"
	  [6]=>
	  string(11) " 'AdminTag'"
	  [7]=>
	  string(9) " 'delete'"
	  [8]=>
	  string(3) " ''"
	  [9]=>
	  string(21) " '删除文章标签'"
	  [10]=>
	  string(3) " ''"
	  [11]=>
	  string(22) " '删除文章标签')"
	  [12]=>
	  string(0) ""
	}*/
	//(1, 1, 'admin', 'admin_url', 'admin/Hook/index', '', '钩子管理', ''),
	$app = rtrim(str_replace("'",'',$part[5]));
	$controller = rtrim(str_replace("'",'',$part[6]));
	$action =  rtrim(str_replace("'",'',$part[7]));
	$title =  rtrim(str_replace("'",'',$part[9]));
	
	$s = "($index, 1, '$app', 'admin_url', '$app/$controller/$action' ,'', '$title', ''),\r\n";
	fwrite($rulefilehandle,$s);
	++$index;
	echo $s,"\r\n";
}

fclose($menufilehandle);
fclose($rulefilehandle);
?>