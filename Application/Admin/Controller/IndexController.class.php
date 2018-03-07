<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class IndexController extends CommonController {
    public function index(){
		$this->display();
    }
	public function top(){
		$this->display();
    }
	public function left(){
		$this->display();
    }
	public function right(){	
		$this->display();
    }
	//获得当前用户媒体
	private function get_media(){	
		//媒体表
		$medium =  M('cps_medium','mygame_','DB_CONFIG_CHONG');
		$medium_access = M('media_access','mygame_','DB_CONFIG_CHONG');
		if($_SESSION['user']!='7477_cps'){						
			$mid = $medium_access->where("username='{$_SESSION['user']}'")->field('mid')->find();
			$map['id'] =array('in',$mid['mid']);
		}
		$map['status']=0;
		$medium_list = $medium->where($map)->select();
		return $medium_list;
		
	}
	//递归栏目
	private function unlimitedForlayer($cate,$name = 'child', $pid = 0){
		$arr = array();
		foreach ($cate as $v){			
			if($v['pid'] == $pid){
				$v[$name] = self::unlimitedForlayer($cate, $name, $v['id']);
				$arr[] = $v;
			}
		}	
		return $arr;
	}
}