<?php
/**
**用户登录控制器
**dengxiaolong*
*/

namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
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
		$list = Db::name('role')->where('status',1)->paginate(10,200);	
        $this->assign('list',$list);	
		return $this->fetch();				
    }
	//节点添加
    public function role_add()
    {		
		$ret = array('code'=>-1,'msg'=>'');
        do{
            if (!Request::instance()->isPost()) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $data = array();
            $data['name'] = input('post.name');
            $data['status'] =input('post.status');
            if (!$data['name']) {
                $ret['code'] = -2;
                $ret['msg'] = '参数不全';
                break;
            }

            $l = Db::name('role')->where(array('name'=>$data['name']))->find();
            if ($l) {
                $ret['code'] = -3;
                $ret['msg'] = '角色已存在';
                break;
            }                  
            $rs = Db::name('role')->insert($data);
            if (!$rs) {
                $ret['code'] = -4;
                $ret['msg'] = '添加失败';
                break;
            }
            $ret['code'] = 1;
            $ret['msg'] = '添加成功';
            break;
        }while(0);
        exit(json_encode($ret));		
    }

	//节点删除
	public function role_del(){
		$ret = array('code'=>-1,'msg'=>'');
        do{
            if (!Request::instance()->isPost()) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $id = input('post.id');
            if (!is_numeric($id)) {
                $ret['code'] = -2;
                $ret['msg'] = '参数错误';
                break;
            }
            $rs = Db::name('role')->where('id',$id)->delete();
            if (!$rs) {
                $ret['code'] = -3;
                $ret['msg'] = '删除失败';
                break;
            }
            $ret['code'] = 1;
            $ret['msg'] = '删除成功';
            break;
        }while(0);
        exit(json_encode($ret));
	}
}
