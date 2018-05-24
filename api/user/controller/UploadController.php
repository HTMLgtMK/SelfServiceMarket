<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace api\user\controller;

use cmf\controller\RestUserBaseController;
use think\Db;

class UploadController extends RestUserBaseController
{
    // 用户上传单个文件
    public function one()
    {
        $file = $this->request->file('file');
        // 移动到框架应用根目录/public/upload/ 目录下
        $info     = $file->validate([
            /*'size' => 15678,*/
            'ext' => 'jpg,png,gif'
        ]);
        $fileMd5  = $info->md5();
        $fileSha1 = $info->sha1();

        $findFile = Db::name("asset")->where('file_md5', $fileMd5)->where('file_sha1', $fileSha1)->find();

        if (!empty($findFile)) {
            $this->success("上传成功!", ['url' => $findFile['file_path'], 'filename' => $findFile['filename']]);
        }
        $info = $info->move(ROOT_PATH . 'public' . DS . 'upload');
        if ($info) {
            $saveName     = $info->getSaveName();
            $originalName = $info->getInfo('name');//name,type,size
            $fileSize     = $info->getInfo('size');
            $suffix       = $info->getExtension();

            $fileKey = $fileMd5 . md5($fileSha1);

            $userId = $this->getUserId();
            Db::name('asset')->insert([
                'user_id'     => $userId,
                'file_key'    => $fileKey,
                'filename'    => $originalName,
                'file_size'   => $fileSize,
                'file_path'   => $saveName,
                'file_md5'    => $fileMd5,
                'file_sha1'   => $fileSha1,
                'create_time' => time(),
                'suffix'      => $suffix
            ]);

            $this->success("上传成功!", ['url' => $saveName, 'filename' => $originalName]);
        } else {
            // 上传失败获取错误信息
            $this->error($file->getError());
        }

    }

	/*修改*/
	public function uploadAvatar(){
		if($this->request->isPost()){
			$userId = $this->getUserId();
			$file = $this->request->file('file');
			// 移动到框架应用根目录/public/upload/ 目录下
			$info     = $file->validate([
				/*'size' => 15678,*/
				'ext' => 'jpg,png,gif'
			]);
			$fileMd5  = $info->md5();
			$fileSha1 = $info->sha1();

			$findFile = Db::name("asset")->where('file_md5', $fileMd5)->where('file_sha1', $fileSha1)->find();

			if (!empty($findFile)) {
				// 更新用户信息中的头像信息
				Db::name('user')->where('id', $userId)->update(['avatar' => $findFile['file_path']]);
				$this->success("上传成功!", ['url' => $findFile['file_path'], 'filename' => $findFile['filename']]);
			}
			$info = $info->move(ROOT_PATH . 'public' . DS . 'upload');
			if ($info) {
				$saveName     = $info->getSaveName();
				$originalName = $info->getInfo('name');//name,type,size
				$fileSize     = $info->getInfo('size');
				$suffix       = $info->getExtension();

				$fileKey = $fileMd5 . md5($fileSha1);

				Db::name('asset')->insert([
					'user_id'     => $userId,
					'file_key'    => $fileKey,
					'filename'    => $originalName,
					'file_size'   => $fileSize,
					'file_path'   => $saveName,
					'file_md5'    => $fileMd5,
					'file_sha1'   => $fileSha1,
					'create_time' => time(),
					'suffix'      => $suffix
				]);
				
				// 更新用户信息中的头像信息
				Db::name('user')->where('id', $userId)->update(['avatar' => $saveName]);
				
				$this->success("上传成功!", ['url' => $saveName, 'filename' => $originalName]);
			} else {
				// 上传失败获取错误信息
				$this->error($file->getError());
			}
		}
	}

}
