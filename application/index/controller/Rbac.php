<?php
/**
**用户登录控制器
**dengxiaolong*
*/

namespace app\index\controller;
use think\Controller;
use think\Db;

class Rbac extends \think\Controller
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
		$list = Db::table('think_role')->where('status',1)->paginate(10,200);	
        $this->assign('list',$list);	
		return $this->fetch();				
    }
	//节点添加
    public function role_add()
    {		
		$list = Db::table('think_role')->where('status',1)->paginate(10,200);	
        $this->assign('list',$list);	
		return $this->fetch();				
    }
}
