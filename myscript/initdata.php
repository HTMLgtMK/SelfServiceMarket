<?php
	header("Content-Type:text/html;Charset=utf8");
	$host="localhost";
	$user="root";
	$pwd="gt";
	$db_name="db_market";
	$conn = mysqli_connect($host,$user,$pwd);
	mysqli_select_db($conn,$db_name);
	//mysqli_query($conn, "select names 'utf8mb4';");
	include_once("executesql.php");
	sp_execute_sql($conn,"initdata.sql","tb_");
	mysqli_close($conn);
?>