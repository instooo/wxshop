<?php
/**
 * Created by Dengxiaolong.
 * User: Administrator
 * Date: 2018/04/08
 * Time: 15:03
 */

namespace Api\Controller;
use Org\Util\ApiHelper;
use Org\Util\Response;

class OrderController extends ApiController
{	
	/*订单列表
	* 接受用户ID，自动筛选大于15天的订单
	*/
	public function order_list(){		
		$map['status']=$_REQUEST['status'];	
		$map['page']=$_REQUEST['page'];
		$map['userid']=666;
		$module=new \Api\Logic\Order\Order();	
		$orderlist = $module->order_list($map);		
		$tmp=array();
		$warelist=array();
		foreach($orderlist as $key=>$val){
			$tmp[$val['orderno']]['id']=$val['id'];
			$tmp[$val['orderno']]['orderno']=$val['orderno'];
			$tmp[$val['orderno']]['total_money']=$val['total_money'];
			$tmp[$val['orderno']]['transportation_cost']=$val['transportation_cost'];
			$tmp[$val['orderno']]['status']=$val['status'];
			$tmp[$val['orderno']]['createtime']=$val['createtime'];
			$warelist['num']=$val['num'];
			$warelist['price']=$val['price'];
			$warelist['goods_name']=$val['goods_name'];
			$warelist['thumb']=$val['thumb'];
			$warelist['sizename']=$val['sizename'];	
			$warelist['sizeid']=$val['sizeid'];				
			$tmp[$val['orderno']]['warelist'][]=$warelist;
		}		
		$arr =array();
		foreach($tmp as $val){
			$arr[]=$val;
		}
		$result['orderlist']=$arr;
		$result["rooturl"]="http://www.wxshop.me";		
		Response::apiReturn(0,"success",$result);	
	}
	
