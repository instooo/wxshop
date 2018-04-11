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
		
	}
	
	/*订单详情页面
	* 接收参数 orderid
	* 自动获取 userid
	*/
	public function order_detail(){
		
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
		
		if($rentid ){
			$rentmap['c.id']=array('in',explode(",",$rentid));
			$rentmap['userid']=666;
			$redata['goodlist']== M("rent_good c")
						->join(C("DB_PREFIX")."goods a on a.id=c.goodid")
						->join(C("DB_PREFIX")."goodssize b on c.goodsizeid=b.goods_id")
						->where($rentmap)
						->select();
			print_r($redata);			
		}else if($goodsizeid){
			$goodmap['b.id']=$goodsizeid;
			$redata['goodlist']=M("goods a")
						->join(C("DB_PREFIX")."goodssize b on a.id=b.goods_id")
						->where($goodmap)->select();
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
		//添加进入数据，然后生成订单号
		//[token] => 
		//[numbers] => 1
		//[goodsizeids] => 2
		//[addressid] => 157
		//[rentid] => 157
		//[point] => 0
		//[remarks] =>
		//查找物品规格是否存在		
		$ret = array("code"=>-1,"msg"=>'',"data"=>"");
		do{ 
			
			if($data['goodsizeids']){
				$sizemap['a.id']=$data['goodsizeids'];
				$result = M('goodssize a')
				->join(C("DB_PREFIX")."goods b on b.id=a.goods_id")
				->where($sizemap)->find();				
				if(!$result){
					$ret['code'] = 1;
					$ret['msg'] = '物品规格不存在';					
					break;
				}
				$admap['id']=$data['addressid'];
				$admap['member_id']=666;
				$adresult= M("address")->where($admap)->find();
				if(!$adresult){
					$ret['code'] = 1;
					$ret['msg'] = '地址不存在';					
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
				$result = M("order")->add($good_data);
				if($result){
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
					Response::apiReturn(0,"success",$result);	
				}
				
			}else if($data['rentid']){//租用列表过来
				print_r(1);
			}	
		}while(0);			
		return $ret;
		
		
		
	}	
	
	/*订单取消
	* 接收参数 orderid
	* 自动获取 userid
	*/
	public function order_cancel(){
		
	}
	
	/*订单确认收货
	* 接收参数 orderid
	* 自动获取 userid
	*/
	public function order_shouhuo(){
		
	}
	
	/**
	 *
	 * @name 订单生成
	 * @return string
	 */
	public function create_order() {
		$str = date ( 'Ymd' ) . \Org\Util\String::randString(1, 5) . date ( 'His' ) . \Org\Util\String::randString(1, 5);
		return $str;
	}
}