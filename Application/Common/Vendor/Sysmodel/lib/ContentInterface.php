<?php
//定义接口
interface ContentInterface{
	//获取模型字段和类型
    function getFields();	
	
	//获取初始化html
	function get_html();
	
	//编辑html	
	function edit_html($info);
}