<?php
/**
 * Created by Notepad++.
 * User: dengxiaolong
 * Date: 2018/04/09
 * Time: 11:08
 */

namespace Api\Logic\Common;
class TableData {	
     //默认查询7天时间的数据
	var $table;
	
	public  function __construct($table){		
		$this->table = $table;
	}
    public function TableData($table) {
    	$this->__construct($table);
    }	
	//所有数据列表
	public function get_all_list($limit=10,$map=array()){
		$classname =ucfirst(strtolower($this->table));		
		//查找对应的栏目id		
		$content = M($classname);
		$contentmap=$map;
		$list = $content
				->where($contentmap)
				->order("sort desc,id desc")
				->limit($limit)
				->select();
		return $list;
	}

}