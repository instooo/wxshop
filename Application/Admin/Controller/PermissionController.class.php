<?php
/**
 * 权限控制
 * Created by qinfan qf19910623@gmail.com.
 * Date: 2016/11/2
 */
namespace Admin\Controller;
use Org\Util\Rbac;
use Think\Controller;

class PermissionController extends CommonController {

    public function _initialize() {
        parent::_initialize();
    }

    //节点列表
    public function nodeList() {
        $node_tree = D('Node')->getNodeTree();
        $this->assign('node_tree',$node_tree);
        $this->display();
    }

    /**
     * 增加节点
     * */
    public function addNode() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $data = array();
            $data['pid']		=	I('post.pid');
            $data['name']	=	I('post.name');
            $data['title']	=	I('post.title');
            $data['sort']	=	I('post.sort',0,'intval');
            $data['ismenu']		=	I('post.ismenu',1,'intval');
            $data['status']		=	1;
            if (!is_numeric($data['pid']) || !$data['name'] || !$data['title']) {
                $ret['code'] = -2;
                $ret['msg'] = '参数不全';
                break;
            }
            //查询父菜单
            $model = M('node','mygame_','DB_CONFIG_ZHU');
            $pinfo = $model->where('id='.$data['pid'])->find();
            $data['level'] = $pinfo?$pinfo['level']+1:0;
            if ($data['level'] >= 3) {
                $ret['code'] = -3;
                $ret['msg'] = '最多只支持3级菜单';
                break;
            }
            $res = $model->add($data);
            if (!$res) {
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

    /**
     * 编辑节点
     * */
    public function updateNode() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $id = I('post.id');
            $data = array();
            $data['title'] = I('post.title');
            $data['name'] = I('post.name');
            $data['sort'] = I('post.sort');
            $data['ismenu'] = I('post.ismenu');
            if (empty($id) || empty($data['title']) || empty($data['name']) || !is_numeric($data['sort']) || !is_numeric($data['ismenu'])) {
                $ret['code'] = -2;
                $ret['msg'] = '参数不全';
                break;
            }
            $map = array();
            $map['id'] = $id;
            $res = M('node','mygame_','DB_CONFIG_ZHU')->where($map)->save($data);
            if (false === $res) {
                $ret['code'] = -4;
                $ret['msg'] = '修改失败';
                break;
            }
            $ret['code'] = 1;
            $ret['msg'] = '修改成功';
            break;
        }while(0);
        exit(json_encode($ret));
    }

    /**
     * 删除节点
     * */
    public function deleteNode() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $id = I('post.id');
            if (!is_numeric($id)) {
                $ret['code'] = -2;
                $ret['msg'] = '非法参数';
                break;
            }
            $l = M('node','mygame_','DB_CONFIG_ZHU')->where(array('id'=>$id))->find();
            if (!$l) {
                $ret['code'] = -3;
                $ret['msg'] = '节点不存在';
                break;
            }
            if ($l['status'] == 2) {
                $ret['code'] = -4;
                $ret['msg'] = '节点不允许删除';
                break;
            }
            $res = M('node','mygame_','DB_CONFIG_ZHU')->where(array('id'=>$id))->delete();
            //删除子节点
            $res1 = M('node','mygame_','DB_CONFIG_ZHU')->where(array('pid'=>$id))->delete();
            if (!$res) {
                $ret['code'] = -5;
                $ret['msg'] = '删除失败';
                break;
            }
            $ret['code'] = 1;
            $ret['msg'] = '删除成功';
            break;
        }while(0);
        exit(json_encode($ret));
    }

    /**
     * 角色列表
     * */
    public function roleList() {
        $count = M('role')->count();
        $Page = new \Think\Page($count,20);
        $show = $Page->show();
        $list=M('role')->order('create_time')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    /**
     * 增加角色
     * */
    public function roleAdd() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $data = array();
            $data['name'] = I('post.name', '', 'htmlspecialchars');
            $data['status'] = I('post.status', 0, 'intval');
            if (!$data['name']) {
                $ret['code'] = -2;
                $ret['msg'] = '参数不全';
                break;
            }

            $l = M('role','mygame_','DB_CONFIG_ZHU')->where(array('name'=>$data['name']))->find();
            if ($l) {
                $ret['code'] = -3;
                $ret['msg'] = '角色已存在';
                break;
            }
            $time = time();
            $data['create_time'] = $time;
            $data['update_time'] = $time;
            $rs = M('role','mygame_','DB_CONFIG_ZHU')->add($data);
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
    /**
     * 编辑角色
     * */
    public function roleEdit() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $id = I('post.id');
            $data = array();
            $data['name'] = I('post.name', '', 'htmlspecialchars');
            $data['status'] = I('post.status', 0, 'intval');
            if (!$data['name'] || !is_numeric($id) || !is_numeric($data['status'])) {
                $ret['code'] = -2;
                $ret['msg'] = '参数错误';
                break;
            }
            $time = time();
            $data['update_time'] = $time;
            $rs = M('role','mygame_','DB_CONFIG_ZHU')->where(array('id'=>$id))->save($data);
            if (!$rs) {
                $ret['code'] = -3;
                $ret['msg'] = '修改失败';
                break;
            }
            $ret['code'] = 1;
            $ret['msg'] = '修改成功';
            break;
        }while(0);
        exit(json_encode($ret));
    }
    /**
     * 删除角色
     * */
    public function roleDelete() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $id = I('post.id');
            if (!is_numeric($id)) {
                $ret['code'] = -2;
                $ret['msg'] = '参数错误';
                break;
            }
            $rs = M('role','mygame_','DB_CONFIG_ZHU')->where(array('id'=>$id))->delete();
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

    /**
     * 角色分配权限
     * */
    public function addAccess() {
        $ret = array('code'=>-1,'msg'=>'');
        $roleid = intval($_POST['roleid']);
        if (!$roleid) {
            //$this->error('参数错误');
            $ret['code'] = -2;
            $ret['msg'] = '参数错误';
            exit(json_encode($ret));
        }
        $roleinfo = M('role','mygame_','DB_CONFIG_ZHU')->where('id='.$roleid)->find();
        if (!$roleinfo) {
            //$this->error('角色不存在');
            $ret['code'] = -2;
            $ret['msg'] = '角色不存在';
            exit(json_encode($ret));
        }

        $nodelist	=	D("node")->getNodeList();
        $nodeUser	=	D("node")->getNodeListByRoleId($roleid);
        foreach ($nodelist as &$v){
            if(in_array($v, $nodeUser)){
                $v['hv']	= 1;
            }else
                $v['hv']	= 0;
        }
        $nodetree	=	D("node")->getChildNode(0,$nodelist);
        $this->assign('roleinfo',$roleinfo);
        $this->assign('nodetree',$nodetree);
        //$this->display();
        $ret['code'] = 1;
        $ret['msg'] = 'success';
        $ret['html'] = $this->fetch();
        exit(json_encode($ret));
    }

    /**
     * 角色更新权限
     * */
    public function saveAccess() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $roleid = I('post.roleid');
            if (!is_numeric($roleid)) {
                $ret['code'] = -2;
                $ret['msg'] = '参数错误';
                break;
            }
            $nodeid_str = I('post.nodeid_str');
            $nodeArr = explode(',', $nodeid_str);

            //删除原有权限
            $model = M('access','mygame_','DB_CONFIG_ZHU');
            $acc_log = $model->where('role_id='.$roleid)->find();
            if ($acc_log) {
                $del_rs = $model->where('role_id='.$roleid)->delete();
                if (!$del_rs) {
                    $ret['code'] = -3;
                    $ret['msg'] = '数据更新失败';
                    break;
                }
            }

            $data = array();
            foreach ($nodeArr as $val) {
                $temp = array();
                $temp['role_id'] = $roleid;
                $temp['node_id'] = $val;
                $temp['level'] = 0;
                $temp['pid'] = 0;
                $data[] = $temp;
            }
            $rs = $model->addAll($data);

            $ret['code'] = 1;
            $ret['msg'] = '修改成功';
            break;
        }while(0);
        exit(json_encode($ret));
    }


    /**
     * 管理员列表
     */
    public function memberList() {
        $model	=D('UserView');
        $count = $model->count();
        $page = new \Think\Page($count, 20);
        $list = $model->order('create_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('pagebar', $page->show());

        //角色列表
        $this->assign('rolelist',M('role')->where("status=1")->order('create_time desc')->select());
        $this->display();
    }
    /**
     * 添加管理员
     * */
    public function memberAdd() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $data = array();
            $data['username'] = I('post.username', '', 'htmlspecialchars');
            $data['password'] = I('post.password', '', 'htmlspecialchars');
            $data['nickname'] = I('post.nickname', '', 'htmlspecialchars');
            $data['status'] = I('post.status', 1, 'intval');
            $role = I('post.role', '', 'intval');
            if (!is_numeric($role) || !$data['username'] || !$data['password'] || !$data['nickname']) {
                $ret['code'] = -2;
                $ret['msg'] = '参数错误';
                break;
            }
            if (!preg_match('/^[a-zA-Z0-9]{6,20}$/',$data['password'])) {
                $ret['code'] = -3;
                $ret['msg'] = '用户名应该为6到20位字母和数字';
                break;
            }
            $l = M('user','mygame_','DB_CONFIG_ZHU')->where(array('username'=>$data['username']))->find();
            if ($l) {
                $ret['code'] = -4;
                $ret['msg'] = '帐号重复';
                break;
            }
            $time = time();
            $data['password'] = md5($data['password']);
            $data['email'] = $data['username'].'@7477.com';
            $data['realname'] = $data['username'];
            $data['create_time'] = $time;
            $data['update_time'] = $time;
            $data['last_login_time'] = $time;
            $data['bind_account'] = '';
            $data['remark'] = '';
            $data['info'] = '';
            $rs = M('user','mygame_','DB_CONFIG_ZHU')->add($data);
            if (!$rs) {
                $ret['code'] = -5;
                $ret['msg'] = '添加失败';
                break;
            }

            //添加角色
            if ($role) {
                $roledata = array();
                $roledata['role_id'] = $role;
                $roledata['user_id'] = $rs;
                $rr = M('role_user','mygame_','DB_CONFIG_ZHU')->add($roledata);
                if (!$rr) {
                    $ret['code'] = -6;
                    $ret['msg'] = '添加角色失败';
                    break;
                }
            }
            $ret['code'] = 1;
            $ret['msg'] = '添加成功';
            break;
        }while(0);
        exit(json_encode($ret));
    }

    /**
     * 修改管理员
     * */
    public function memberEdit() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $id = I('post.id', '', 'htmlspecialchars');
            $password = I('post.password', '', 'htmlspecialchars');
            $nickname = I('post.nickname', '', 'htmlspecialchars');
            $role = I('post.role');
            $status = I('post.status');
            if (!is_numeric($id)) {
                $ret['code'] = -2;
                $ret['msg'] = '参数错误';
                break;
            }
            if ($password && !preg_match('/^[a-zA-Z0-9]{6,20}$/',$password)) {
                $ret['code'] = -3;
                $ret['msg'] = '密码应该为6到20位字母和数字';
                break;
            }
            //更新帐号信息
            $data = array();
            if ($password) $data['password'] = md5($password);
            if ($nickname) $data['nickname'] = $nickname;
            if (is_numeric($status)) $data['status'] = $status;
            if ($data) {
                $res = M('user','mygame_','DB_CONFIG_ZHU')->where(array('id'=>$id))->save($data);
                if (false === $res) {
                    $ret['code'] = -4;
                    $ret['msg'] = '帐号修改失败';
                    break;
                }
            }

            //更新角色信息
            if ($role) {
                $roleinfo = M('role','mygame_','DB_CONFIG_ZHU')->where(array('user_id'=>$id))->find();
                if ($roleinfo) {
                    $rdata = array();
                    $rdata['role_id'] = $role;
                    $role_rs = M('role_user','mygame_','DB_CONFIG_ZHU')->where(array('user_id'=>$id))->save($rdata);
                    if (false === $role_rs) {
                        $ret['code'] = -5;
                        $ret['msg'] = '角色修改失败';
                        break;
                    }
                }else {
                    $rdata = array();
                    $rdata['role_id'] = $role;
                    $rdata['user_id'] = $id;
                    $role_rs = M('role_user','mygame_','DB_CONFIG_ZHU')->add($rdata);
                    if (false === $role_rs) {
                        $ret['code'] = -6;
                        $ret['msg'] = '角色修改失败';
                        break;
                    }
                }
            }
            $ret['code'] = 1;
            $ret['msg'] = '更新成功';
            break;
        }while(0);
        exit(json_encode($ret));
    }

    /**
     * 删除管理员
     */
    public function memberDelete() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $id = I('post.id', '', 'htmlspecialchars');
            if (!is_numeric($id)) {
                $ret['code'] = -2;
                $ret['msg'] = '参数错误';
                break;
            }
            $info = M('user','mygame_','DB_CONFIG_ZHU')->where(array('id'=>$id))->find();
            if (!$info) {
                $ret['code'] = -3;
                $ret['msg'] = '帐号不存在';
                break;
            }
            if ($info['username'] == 'admin') {
                $ret['code'] = -4;
                $ret['msg'] = '该帐号为超级管理员，不允许删除';
                break;
            }
            //删除帐号
            $res = M('user','mygame_','DB_CONFIG_ZHU')->where(array('id'=>$id))->delete();
            if (!$res) {
                $ret['code'] = -5;
                $ret['msg'] = '帐号删除失败';
                break;
            }
            //删除角色列表
            $rr = M('role_user','mygame_','DB_CONFIG_ZHU')->where(array('user_id'=>$id))->delete();
            //删除channel
            M('user_channel','mygame_','DB_CONFIG_ZHU')->where(array('user_id'=>$id))->delete();

            $ret['code'] = 1;
            $ret['msg'] = '删除成功';
            break;
        }while(0);
        exit(json_encode($ret));
    }


}