<?php
/*
 * 由GT重新编辑
 * author: ThinkCMF5 老猫
 * time: 2018-03-30 21:00
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

/**
 * Class AdminstratorController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   => '管理组',
 *     'action' => 'default',
 *     'parent' => 'user/AdminIndex/default',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   => '',
 *     'remark' => '管理组'
 * )
 */
class AdminstratorController extends AdminBaseController
{

    /**
     * 管理员列表
     * @adminMenu(
     *     'name'   => '管理员',
     *     'parent' => 'default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '管理员管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $where = [];
        /**搜索条件**/
        $userLogin = $this->request->param('user_login');
		$userName = $this->request->param('user_name');
		$userMobile = trim($this->request->param('user_mobile'));
		$userPostId = $this->request->param('post_id');
		$userStatus = $this->request->param('user_status','1','intval');
		
		$where['a.user_status'] = $userStatus;
		
        if ($userLogin) {
            $where['user_login'] = ['like', "%$userLogin%"];
        }

		if ($userName) {
            $where['a.name'] = ['like', "%$userName%"];
        }

        if ($userMobile) {
            $where['mobile'] = ['like', "%$userMobile%"];;
        }
        
        if($userPostId){
        	$where['a.post_id'] = ['=',"$userPostId"];
        }
        
        $users = Db::name('adminstrator')
        	->alias('a')
        	->field("a.*,b.name as post_name")
        	->join("__MARKET_POSTS__ b",'a.post_id=b.id')
            ->where($where)
            ->order("id DESC")
            ->paginate(10);
        $users->appends(['user_login' => $userLogin,'user_name' => $userName, 'user_mobile' => $userMobile, 'post_id'=>"$userPostId"]);
        // 获取分页显示
        $page = $users->render();

