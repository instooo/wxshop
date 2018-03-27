<?php

include "lib/ContentInterface.php";
include "lib/Content.class.php";
// 没有声明命名空间
class Ad extends Content implements ContentInterface 
{	
	protected $_validate = array(
		array('title','require','不能为空'), //默认情况下用正则进行验证	
	);
    //获取模型字段和类型
    function getFields(){	
		/*
		* 0:字段名称
		* 1：文字描述
		* 2：类型（input|file|text|editor)
		*
		*/
		$fields[]=array('title',"广告名称",'input','',1);
		$fields[]=array('img_duo',"产品详情",'editor',10);
		return $fields;		
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
}
?>