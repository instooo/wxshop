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
		//查找不同标签下的数据
		$result = M("goods")->group("label_id")->select();	
		$data["rooturl"]="http://www.wxshop.me";
		$data["good_list"]=$result;
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
		/*
		*接收参数
		*产品详情ID
		*/		
	}
}