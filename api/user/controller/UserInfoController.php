<?php
/**
 * 用户信息控制器
 * author: GT
 * time: 2018.05.21
 */
 
namespace api\user\controller;

use cmf\controller\RestUserBaseController;
use think\Db;
use think\Response;
use think\exception\HttpResponseException;

class UserInfoController extends RestUserBaseController {
	
	/*获取avatar图片*/
	public function avatar(){
		$avatar = $this->user['avatar'];
		$dir = ROOT_PATH . 'public' . DS . 'upload';
		$realPath = $dir . DS . $avatar;
		$content='';
		try{
			$content = file_get_contents($realPath);
		}catch(\Exception $e){
			// continue ?
		}
		
		$header['Content-Type'] = "image/png";
		$response = Response::create($content, 'image/png')->header($header);
		throw new HttpResponseException($response);
	}
	
}
