<?php

include "lib/ContentInterface.php";
include "lib/Content.class.php";
// 没有声明命名空间
class Goodslabel extends Content implements ContentInterface 
{	
	//需要验证的字段
	protected $_validate = array(
		array('name','require','栏目名称不能为空'), //默认情况下用正则进行验证	
		array('pic1','require','缩略图不能为空'), //默认情况下用正则进行验证		
		array('sort','number','排序必须为数字'), //默认情况下用正则进行验证
		array('status','number','状态必须为数字'), //默认情况下用正则进行验证		
	);
	//获取显示的字段
	function getShowFields(){
		$other[1]="开启";
		$other[0]="关闭";
		$showfields['status']=$other;
		return $showfields;		
		
	}
    //获取模型字段和类型
    function getFields(){	
		/*
		* 0:字段名称
		* 1：文字描述
		* 2：类型（input|file|text|editor)
		* 3：默认数据和多选数据结合
		*/
		$fields[]=array('name',"标签名称",'input');
		$fields[]=array('pic1',"缩略图",'one_file');
		$other['many_data'][]=array("1","开启");
		$other['many_data'][]=array("0","关闭");
		$fields[]=array('sort',"排序",'input');		
		$fields[]=array('status',"状态",'select',$other);	
		
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
	//检测数据
	public function checkData($data){
		$_validate =$this->_validate;
		$fields =$this->getFields();			
		$result = parent::checkData($data,$_validate,$fields);			
		return $result;
	}
}
?>