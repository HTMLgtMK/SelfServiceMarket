<?php
/**
 * 执行sql文件中的sql语句
 * @param $db database 资源连接
 * @param $file sql文件
 * @param $tablepre 数据表前缀
 */
function sp_execute_sql($db,$file,$tablepre){
    //读取SQL文件
    $sql = file_get_contents($file);
    $sql = str_replace("\r", "\n", $sql);
    $sql = explode(";\n", $sql);
    
    //替换表前缀
    $default_tablepre = "tb_";
    $sql = str_replace($default_tablepre,$tablepre, $sql);
    
    //开始安装
    echo '开始安装数据库...',"<br/>";
    foreach ($sql as $item) {
        $item = trim($item);
        if(empty($item) || $item=='') continue;
        preg_match('/CREATE TABLE IF NOT EXISTS `([^ ]*)`/', $item, $matches);
        if($matches) {
			$msg = "执行=>".$item;
			echo $msg,"<br/>";
            $table_name = $matches[1];
            $msg  = "创建数据表{$table_name}";
            if(false != mysqli_query($db,$item)){
                echo $msg . ' 完成',"<br/>";
            } else {
				echo "<span style='color:red;'>",mysqli_error($db),"</span><br/>";
                echo "<span style='color:red;'>",$msg . ' 失败！',"</span><br/>";
            }
        } else {
			$msg = "执行=>".$item;
			echo $msg,"<br/>";
            if(false == mysqli_query($db,$item)){
				echo "<span style='color:red;'>",mysqli_error($db),"</span><br/>";
			}
        }
    }
}
?>