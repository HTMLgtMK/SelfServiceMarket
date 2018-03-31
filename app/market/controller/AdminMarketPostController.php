<?php
/**
 * 无人超市 岗位管理
 * author: GT
 * time: 2018-03-31 09:16
 */

namespace app\market\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class AdminMarketPostController extends AdminBaseController {
	
	/**
	 * 岗位列表
	 */
	public function index(){
		/*按搜索条件过滤*/
		$name = $this->request->param('post_name');
		$salary_min = $this->request->param('salary_min');
		$salary_max = $this->request->param('salary_max');
		$address	= $this->request->param('address');
		$role_id	= $this->request->param('role_id');

		$where = [];
		if(!empty($name)){
			$where['a.name'] = [ 'like', "%$name%" ];
		}

		if(!empty($salary_min) && !empty($salary_max)){
			$where['salary'] = ['between', ["$salary_min", "$salary_max"]];
		}

		if(!empty($address)){
			$where['address'] = ['like' , "%$address%" ];
		}

		if(!empty($role_id) && $role_id!=0){
			$where['role'] = [ '=' , "$role_id" ];
		}

		$posts = Db::name('market_posts')
					->alias('a')
					->field('a.*,b.name AS role_name')
					->join('__ROLE__ b','a.role=b.id')
					->where($where)
					->paginate(10);

		$arr = ['post_name'=>"$name", 'salary_min'=>"$salary_min",
							'salary_max'=>"$salary_max", 'address'=>"$address",
							'role_id'=>"$role_id"];
		$posts->appends($arr);
		//获取分页显示
		$pages = $posts->render();

		$rolesSrc = Db::name('role')->select();
        $roles    = [];
        foreach ($rolesSrc as $r) {
            $roleId           = $r['id'];
            $roles["$roleId"] = $r;
        }

		$this->assign('posts', $posts);
		$this->assign('roles', $roles);
		$this->assign('page', $pages);
		return $this->fetch();
	}

	/**
	 * 添加岗位
	 */
	public function add(){
		$roles = Db::name('role')->where(['status' => 1])->order("id DESC")->select();
        $this->assign("roles", $roles);
        return $this->fetch();
	}
	
	
	/**
	 * 添加岗位提交
	 */
	public function addPost(){
		if($this->request->isPost()){ 
			$name = $this->request->param('post_name');
			$salary = $this->request->param('post_salary');
			$address = $this->request->param('post_address');
			$role_id = $this->request->param('role_id');
			$remark = $this->request->param('remark');//备注
			
			$post = [
				'name' 		=> "$name",
				'salary' 	=> "$salary",
				'address' 	=> "$address",
				'count' 	=> "0",
				'role' 		=> "$role_id",
				'remark'	=> "$remark"
			];
			
			$result = $this->validate($post,"AdminMarketPost.add");
			if($result !== true){
				return $this->error($result);
			}else{
				$result = Db::name('market_posts')->insertGetId($post);
				if($result){
					return $this->success("添加岗位成功!",url('market/AdminMarketPost/index'));
				}else{
					return $this->error("添加岗位失败!");
				}
			}
		}
	}
	
	
	/**
	 * 岗位编辑
	 */
	public function edit(){
        $id    = $this->request->param('id', 0, 'intval');
        $post = DB::name('market_posts')->where(["id" => $id])->find();
        $this->assign('post',$post);
        $roles = DB::name('role')->where(['status' => 1])->order("id DESC")->select();
        $this->assign("roles", $roles);
        
        return $this->fetch();
    }
    
    
    /**
     * 岗位编辑提交
     */
    public function editPost(){
    	if($this->request->isPost()){
    		$result = $this->validate($this->request->param(),'AdminMarketPost.edit');
    		if($result !== true){
    			return $this->error($result);
    		}else{
    			$result = Db::name('market_posts')->update($this->request->param());
    			if($result == true){
    				return $this->success("更新成功!");
    			}else{
    				return $this->success("更新失败!");
    			}
    		}
    	}
    }
    
    
    /**
     * 删除岗位
     */
    public function delete(){
        $id = $this->request->param('id', 0, 'intval');
        if ($id == 1) {
            $this->error("店主不能删除！");
        }
        $admin_id = cmf_get_current_admin_id();
        $admin = Db::name('adminstrator')->where('id',"$admin_id")->find();
        if($admin['post_id'] == $id){
        	$this->error("当前用户的岗位不能删除!");
        }
        
        $post = Db::name('market_posts')->where('id',"$id")->find();
        if($post['count']>0){
        	$this->error("该岗位存在在岗员工!");
        }

        if (Db::name('market_posts')->delete($id) !== false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
	
}



