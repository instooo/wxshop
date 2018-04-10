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

class RentController extends ApiController
{	
	/*地址列表
	* 接受用户ID，自动筛选大于15天的地址
	*/
	public function rent_list(){
		$data["rooturl"]="http://www.wxshop.me";
		$module=new \Api\Logic\Goods\Rent();		
		$map = $_GET;
		$map["userid"]="666";			
		$data["rent_list"]=$module->rent_list($map);		
		$data["flag"]=count($data["rent_list"])>0?true:false;		
		Response::apiReturn(0,"success",$data);		
	}
	
	/*地址详情页面
	* 接收参数 rentid
	* 自动获取 userid
	*/
	public function rent_detail(){
		
	}
	/*
	* 添加订单
	*/
	public function add_rent(){
		$module=new \Api\Logic\Goods\Rent();		
		$data = $_GET;
		$data["userid"]="666";
		$data["goodid"]=$data["goods_id"];
		$data["goodsizeid"]=$data["goods_size_id"];
		$data["num"]=$data["num"];
		$result = $module->rent_add($data);
		exit(json_encode($result));
	}	
	
	/*地址删除
	* 接收参数 rentid
	* 自动获取 userid
	*/
	public function rent_del(){
		$id=I("post.id","","intval");
		$module=new \Api\Logic\Goods\Rent();
		$data['id']=$id;
		$data['userid']=666;
		$result = $module->rent_del($data);
		exit(json_encode($result));
	}
	
	/*地址修改
	* 接收参数 rentid
	* 自动获取 userid
	*/
	public function rent_edit(){
		$id=I("get.id","","intval");
		$module=new \Api\Logic\Goods\Rent();
		$data['num']=$_GET['num'];
		$data['id']=$id;
		$result = $module->rent_edit($data);
		exit(json_encode($result));
	}
	
	
}