        $posts = Db::name('market_posts')->select();
        $this->assign("page", $page);
        $this->assign("posts", $posts);
        $this->assign("users", $users);
        return $this->fetch();
    }

    /**
     * 管理员添加
     * @adminMenu(
     *     'name'   => '管理员添加',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '管理员添加',
     *     'param'  => ''
     * )
     */
    public function add(){
    	$posts = Db::name('market_posts')->order("id DESC")->select();
        $this->assign("posts", $posts);
        return $this->fetch();
    }

    /**
     * 管理员添加提交
     * @adminMenu(
     *     'name'   => '管理员添加提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '管理员添加提交',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        if ($this->request->isPost()) {
            if (!empty($_POST['post_id'])) {
                $post_id = $_POST['post_id'];
                unset($_POST['post_id']);
                if($post_id == 1){
                	$this->error("为了超市的安全，不可再创建店主！");
                }
                
                $result = $this->validate($this->request->param(), 'Adminstrator');
                if ($result !== true) {
                    $this->error($result);
                } else {
                    $data['user_login'] = $this->request->param('user_login');
                    $data['name'] = $this->request->param('name');
                    $data['mobile'] = $this->request->param('mobile');
                    $data['user_pass'] = cmf_password($this->request->param('user_pass'));
                    $data['user_status'] = "1";
                    $data['sex'] = $this->request->param('sex');
                    $data['create_time'] = time();
                    $data['post_id'] = "$post_id";
                    
                    $result             = DB::name('Adminstrator')->insertGetId($data);
                    
                    if ($result !== false) {
                    	//该岗位在岗人数新增1人
                    	Db::name('market_posts')->where('id',"$post_id")->update(['count' => ['exp','count+1']]);
                        $this->success("添加成功！", url("Adminstrator/index"));
                    } else {
                        $this->error("添加失败！");
                    }
                }
            } else {
                $this->error("请为此用户指定岗位！");
            }
        }
    }

    /**
     * 管理员编辑
     * @adminMenu(
     *     'name'   => '管理员编辑',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '管理员编辑',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $id    = $this->request->param('id', 0, 'intval');
        $posts = DB::name('market_posts')->order("id DESC")->select();
        $this->assign("posts", $posts);

        $user = DB::name('adminstrator')->where(["id" => $id])->find();
        $this->assign("user",$user);
        return $this->fetch();
    }

    /**
     * 管理员编辑提交
     * @adminMenu(
     *     'name'   => '管理员编辑提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '管理员编辑提交',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {
        if ($this->request->isPost() && !empty($_POST['id'])) {
        	$id = $_POST['id'];
            if (!empty($_POST['post_id'])) {
                $post_id = $_POST['post_id'];//最新岗位
                unset($_POST['post_id']);
                if($post_id == 1){
                	$this->error("为了超市的安全，不可再创建店主！");
                }
                
                $result = $this->validate($this->request->param(), 'Adminstrator');
                if ($result !== true) {
                    $this->error($result);
                } else {
                	$post_id_org = Db::name('adminstrator')->where('id',"$id")
        							->limit(1)->column("post_id");
        			$post_id_org = $post_id_org['0'];//获取原来的岗位id
        			//修改的数据
                    $data['user_login'] = $this->request->param('user_login');
                    $data['name'] = $this->request->param('name');
                    $data['mobile'] = $this->request->param('mobile');
                    $data['user_pass'] = cmf_password($this->request->param('user_pass'));
                    $data['user_status'] = "1";
                    $data['sex'] = $this->request->param('sex');
                    $data['post_id'] = "$post_id";
                    $data['user_status'] = $this->request->param('user_status');
                    
                    $result             = DB::name('Adminstrator')->where('id',"$id")->update($data);
                    if ($result !== false) {
                    	//修改在岗人数
                    	Db::name("market_posts")->where('id',"$post_id_org")->setDec('count');
                    	Db::name("market_posts")->where('id',"$post_id")->setInc('count');
                        $this->success("修改成功！", url("Adminstrator/index"));
                    } else {
                        $this->error("修改失败！");
                    }
                }
            } else {
                $this->error("请为此用户指定岗位！");
            }
        }else{
        
        	$this->error("1111");
        }
    }

    /**
     * 管理员个人信息修改
     * @adminMenu(
     *     'name'   => '个人信息',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '管理员个人信息修改',
     *     'param'  => ''
     * )
     */
    public function userInfo()
    {
        $id   = cmf_get_current_admin_id();
        $user = Db::name('user')->where(["id" => $id])->find();
        $this->assign($user);
        return $this->fetch();
    }

    /**
     * 管理员个人信息修改提交
     * @adminMenu(
     *     'name'   => '管理员个人信息修改提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '管理员个人信息修改提交',
     *     'param'  => ''
     * )
     */
    public function userInfoPost()
    {
        if ($this->request->isPost()) {

            $data             = $this->request->post();
            $data['birthday'] = strtotime($data['birthday']);
            $data['id']       = cmf_get_current_admin_id();
            $create_result    = Db::name('user')->update($data);;
            if ($create_result !== false) {
                $this->success("保存成功！");
            } else {
                $this->error("保存失败！");
            }
        }
    }

    /**
     * 管理员删除
     * @adminMenu(
     *     'name'   => '管理员删除',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '管理员删除',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id == 1) {
            $this->error("最高管理员不能删除！");
        }
        
        if(cmf_get_current_admin_id() == $id){
        	$this->error("当前员工不能删除!");
        }
        
        $post_id = Db::name('adminstrator')->where('id',"$id")->limit(1)->column('post_id');
        $post_id = $post_id['0'];

        if (Db::name('adminstrator')->delete($id) !== false) {
        	//在岗人数减1
        	Db::name('market_posts')->where('id',"$post_id")->setDec('count',1);
            $this->success("删除成功！",url('adminstrator/index'));
        } else {
            $this->error("删除失败！");
        }
    }

    /**
     * 停用管理员
     * @adminMenu(
     *     'name'   => '停用管理员',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '停用管理员',
     *     'param'  => ''
     * )
     */
    public function offpost()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (!empty($id)) {
        	if( $id == 1){
        		$this->error('店主不可停用!');
        	}
        	if( cmf_get_current_admin_id() == $id){
        		$this->error('当前用户不可停用!');
        	}
        
            $result = Db::name('adminstrator')->where(["id" => $id, "user_status" => 1])->setField('user_status', '0');
            if ($result !== false) {
            	//在岗人数减1
            	$post_id = Db::name('adminstrator')->where('id',"$id")->limit(1)->column('post_id');
            	$post_id = $post_id['0'];
            	Db::name('market_posts')->where('id',"$post_id")->setDec('count');
                $this->success("管理员停用成功！", url("adminstrator/index"));
            } else {
                $this->error('管理员停用失败！');
            }
        } else {
            $this->error('数据传入失败！');
        }
    }

    /**
     * 启用管理员
     * @adminMenu(
     *     'name'   => '启用管理员',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '启用管理员',
     *     'param'  => ''
     * )
     */
    public function activeUser()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (!empty($id)) {
            $result = Db::name('adminstrator')->where('id',"$id")->setField('user_status', '1');
            if ($result !== false) {
           		 //在岗人数加1
            	$post_id = Db::name('adminstrator')->where('id',"$id")->limit(1)->column('post_id');
            	$post_id = $post_id['0'];
            	Db::name('market_posts')->where('id',"$post_id")->setInc('count');
                $this->success("管理员启用成功！", url("adminstrator/index"));
            } else {
                $this->error('管理员启用失败！');
            }
        } else {
            $this->error('数据传入失败！');
        }
    }
}
