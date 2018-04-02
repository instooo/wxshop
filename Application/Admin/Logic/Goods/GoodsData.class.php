<?php
/**
 * Created by Notepad++.
 * User: dengxiaolong
 * Date: 2018/03/29
 * Time: 11:08
 */

namespace Admin\Logic\Goods;
class GoodsData {	
     //默认查询7天时间的数据
	var $table;
	
	public  function __construct($table){		
		$this->table = $table;
	}
    public function Module($table) {
    	$this->__construct($table);
    }	
	//所有数据列表
	public function get_all_list(){
		$classname =ucfirst(strtolower($this->table));		
		//查找对应的栏目id		
		$content = M($classname);
		$contentmap=array();
		$list = $content
				->where($contentmap)
				->select();
		return $list;
	}

}