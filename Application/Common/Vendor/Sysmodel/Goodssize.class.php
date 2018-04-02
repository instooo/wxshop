<?php
require_once "lib/ContentInterface.php";
require_once "lib/Content.class.php";
// 没有声明命名空间
class Goodssize extends Content implements ContentInterface 
{	
	protected $_validate = array(
		array('sizename','require','不能为空'), //默认情况下用正则进行验证	
		array('price','number','价格必须为数字'), //默认情况下用正则进行验证	
		array('kucun','number','价格必须为数字'), //默认情况下用正则进行验证
		array('goods_id','require','物品名称'), //默认情况下用正则进行验证			
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
		$fields[]=array('sizename',"规格名称",'input');
		$fields[]=array('price',"价格",'input');
		$fields[]=array('kucun',"库存",'input');	
		$fields[]=array('goods_id',"物品名称",'input');	
		return $fields;		
	}
	//获取html
	function get_html($ext){		
		$common_fields =$this->getFields($ext);			
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
	//数据过滤
	public function filter($data){
		$common_fields =$this->getFields();
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