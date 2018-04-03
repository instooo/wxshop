<?php

require_once "lib/ContentInterface.php";
require_once "lib/Content.class.php";
// 没有声明命名空间
class Goods extends Content implements ContentInterface 
{	
	protected $_validate = array(
		array('good_type_id','require','栏目不能为空'), //默认情况下用正则进行验证	
		array('good_type_id','number','栏目非法参数'), //默认情况下用正则进行验证		
		array('goods_name','require','商品名称不能为空'), //默认情况下用正则进行验证	
		array('price','require','价格范围不能为空'), //默认情况下用正则进行验证	
		array('thumbs','require','缩略图不能为空'), //默认情况下用正则进行验证	
		array('sort','number','排序必须为数字'), //默认情况下用正则进行验证	
		array('status','require','状态不能为空'), //默认情况下用正则进行验证	
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
		$cate['many_data'] = $ext["goodstype"];
		$label['many_data'] = $ext["goodslabel"];		
		$fields[]=array('good_type_id','栏目名称','select',$cate);
		$fields[]=array('label_id','标签','select',$label);
		$fields[]=array('goods_name',"商品名称",'input');
		$fields[]=array('price',"商品价格范围",'input');
		$fields[]=array('thumb',"缩略图",'one_file');
		$fields[]=array('thumbs',"多个产品图片",'duo_file');
		$fields[]=array('description',"产品详情",'editor');
		$other['many_data'][]=array("1","上架");
		$other['many_data'][]=array("0","下架");		
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