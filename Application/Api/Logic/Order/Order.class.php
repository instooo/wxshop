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
		if($data['status']){
			if($data['status']==2){				
				$map['a.status']=array("in",array(2,3,5));
			}else{
				$map['a.status']=$data['status'];
			}
			
		}
		$st = M("order a")		
		->field("a.id,a.orderno,a.total_money,a.transportation_cost,a.status,a.createtime,b.num,b.price,c.goods_name,c.thumb,d.sizename,d.id as sizeid")
		->join(C("DB_PREFIX")."order_ware b on a.orderno = b.orderno")
		->join(C("DB_PREFIX")."goods c on b.goodid = c.id")
		->join(C("DB_PREFIX")."goodssize d on b.goodsizeid = d.id")
		->where($map)
		->limit($pagestart,$offset)
		->select();			
		return $st;
	}
	public function order_info($data){	
		$map['orderno']=$data['orderinfo'];
		$map['userid']=$data['userid'];	
		$result = M("order")->where($map)->find();		
		return $result;
	}
	public function order_ware_list($data){
		$map['orderno']=$data['orderno'];	
		$result = M("order_ware b")
		->field("b.num,b.price,b.goodid,c.goods_name,c.thumb,d.sizename,d.id as sizeid")
		->join(C("DB_PREFIX")."goods c on b.goodid = c.id")
		->join(C("DB_PREFIX")."goodssize d on b.goodsizeid = d.id")
		->where($map)
		->select();		
		return $result;
	}
}