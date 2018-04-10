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
		
	}
	/*提交地址确认页面
	* 接收参数 省,州,市,县
	*/
	public function add_address(){
		
	}	
	
	/*地址删除
	* 接收参数 addressid
	* 自动获取 userid
	*/
	public function address_del(){
		
	}
	
	/*地址修改
	* 接收参数 addressid
	* 自动获取 userid
	*/
	public function address_edit(){
		
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