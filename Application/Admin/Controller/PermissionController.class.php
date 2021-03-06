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
		$arr['access_name'] = I('get.access_name','','htmlspecialchars');
		$arr['zhu_module'] = I('get.zhu_module','','htmlspecialchars');
		if($arr['zhu_module'] || $arr['access_name']){
			 $node_tree = D('Node')->getNodeTreeMap($arr);			
		}else{
			 $node_tree = D('Node')->getNodeTree();	
		}
       
		//查找相关主模块
		$zhumodule_list = D('Node')->getModulelist();
		$this->assign('req',$arr);		
        $this->assign('node_tree',$node_tree);
		$this->assign('zhumodule_list',$zhumodule_list);
        $this->display();
    }
	
    /**
     * 增加节点
     * */
    public function addNode() {
		if (!IS_POST) {
			$pid = $_GET['pid']?$_GET['pid']:0;			
			//查找所有的控制器
			$con = getController('Admin');				
			for ($i=0; $i <count($con) ; $i++) { 
				$act[$con[$i]]= getAction($con[$i]);
			}	
			$this->assign('pid',$pid);	
			$this->assign('act',$act);	
			$this->assign('con',$con);
			$this->display("nodeadd");
        }else{
			$ret = array('code'=>-1,'msg'=>'');
			do{            
				$data = array();
				$data['pid']		=	I('post.pid');
				$data['name']	=	I('post.name');
				$data['title']	=	I('post.title');
				$data['zhu_module']	=	I('post.zhu_module');
				$data['access_name']	=	I('post.access_name');
				$data['sort']	=	I('post.sort',0,'intval');
				$data['ismenu']		=	I('post.ismenu',1,'intval');
				$data['status']		=	1;	
				$data['iconclass']		=I('post.iconclass');	
					
				if (!is_numeric($data['pid']) || !$data['name'] || !$data['title']) {
					$ret['code'] = -2;
					$ret['msg'] = '参数不全';
					break;
				}
				//查询父菜单
				$model = M('node');
				$pinfo = $model->where('id='.$data['pid'])->find();
				$data['level'] = $pinfo?$pinfo['level']+1:0;
				if ($data['level'] >= 3) {
					$ret['code'] = -3;
					$ret['msg'] = '最多只支持3级菜单';
					break;
				}
				$pinfoaa = $model->where('name="'.$data['name'].'"')->find();
				if($pinfoaa){
					$ret['code'] = -6;
					$ret['msg'] = '已经存在菜单节点了';
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
    }

    /**
     * 编辑节点
     * */
    public function updateNode() {
		if (!IS_POST) {			
			$id = I('get.id', '', 'htmlspecialchars');			
			$res = M('node a')				
				->where(array('id'=>$id))
				->find();			
			$this->assign('info',$res);	
			$nowcon = explode('/',$res['name']);
			//查找所有的控制器
			$con = getController('Admin');			

			$this->assign('nowcon',$nowcon[1]);
			$this->assign('nowact',$nowcon[2]);			
			$this->assign('id',$id);
			$this->assign('pid',$pid);
			$this->assign('con',$con);
			$this->assign('act',getAction($nowcon[1]));
			$this->display("nodeedit");
        }else{
			$ret = array('code'=>-1,'msg'=>'');
			do{
				$id = I('post.id');
				$data = array();
				$data['title'] = I('post.title');
				$data['name'] = I('post.name');
				$data['zhu_module']	=	I('post.zhu_module');
				$data['access_name']	=	I('post.access_name');
				$data['sort'] = I('post.sort');
				$data['ismenu'] = I('post.ismenu');
				$data['iconclass']	=I('post.iconclass');	
				if (empty($id) || empty($data['title']) || empty($data['name']) || !is_numeric($data['sort']) || !is_numeric($data['ismenu']) || empty($data['zhu_module']) || empty($data['access_name']) ) {
					$ret['code'] = -2;
					$ret['msg'] = '参数不全';
					break;
				}
				//过滤相同控制器名的节点
				$mapda['id'] = array('neq',$id);
				$mapda['name']=$data['name'];
				$flag = M('node')->where($mapda)->find();
				if($flag){
					$ret['code'] = -2;
					$ret['msg'] = '节点已经存在';
					break;
				}
				$map = array();
				$map['id'] = $id;
				$res = M('node')->where($map)->save($data);
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
			 //查看节点是否有子节点
            $res1 = M('node')->where(array('pid'=>$id))->find();
			if ($res1) {
                $ret['code'] = -3;
                $ret['msg'] = '请先删除子节点';
                break;
            }
            $l = M('node')->where(array('id'=>$id))->find();
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
            $res = M('node')->where(array('id'=>$id))->delete();           
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
        $Page = new \Think\Page($count,2);
        $show = $Page->show();
        $list=M('role')->order('create_time')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('pagebar',$show);		
        $this->display();
    }

    /**
     * 增加角色
     * */
    public function roleAdd() {
		 if (!IS_POST) {
			$this->display();
		}else{
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

				$l = M('role')->where(array('name'=>$data['name']))->find();
				if ($l) {
					$ret['code'] = -3;
					$ret['msg'] = '角色已存在';
					break;
				}
				$time = time();
				$data['create_time'] = $time;
				$data['update_time'] = $time;
				$rs = M('role')->add($data);
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
        
    }
    /**
     * 编辑角色
     * */
    public function roleEdit() {
		if (!IS_POST) {
			$id = I('get.id', '', 'htmlspecialchars');			
			$res = M('role a')
				//->join("wxshop_role_user b on a.id=b.user_id ")
				->where(array('id'=>$id))
				->find();		
			$this->assign('info',$res);	
			$this->display();
		}else{
			$ret = array('code'=>-1,'msg'=>'');
			do{           
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
				$rs = M('role')->where(array('id'=>$id))->save($data);
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
            $rs = M('role')->where(array('id'=>$id))->delete();
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
		if (!IS_POST) {			
			$roleid = intval($_GET['roleid']);
			if (!$roleid) {				
				$ret['code'] = -2;
				$ret['msg'] = '参数错误';				
			}
			$roleinfo = M('role')->where('id='.$roleid)->find();
			if (!$roleinfo) {            
				$ret['code'] = -2;
				$ret['msg'] = '角色不存在';				
			}
			//按主模块获取权限
			$nodelist	=	D("node")->getModulelist();				
			$nodeUser	=	D("node")->getModulelistByRoleId($roleid);			
			foreach ($nodelist as &$v){
				if(in_array($v, $nodeUser)){
					$v['hv']	= 1;
				}else{
					$v['hv']	= 0;
				}					
			}
			$nodetree	=	D("node")->getLevelNode(0,0,$nodelist);			
			$this->assign('roleinfo',$roleinfo);
			$this->assign('nodetree',$nodetree);
			$this->assign('roleid',$roleid);
			$this->display('accessadd');
		}else{
			
		}
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
            $moduleid_str = I('post.moduleid'); 
			//找找所有的节点ID
			$map['access_name']=array('in',$moduleid_str);			
			$nodeArr = M('node')->where($map)->select();	
			$pidarr=D('node')->getAllZhumodule($nodeArr);			
			$moduleid_str = array_merge($moduleid_str,$pidarr);			
			$map['access_name']=array('in',array_unique($moduleid_str));
			$nodeArr = M('node')->where($map)->select();			
            //删除原有权限
            $model = M('access');
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
                $temp['node_id'] = $val['id'];
                $temp['level'] = $val['level'];
                $temp['pid'] = $val['pid'];
				$temp['module'] = $val['access_name'];
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
        $page = new \Think\Page($count, 1);
        $list = $model->order('create_time desc')->limit($page->firstRow.','.$page->listRows)->select();		
        $this->assign('list', $list);
		$page -> setConfig('prev','<');
		$page -> setConfig('next','>');		
        $this->assign('pagebar', $page->show());        
        $this->display();
    }
    /**
     * 添加管理员
     * */
    public function memberAdd() {
		if (!IS_POST) {
			//角色列表
			$this->assign('rolelist',M('role')->where("status=1")->order('create_time desc')->select());
            $this->display();
        }else{
			$ret = array('code'=>-1,'msg'=>'');
			do{            
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
					$ret['msg'] = '密码应该为6到20位字母和数字';
					break;
				}
				$l = M('user')->where(array('username'=>$data['username']))->find();
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
				$rs = M('user')->add($data);
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
					$rr = M('role_user')->add($roledata);
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
    }

    /**
     * 修改管理员
     * */
    public function memberEdit() {
		if (!IS_POST) {			
			$id = I('get.id', '', 'htmlspecialchars');			
			$res = M('user a')
				->join("wxshop_role_user b on a.id=b.user_id ")
				->where(array('id'=>$id))
				->find();		
			$this->assign('info',$res);	
			$this->assign('rolelist',M('role')->where("status=1")->order('create_time desc')->select());
            $this->display();
        }else{
			$ret = array('code'=>-1,'msg'=>'');
			do{   				
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
					$res = M('user')->where(array('id'=>$id))->save($data);
					if (false === $res) {
						$ret['code'] = -4;
						$ret['msg'] = '帐号修改失败';
						break;
					}
				}
				//更新角色信息
				if ($role) {
					$roleinfo = M('role_user')->where(array('user_id'=>$id))->find();
					if ($roleinfo) {
						$rdata = array();
						$rdata['role_id'] = $role;
						$role_rs = M('role_user')->where(array('user_id'=>$id))->save($rdata);
						if (false === $role_rs) {
							$ret['code'] = -5;
							$ret['msg'] = '角色修改失败';
							break;
						}
					}else {
						$rdata = array();
						$rdata['role_id'] = $role;
						$rdata['user_id'] = $id;
						$role_rs = M('role_user')->add($rdata);
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
            $info = M('user')->where(array('id'=>$id))->find();
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
            $res = M('user')->where(array('id'=>$id))->delete();
            if (!$res) {
                $ret['code'] = -5;
                $ret['msg'] = '帐号删除失败';
                break;
            }
            $ret['code'] = 1;
            $ret['msg'] = '删除成功';
            break;
        }while(0);
        exit(json_encode($ret));
    }


}