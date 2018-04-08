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
		
	}
	
	/*地址详情页面
	* 接收参数 rentid
	* 自动获取 userid
	*/
	public function rent_detail(){
		
	}
	/*提交地址确认页面
	* 接收参数 省,州,市,县
	*/
	public function add_rent(){
		
	}	
	
	/*地址删除
	* 接收参数 rentid
	* 自动获取 userid
	*/
	public function rent_del(){
		
	}
	
	/*地址修改
	* 接收参数 rentid
	* 自动获取 userid
	*/
	public function rent_edit(){
		
	}
	
}