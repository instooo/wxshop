<?php
/**
**用户登录控制器
**dengxiaolong*
*/

namespace app\index\controller;
use think\Controller;

class Rbac
{
	//节点管理
    public function node()
    {
		return view('rbac/node');
    }
	//用户管理
    public function user()
    {
		return view('rbac/user');
    }
	//节点管理
    public function role()
    {
		return view('rbac/role');
    }
}
