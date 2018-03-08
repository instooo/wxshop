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