<?php
include "../lib/ContentInterface.php";
include "../lib/Content.class.php";
// 没有声明命名空间
class Ad extends Super implements SuperModuleInterface 
{	
	protected $_validate = array(
		array('title','require','不能为空'), //默认情况下用正则进行验证	
	);
    //获取模型字段和类型
    function getFields(){
		$common_fields = parent::getFields();
		$fields[]=array('title',"广告名称",'input','',1);
		$fields[]=array('weight',"排序",'input',0,1);
		$fields[]=array('url',"广告链接",'input','',1);
		$fields[]=array('img_duo',"广告图片",'file',10);			
		$common_fields = array_merge($common_fields,$fields);
		return $common_fields;		
	}
	//获取html
	 function get_html(){
		$common_fields =$this->getFields();		
		$html = parent::get_html($common_fields);
		return $html;
	}
	
	//编辑html	
	function edit_html($info){		
		$common_fields =$this->getFields();		
		$html = parent::edit_html($common_fields,$info);	
		return $html;
	}
	
	public function checkData($data){
		$_validate =$this->_validate;
		$common_fields =$this->getFields();			
		$result = parent::checkData($data,$_validate,$common_fields);			
		return $result;
	}
}
?>