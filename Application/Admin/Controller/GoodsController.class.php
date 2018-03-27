<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class GoodsController extends CommonController {
	//商品列表
	public function index(){		
		$this->display();
	}
	//添加商品
	public function goodadd(){
		//查找模型对应的表格和对应的类名
		$classname =ucfirst(strtolower('ad'));
		import('Common/Vendor/Sysmodel/'.$classname);		
        $class    = new $classname();
		//这些案例所有都有对应栏目，所以是公用的		
		$html = $class->get_html();			
		$this->assign('html',$html);	
		$this->display();
	}
	//编辑商品
	public function goodedit(){
		$this->display();
	}
	//删除商品
	public function gooddelete(){
		$this->display();
	}
}