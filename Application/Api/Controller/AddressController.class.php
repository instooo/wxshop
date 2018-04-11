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

class AddressController extends ApiController
{	
	/*地址列表
	* 接受用户ID，自动筛选大于15天的地址
	*/
	public function address_list(){
		$map['member_id'] = 666;
		$data['addresslist']=M('address')->where($map)->select();
		Response::apiReturn(0,"success",$data);
	}
	
	/*地址详情页面
	* 接收参数 addressid
	* 自动获取 userid
	*/
	public function address_detail(){
		$map['id']=$_POST['id'];
		$map['member_id']=666;
		$result['addressinfo'] = M('address')->where($map)->find();
		if($result['addressinfo']){
			Response::apiReturn(0,"success",$result);
		}else{
			Response::apiReturn(1,"fail");
		}
		
	}
	/*提交地址确认页面
	* 接收参数 省,州,市,县
	*/
	public function add_address(){
		$data = $_POST;
		if($data['isdefault']==1){
			$map['member_id']=666;
			$aa = M('address')->where($map)->setField('isdefault',0);			
		}
		$data['member_id']=666;
		$data['addtime']=time();		
		$result = M('address')->add($data);
		if($result){
			$redata['id']=$result;
			Response::apiReturn(0,"success",$redata);
		}else{
			Response::apiReturn(1,"fail");
		}	
	}
	
	/*地址修改
	* 接收参数 addressid
	* 自动获取 userid
	*/
	public function address_edit(){
		$data = $_POST;
		if($data['isdefault']==1){
			$map['member_id']=666;
			$aa = M('address')->where($map)->setField('isdefault',0);			
		}
		$map['id']=$_POST['id'];
		$map['member_id']=666;
		$result = M('address')->where($map)->save($data);
		if($result){			
			Response::apiReturn(0,"success",$result);
		}else{
			Response::apiReturn(1,"fail");
		}	
	}
	
	/*地址删除
	* 接收参数 addressid
	* 自动获取 userid
	*/
	public function address_del(){
		$map['id']=$_POST['id'];
		$map['member_id']=666;
		$result['addressList'] = M('address')->where($map)->delete();
		if($result){			
			Response::apiReturn(0,"success",$result);
		}else{
			Response::apiReturn(1,"fail");
		}	
	}
	
	
	/**
	* 获取省份
	**/
	public function getprovince(){
		$map['level'] = 1;
		$data['provincelist']=M('area')->where($map)->select();
		Response::apiReturn(0,"success",$data);
	}
	/**
	* 获取省份
	**/
	public function getarea(){		
		$map['parentId'] = I("get.code","","intval");
		$data['arealist']=M('area')->where($map)->select();
		Response::apiReturn(0,"success",$data);
	}
	
}