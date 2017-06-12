<?php
/**
**用户登录控制器
**dengxiaolong*
*/

namespace app\index\controller;
use think\Controller;

class Register
{
	//登录页
    public function login()
    {
		return view('public/login');
    }
	
	//注册页
	public function register(){
		return view('public/register');
	}

}
