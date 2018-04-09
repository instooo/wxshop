<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class SystemController extends CommonController {	
	
	//广告列表
	public function ad_list(){	
		//获取商品栏目
		$module = new \Admin\Logic\Goods\GoodsData("ad_type");
		$ext["ext"]['adtypeid'] = $module->get_all_list();	
		$ext["field"]["adtypeid"]=array("id","name");			
		$this->data_list("ad",$ext);			
	}
	//广告添加
	public function ad_add(){
		//获取商品栏目
		$module = new \Admin\Logic\Goods\GoodsData("ad_type");
		$ext['adtypeid'] = $module->get_all_list();		
		$this->data_add("ad",$ext);			
	}
	//广告编辑
	public function ad_edit(){	
		$module = new \Admin\Logic\Goods\GoodsData("ad_type");
		$ext['adtypeid'] = $module->get_all_list();		
		$this->data_edit("ad",$ext);		
	}
	//广告删除
	public function ad_delete(){		
		$this->data_delete("ad");
	}
	
	//广告类型列表
	public function ad_type_list(){	
		$this->data_list("ad_type");		
	}
	//广告类型添加
	public function ad_type_add(){		
		$this->data_add("ad_type");		
	}
	//广告类型编辑
	public function ad_type_edit(){		
		$this->data_edit("ad_type");		
	}
	//广告类型删除
	public function ad_type_delete(){		
		$this->data_delete("ad_type");		
	}	
	
}