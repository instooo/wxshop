<?php
/**
 * Created by PhpStorm.
 * User: dengxiaolong
 * Date: 2017/4/8
 * Time: 15:17
 */
namespace Api\Controller;
use Org\Util\ApiHelper;
use Org\Util\Response;

class IndexController extends ApiController
{	
	//首页
    public function index(){
		$data["rooturl"]="http://www.wxshop.me";
		//查找不同标签下的数据
		$module = new \Api\Logic\Common\TableData("goods");		
		$data["good_list"]=$module->get_all_list(10);		
		$module2 = new \Api\Logic\Common\TableData("ad");	
		$data["banner_list"]=$module2->get_all_list(10);			
		Response::apiReturn(0,"success",$data);
    }
	//产品列表页
	public function goodslist(){
		/*		
		* 栏目ID
		* PAGE=默认为1
		* 返回数据包含产品列表，分类列表
		*/		
	}
	//产品详情页
	public function goodsDetail(){		
		$id=I("get.id","","intval");
		$module = new \Api\Logic\Goods\Goods();	
		$data['goodsinfo']	=$module->get_detail($id);
		$data['goodssize']	=$module->get_size_detail($id);
		//查找购物车的数量
		$module2 = new \Api\Logic\Goods\Rent();	
		$tmp["userid"]=666;
		$tmp['goodid']=$id;
		$data['rentcount'] = $module2->get_rent_counts($tmp);		
		$data["rooturl"]="http://www.wxshop.me";	
		Response::apiReturn(0,"success",$data);		
	}
}