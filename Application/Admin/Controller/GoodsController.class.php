<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
//$request = \Admin\Logic\Common\Request::filter($_REQUEST);
//$paylist = new \Admin\Logic\Pay\Pay($request,0);		
	
class GoodsController extends CommonController {
	//商品列表
	public function index(){
		$this->data_list("goods");		
	}
	//添加商品
	public function goodadd(){		
		$html = $this->data_get_html("goods");//获取商品常规参数
		//获取商品栏目
		$module = new \Admin\Logic\Goods\GoodsData("goodstype");
		$data = $module->get_all_list();
		print_r($data);die;
		//获取商品标签
		//获取实体店
		
		$this->assign('html',$html);		
		$this->display();
		
	}
	//编辑商品
	public function goodedit(){
		$this->data_edit("goods");
	}
	//删除商品
	public function gooddelete(){
		$this->data_delete("goods");		
	}
	
	//商品类型列表
	public function goods_type_list(){	
		$this->data_list("goodstype");			
	}
	//商品类型添加
	public function goods_type_add(){
		$this->data_add("goodstype");			
	}
	//商品类型编辑
	public function goods_type_edit(){		
		$this->data_edit("goodstype");		
	}
	//商品类型删除
	public function goods_type_delete(){		
		$this->data_delete("goodstype");
	}
	
	//商品标签列表
	public function goods_label_list(){	
		$this->data_list("goodslabel");		
	}
	//商品标签添加
	public function goods_label_add(){		
		$this->data_add("goodslabel");		
	}
	//商品标签编辑
	public function goods_label_edit(){		
		$this->data_edit("goodslabel");		
	}
	//商品标签删除
	public function goods_label_delete(){		
		$this->data_delete("goodslabel");		
	}	
	
}