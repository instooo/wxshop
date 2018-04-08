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
		
	}
	/*添加订单
	* 接收参数 goodid,goodsizeid,num,addressid
	* userid自动获取
	*《额外的选项可能有：point , card 等等》
	*/
	public function add_order_do(){
		
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
	
	/*订单提交支付
	* 接收参数 orderid
	* 自动获取 userid
	*/
	public function order_shouhuo(){
		
	}
	
}