<?php
/**
 * Created by Notepad++.
 * User: dengxiaolong
 * Date: 2018/04/09
 * Time: 09:48
 */

namespace Api\Logic\Goods;

class Rent {	
	public function rent_add($data){
		$ret = array("code"=>-1,"msg"=>'',"data"=>"");
		do{ 
			$result = $this->checkData($data);//检查数据
			if($result['code']!=1){
				$ret = $result;
				break;
			}			
			$st = M("rent_good")->data($result['data'])->add();
			if(!$st){
				$ret['code'] = 1;
				$ret['msg'] = '添加失败';
				$ret['data'] = $st;
				break;
			}
			$ret['code'] = 0;
			$ret['msg'] = '添加成功';
			$ret['data'] = $st;
		}while(0);			
		return $ret;
	}
	public function rent_list($data){	
		$map['userid']=$data['userid'];
		$st = M("rent_good a")
		->field("a.*,b.thumb,b.goods_name,c.*")
		->join(C("DB_PREFIX")."goods b on b.id = a.goodid")
		->join(C("DB_PREFIX")."goodssize c on c.id = a.goodsizeid")
		->where($map)
		->select();	
		return $st;
	}
	//购物车的数量
	public function get_rent_counts($data){
		//查找对应的栏目id		
		$content = M("rent_good");
		$contentmap['userid']=$data['userid'];	
		$contentmap['goodid']=$data['goodid'];	
		$count = $content
				->where($contentmap)
				->count();	
		return $count;
	}
	
	private function checkData($data){
		$result["code"]=1;
		$data['addtime']=time();
		$result["data"]=$data;
		return $result;
	}
}