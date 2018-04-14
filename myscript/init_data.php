<?php
	$conn = mysqli_connect("localhost","root","gt");
	mysqli_select_db($conn,"db_market");
	
	$time = time();
	
	$sql1 = "INSERT INTO `tb_store`(`id`,`name`,`status`,`address`,`adminstrator_id`,`create_time`) VALUES"
			. "('1','store 1', '1', 'weihai city', '1', '$time');";
	
	$sql2 = "INSERT INTO `tb_store_terminal`(`id`,`ip`,`salecount`,`status`,`store_id`,`remark`) VALUES"
			. "('1','0.0.0.0','0','1','1','');";
	$res = mysqli_query($conn,$sql1);
	var_dump($res);
	if(!$res){
		echo mysqli_error($conn),"</br>";
	}
	$res = mysqli_query($conn,$sql2);
	var_dump($res);
	if(!$res){
		echo mysqli_error($conn),"</br>";
	}
	mysqli_close($conn);
?>