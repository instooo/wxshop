<?php
/**
 * Created by Notepad++.
 * User: dengxiaolong
 * Date: 2018/04/09
 * Time: 09:48
 */

namespace Api\Logic\Order;

class Order {	
	public function rent_add($data){
		$ret = array("code"=>-1,"msg"=>'',"data"=>"");
		do{ 
			
		}while(0);			
		return $ret;
	}
	
	public function rent_edit($data){
		$ret = array("code"=>-1,"msg"=>'',"data"=>"");
		do{ 		
			
		}while(0);			
		return $ret;
	}
	
	
	public function rent_del($data){
		$ret = array("code"=>-1,"msg"=>'',"data"=>"");
		do{ 	
			
		}while(0);			
		return $ret;
	}
	
	
	public function order_list($data){	
		$pagestart = ($data['page']-1)*10;
		$offset =10;
		$map['a.userid']=$data['userid'];
		$map['a.status']=$data['status'];
		$st = M("order a")		
		->join(C("DB_PREFIX")."order_ware b on a.orderno = b.orderno")
		->join(C("DB_PREFIX")."goods c on b.goodid = c.id")
		->join(C("DB_PREFIX")."goodssize d on b.goodsizeid = d.id")
		->where($map)
		->limit($pagestart,$offset)
		->select();	
		print_R()
		return $st;
	}
}