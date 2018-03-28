<?php

include "lib/ContentInterface.php";
include "lib/Content.class.php";
// 没有声明命名空间
class Goodstype extends Content implements ContentInterface 
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
		* 3：默认数据和多选数据结合
		*/
		$fields[]=array('name',"栏目名称",'input');
		$fields[]=array('pic1',"缩略图",'one_file');
		$other['many_data'][]=array("1","开启");
		$other['many_data'][]=array("0","关闭");
		$fields[]=array('sort',"排序",'input');		
		$fields[]=array('status',"状态",'select',$other);
		
		
		//$fields[]=array('duo_file',"广告名称",'duo_file');
		//
		//$fields[]=array('saa',"产品详情",'text');	
		//$fields[]=array('start',"时间",'date');			
		//$other['default']=array("","默认");
        //
		//$fields[]=array('radio',"单选",'radio',$other);			
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