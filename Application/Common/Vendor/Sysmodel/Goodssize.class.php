<?php
include "lib/ContentInterface.php";
include "lib/Content.class.php";
// 没有声明命名空间
class Goodssize extends Content implements ContentInterface 
{	
	protected $_validate = array(
		array('title','require','不能为空'), //默认情况下用正则进行验证	
		array('price','number','价格必须为数字'), //默认情况下用正则进行验证	
		array('kuncun','number','价格必须为数字'), //默认情况下用正则进行验证	
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
		$fields[]=array('sizename',"规格名称",'input');
		$fields[]=array('price',"价格",'input');
		$fields[]=array('kuncun',"库存",'input');		
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