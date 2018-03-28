<?php
/**
 * 首页控制器
 * Created by dengxiaolong 2057844718@qq.com.
 * Date: 2016/11/10
 */
namespace Admin\Controller;
use Think\Controller;

class UploadController extends CommonController {	
	
	
	//文件上传处理
	public function uploadFile_do() {
		// 设置不同类型附件上传大小1.5M/swf为100k
		$type =explode('.',$_FILES['sourceFile']['name']);
		$sizae = ($type[1]=='swf')?(1.5*1024*1024):(1.5*1024*1024);

		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     $sizae;
		$upload->exts      =     array('mp4','flv','swf');// 设置附件上传类型		
		$upload->rootPath  =     C("UPLOAD_PATH"); // 设置附件上传根目录
		$upload->savePath  =     ''; // 设置附件上传（子）目录\		
		$info   =   $upload->upload();
		if(!$info) {// 上传错误提示错误信息
			$data['status']=false;
			$data['content']=$upload->getError();
		}else{			
			//上传成功后返回文件保存地址
			$data['status']=true;			
			$data['content']= "/".C("UPLOAD_PATH").$info['sourceFile']['savepath'].$info['sourceFile']['savename'];
			$data['info'] = $this->getWorld($info['sourceFile']['name']);
		}
		echo json_encode($data);
    }
    
	//文件上传处理
	public function uploadImage_do() {		
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     20*1024*1024;// 设置附件上传大小
		$upload->exts      =     array('png','gif','jpg','jpeg','bmp');// 设置附件上传类型		
		$upload->rootPath  =     C("UPLOAD_PATH"); // 设置附件上传根目录
		$upload->savePath  =     ''; // 设置附件上传（子）目录\		
		$info   =   $upload->upload();
		if(!$info) {// 上传错误提示错误信息
			$data['status']=false;
			$data['content']=$upload->getError();
		}else{			
			//上传成功后返回文件保存地址
			$data['status']=true;			
			$data['content']= "/".C("UPLOAD_PATH").$info['sourceFile']['savepath'].$info['sourceFile']['savename'];		
		}
		echo json_encode($data);
    }
	
	//删除上传文件
	public function deleteFile(){
		//获取文件名
		if(IS_AJAX){//判断是否是AJAX提交和来源网址是否正常

			$ywjName=I('post.ywjName','','htmlspecialchars');
			$yltName=I('post.yltName','','htmlspecialchars');
			if(unlink('.'.$yltName)||unlink('.'.$ywjName)){
				$result = array('status'=>true,'content'=>'删除成功');
			}else{
				$result = array('status'=>false,'content'=>'删除失败');
			}
			echo json_encode($result);
		}
		
	}	
	
}