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