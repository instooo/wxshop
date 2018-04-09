<?php
/**
 * Created by Notepad++.
 * User: dengxiaolong
 * Date: 2018/04/09
 * Time: 09:48
 */

namespace Api\Logic\Goods;
class Goods {
	//获得首页数据列表
	public function get_index_list($limit){		
		//查找对应的栏目id		
		$content = M("goods");
		$contentmap['status']=1;		
		$list = $content
				->where($contentmap)
				->order("sort desc,id desc")
				->limit($limit)
				->select();
		return $list;
	}
	//获得单条数据详情
	public function get_detail($id){		
		//查找对应的栏目id		
		$content = M("goods");
		$contentmap['id']=$id;		
		$info = $content				
				->where($contentmap)				
				->find();
		$info["thumblist"] = explode("|",trim($info['thumbs'],"|"));
		return $info;
	}
	//获得单条数据详情
	public function get_size_detail($id){		
		//查找对应的栏目id		
		$content = M("goodssize");
		$contentmap['goods_id']=$id;		
		$info = $content				
				->where($contentmap)				
				->select();		
		return $info;
	}

}