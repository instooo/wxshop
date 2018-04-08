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

class UserController extends ApiController
{	
	//根据程序生成的code,获得用户登录信息
	public function mwxgettoken(){
		$code = $_GET['_code'];
		//根据code,获得用户唯一openid
		$nickname = $_GET['nickname'];
		$headimgurl = $_GET['headimgurl'];
		$sex = $_GET['sex'];
		$province = $_GET['province'];
		$city = $_GET['city'];
		$country = $_GET['country'];
		//用户没注册，则注册信息，用户注册后，则直接返回token,()
	}
	
	//获取用户信息
	public function muserinfo(){
		
	}
	
	//获取用户积分列表
	public function getpointlist(){
		
	}
	
	//获取用户推荐用户列表
	public function getmyuser(){
		
	}
}