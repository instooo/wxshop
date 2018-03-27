<?php
//定义超级父类
class Super{
	var $fields;
	public function __construct($fields){
		$this->fields = $fields;
	}
	//根据类型获得不同类型的前端代码
	public function get_html(){
		$html = "";		
		return $html;
	}
}