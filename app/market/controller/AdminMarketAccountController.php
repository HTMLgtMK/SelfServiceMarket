<?php
/**
 * 无人超市财务管理
 * author: GT
 * time: 2018.04.29 15:28
 */
 
namespace app\market\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\Validate;

class AdminMarketAccountController extends AdminBaseController {
	
	/**
	 * 财务管理首页
	 */
	public function index(){
		$where = array();
		$keyword = $this->request->param('keywords');
		$type_id = $this->request->param('type_id');
		$create_time_min = $this->request->param('create_time_min');
		$create_time_max = $this->request->param('create_time_max');
		if(!empty($keyword)){
			$where['b.name|a.remark'] = ['LIKE', "%keyword%"];
		}
		if(!empty($type_id)){
			$where['type_id'] = $type_id;
		}
		if(!empty($create_time_min) && !empty($create_time_max)){
			$create_time_min = strtotime($create_time_min);
			$create_time_max = strtotime($create_time_max);
			$where['a.create_time'] = ['between', [$create_time_min, $create_time_max]];
		}
		$accounts = Db::name('market_account')
						->alias('a')
						->field('a.*, b.name as type_name')
						->join('__MARKET_ACCOUNT_TYPE__ b', 'a.type_id=b.id')
						->where($where)
						->paginate(10);
		$accounts->appends(['keywords'=>"$keyword",'type_id'=>"$type_id",
				'create_time_min'=>"$create_time_min", 'create_time_max'=>"$create_time_max"]);
		$pages = $accounts->render();
		
		$this->assign('accounts', $accounts);
		$this->assign('page', $pages);
		
		$types = Db::name('market_account_type')->select();
		$this->assign('types', $types);
		
		return $this->fetch();
	}
	
	/**
	 * 新增账务
	 */
	public function add(){
		$types = Db::name('market_account_type')->select();
		$this->assign('types', $types);
		return $this->fetch();
	}
	
	/**
	 * 新增账务提交
	 */
	public function addPost(){
		if($this->request->isPost()){
			$data = $this->request->param();
			$validate = new Validate([
				'type_id'		=> 'require',
				'inout'			=> 'require',
				'amount'		=> 'require|number'
			]);
			$validate->message([
				'type_id.require'		=> '请选择财务类型!',
				'inout.require'			=> '请选择收支类型!',
				'amount.require'		=> '请输入金额!'
			]);
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			$data['create_time'] = time();
			$result = Db::name('market_account')->insert($data);
			if($result){
				$this->success("新建账务成功!", url('AdminMarketAccount/index'));
			}else{
				$this->success("新建账务失败!");
			}
		}
	}
	
}