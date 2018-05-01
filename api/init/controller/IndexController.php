<?php
/**
 * 初始化数据接口
 * author: GT
 * time: 2018.04.30 23:16
 */
 
namespace api\init\controller;

use cmf\controller\RestBaseController;
use think\Db;
use think\Config;

class IndexController extends RestBaseController {
	
	public function initdata(){
		$dbConfig = Config::get('database');
		$sql_initdata = cmf_split_sql( ROOT_PATH . 'api/init/data/initdata.sql', $dbConfig['prefix'], $dbConfig['charset']);
		$db = Db::connect($dbConfig);
		foreach($sql_initdata as $sql){
			 $sqlToExec = $sql . ';';
			 $result = sp_execute_sql($db, $sqlToExec);
			 if (!empty($result['error'])) {
				echo "<font color='#ff0000'>",$result['message'],"</font>/br>";
				echo "sql:",$sql,"</br>";
				echo "<font color='#ff0000'> exception:",$result['exception'], "</font></br>";
			} else {
				echo $result['message'],"</br>";
				echo "sql:",$sql,"</br>";
			}
		}
	}
}
