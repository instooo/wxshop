<?php

require_once "lib/ContentInterface.php";
require_once "lib/Content.class.php";
// 没有声明命名空间
class Ad extends Content implements ContentInterface 
{	
	protected $_validate = array(
		array('name','require','栏目名称不能为空'), //默认情况下用正则进行验证	
		array('pic','require','缩略图不能为空'), //默认情况下用正则进行验证		
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
    function getFields($ext){	
		/*
		* 0:字段名称
		* 1：文字描述
		* 2：类型（input|file|text|editor)
		* 3：默认数据和多选数据结合
		*/
		$adtypeid['many_data']= $ext['adtypeid'];
		$fields[]=array('adtypeid',"广告类型",'select',$adtypeid);
		$fields[]=array('name',"广告标题",'input');
		$fields[]=array('url',"链接地址",'input');
		$fields[]=array('pic',"缩略图",'one_file');
		$other['many_data'][]=array("1","开启");
		$other['many_data'][]=array("0","关闭");
		$fields[]=array('sort',"排序",'input');		
		$fields[]=array('status',"状态",'select',$other);
		return $fields;		
	}
	//获取html
	function get_html($ext){	
		$common_fields =$this->getFields($ext);			
		$html = parent::get_html($common_fields);
		return $html;
	}	
	//编辑html	
	function edit_html($info,$ext){		
		$common_fields =$this->getFields($ext);		
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
	//数据过滤
	public function filter($data){
		$common_fields =$this->getFields();
		$common_fields[]=array('id',"主键",'input');	
		foreach($common_fields as $key=>$val){
			$keyarr[]=$val[0];
		}
		$para_filter = array();
		while (list ($key, $val) = each ($data)) {
			if(!in_array($key,$keyarr)){
					continue;
			}else{
				$para_filter[$key] = $data[$key];
			}		
		}		
		return $para_filter;		
	}
}
?>