	/*订单详情页面
	* 接收参数 orderid
	* 自动获取 userid
	*/
	public function order_detail(){
		$map['orderinfo']=I("get.id",'','htmlspecialchars');
		$map['userid']=666;
		$module=new \Api\Logic\Order\Order();	
		$orderlist = $module->order_info($map);			
		$result['orderinfo'] = $orderlist;
		$mapware["orderno"]=$orderlist['orderno'];		
		$result['orderinfo']['orderwarelist']=$module->order_ware_list($mapware);	
		$result["rooturl"]="http://www.wxshop.me";				
		Response::apiReturn(0,"success",$result);	
	}
	/*提交订单确认页面
	* 接收参数 goodid,goodsizeid,num,addressid
	*/
	public function add_order(){
		$data =$_POST;		
		//查询默认地址信息
		if($data['addressid']){
			$admap['id']=$data['addressid'];
			$admap['member_id']=666;
			$redata['addressinfo']= M("address")->where($admap)->find();
		}else{
			$admap['member_id']=666;
			$admap['isdefault']=1;
			$redata['addressinfo']= M("address")->where($admap)->find();
		}
		//根据规格ID查询商品信息
		$goodsizeid = I("post.goodsizeids","","htmlspecialchars");
		$rentid = trim(I("post.rentids","","htmlspecialchars"),',');
		$numbers =I("post.numbers","","intval"); 
		$goodmap['b.id']=$goodsizeid;		
		
		if($rentid){
			$rentmap['c.id']=array('in',explode(",",$rentid));
			$rentmap['c.userid']=666;
			$redata['goodlist']= M("rent_good c")
						->join(C("DB_PREFIX")."goods a on a.id=c.goodid")
						->join(C("DB_PREFIX")."goodssize b on c.goodsizeid=b.id")
						->where($rentmap)
						->select();	
			foreach($redata['goodlist'] as $key=>$val){
				$redata['totalmoney']+=$val['price']*$val['num'];
			}
			$redata['freight']=0.00;
		}else if($goodsizeid){
			$goodmap['b.id']=$goodsizeid;
			$redata['goodlist']=M("goods a")
						->join(C("DB_PREFIX")."goodssize b on a.id=b.goods_id")
						->where($goodmap)->select();
			$redata['goodlist'][0]['num']=$numbers;
			$redata['totalmoney']=$redata['goodlist'][0]['price']*$numbers;
			$redata['freight']=0.00;
			
		}
		$redata["rooturl"]="http://www.wxshop.me";		
		//给到用户信息
		$usermap['id']=666;
		$redata['userinfo'] = M("member")->where($usermap)->find();		
		//给到折扣后的信息
		//$data[]
		Response::apiReturn(0,"success",$redata);	
		
	}
	/*添加订单
	* 接收参数 goodid,goodsizeid,num,addressid
	* userid自动获取
	*《额外的选项可能有：point , card 等等》
	*/
	public function add_order_do(){
		$data =$_POST;	
		$ret = array("code"=>-1,"msg"=>'',"data"=>"");
		do{ 
			//地址为公用的
			$admap['id']=$data['addressid'];
			$admap['member_id']=666;
			$adresult= M("address")->where($admap)->find();
			if(!$adresult){
				$ret['code'] = 1;
				$ret['msg'] = '地址不存在';					
				break;
			}
			
			if($data['goodsizeids']){
				$sizemap['a.id']=$data['goodsizeids'];
				$result = M('goodssize a')
				->field("a.id,a.sizename,a.goods_id,b.shop_id,b.good_type_id,b.goods_name,b.thumb,b.label_id,b.sort,a.price")
				->join(C("DB_PREFIX")."goods b on b.id=a.goods_id")
				->where($sizemap)->find();				
				if(!$result){
					$ret['code'] = 1;
					$ret['msg'] = '物品规格不存在';					
					break;
				}				
				$good_data['total_money']=$data['numbers']*$result['price'];
				$good_data['total_ys_money']=$data['numbers']*$result['price'];
				$good_data['transportation_cost']=0.00;
				$good_data['payremarks']=$data['remarks'];
				$good_data['status']=1;
				$good_data['createtime']=time();
				$good_data['modifytime']=0;
				$good_data['paytime']=0;
				$good_data['recetime']=0;
				$good_data['userid']=666;
				$good_data['paymethod']="weixinpay";
				$good_data['ordername']=$adresult["ordername"];
				$good_data['phone']=$adresult["phone"];
				$good_data['province']=$adresult["province"];
				$good_data['city']=$adresult["city"];
				$good_data['country']=$adresult["area"];
				$good_data['detailaddress']=$adresult["detailaddress"];
				$good_data['returnremarks']="";
				$good_data['costpassword']=$data['total_money'];
				$good_data['sendexpressnumber']="";
				$good_data['outexpressnumber']="";
				$good_data['point']=$data['point'];
				$good_data['point_cost']=$data['point'];
				$good_data['prepay_id']=0;
				$good_data['discount']=0;
				$good_data['orderno']=$this->create_order();
				$adresult = M("order")->add($good_data);
				if($adresult){
					$dataware['orderno']=$good_data['orderno'];
					$dataware['goodid']=$result['goods_id'];
					$dataware['goodsizeid']=$result['id'];
					$dataware['num']=$data['numbers'];
					$dataware['price']=$result['price'];
					$dataware['zk']=1;
					$dataware['addtime']=time();
					$st = M("order_ware")->add($dataware);
				}
				if($st){
					Response::apiReturn(0,"success",$good_data['orderno']);	
				}else{
					Response::apiReturn(1,"fail");	
				}
				
			}else if($data['rentids']){//租用列表过来
				$rentids =explode(",",$data['rentids']);
				//查找rentids
				$rentmap['c.id']=array('in',$rentids);
				$rentmap['c.userid']=666;
				$rentresult['goodlist']= M("rent_good c")
							->field("c.id as rent_id,b.id,b.price,b.goods_id,c.num")
							->join(C("DB_PREFIX")."goods a on a.id=c.goodid")
							->join(C("DB_PREFIX")."goodssize b on c.goodsizeid=b.id")
							->where($rentmap)
							->select();			
				foreach($rentresult['goodlist'] as $val){
					$total_money +=$val['price']*$val['num'];
				}		
				$good_data['total_money']=$total_money;
				$good_data['total_ys_money']=$total_money;
				$good_data['transportation_cost']=0.00;
				$good_data['payremarks']=$data['remarks'];
				$good_data['status']=1;
				$good_data['createtime']=time();
				$good_data['modifytime']=0;
				$good_data['paytime']=0;
				$good_data['recetime']=0;
				$good_data['userid']=666;
				$good_data['paymethod']="weixinpay";
				$good_data['ordername']=$adresult["ordername"];
				$good_data['phone']=$adresult["phone"];
				$good_data['province']=$adresult["province"];
				$good_data['city']=$adresult["city"];
				$good_data['country']=$adresult["area"];
				$good_data['detailaddress']=$adresult["detailaddress"];
				$good_data['returnremarks']="";
				$good_data['costpassword']=$data['total_money'];
				$good_data['sendexpressnumber']="";
				$good_data['outexpressnumber']="";
				$good_data['point']=$data['point'];
				$good_data['point_cost']=$data['point'];
				$good_data['prepay_id']=0;
				$good_data['discount']=0;
				$good_data['orderno']=$this->create_order();
				$adresult = M("order")->add($good_data);				
				if($adresult){
					foreach($rentresult['goodlist'] as $key=>$val){						
						$dataware[$key]['orderno']=$good_data['orderno'];
						$dataware[$key]['goodid']=$val['goods_id'];
						$dataware[$key]['goodsizeid']=$val['id'];
						$dataware[$key]['num']=$val['num'];
						$dataware[$key]['price']=$val['price'];
						$dataware[$key]['zk']=1;
						$dataware[$key]['addtime']=time();
					}	
					$st = M("order_ware")->addAll($dataware);
				}
				if($st){
					//删除购物车
					
					$module=new \Api\Logic\Goods\Rent();
					$mapdata['id']=$rentids;
					$mapdata['userid']=666;
					$module->rent_del($mapdata);
					
					Response::apiReturn(0,"success",$good_data['orderno']);	
				}else{
					Response::apiReturn(1,"fail");	
				}				
			}	
		}while(0);			
		return $ret;
		
		
		
	}	
	
	/*订单取消
	* 接收参数 orderid
	* 自动获取 userid
	*/
	public function order_cancel(){
		$map['id']=I("post.id",'','htmlspecialchars');
		$map['userid']=666;
		$info = M("order")->where($map)->find();
		$result = M("order")->where($map)->delete();
		if($result){
			$mapa["orderno"] = $info["orderno"];
			$resulta = M("order_ware")->where($mapa)->delete();
			Response::apiReturn(0,"success",$resulta);	
		}else{
			Response::apiReturn(1,"fail");	
		}
		
	}
	
	/*订单确认收货
	* 接收参数 orderid
	* 自动获取 userid
	*/
	public function order_shouhuo(){
		$map['id']=I("post.id",'','htmlspecialchars');
		$map['userid']=666;
		$result = M("order")->where($map)->setField("status",4);
		if($result){
			Response::apiReturn(0,"收货成功",$result);	
		}else{
			Response::apiReturn(1,"fail");	
		}
	}
	
	/**
	 *
	 * @name 订单生成
	 * @return string
	 */
	public function create_order() {
		$str = date ( 'Ymd' ) . \Org\Util\String::randString(3, 2) . date ( 'His' ) . \Org\Util\String::randString(2, 2);		
		return $str;
	}
	
}