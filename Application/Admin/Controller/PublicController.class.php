<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;
Vendor ( 'Ucenter.UcApi' );
class PublicController extends Controller {
    /**
	 * 登陆界面
	 */
	public function Login() {	
		$this->display();
	}
	
	// 登录检测
	public function checkLogin() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $username = I('post.username', '', 'htmlspecialchars');
            $password = I('post.password', '');

            if (!preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_-]{2,16}$/u', $username)) {
                $ret['code'] = -2;
                $ret['msg'] = '请输入合法的用户名';
                break;
            }
            if (strlen($password) < 5 || strlen($password) > 18) {
                $ret['code'] = -3;
                $ret['msg'] = '请输入6位数以上的密码';
                break;
            }

            $uinfo = M('user','mygame_','DB_CONFIG_ZHU')->where(array('username'=>$username))->find();

            if (!$uinfo) {
                $ret['code'] = -6;
                $ret['msg'] = '用户名不存在';
                break;
            }
            if ($uinfo['status'] == 0) {
                $ret['state'] = -7;
                $ret['msg'] = '帐号被禁用';
                break;
            }
            if ($uinfo['password'] != md5($password)) {
                $ret['code'] = -8;
                $ret['msg'] = '密码错误';
                break;
            }
            $_SESSION['userid'] = $uinfo['id'];
            $_SESSION['user'] = $uinfo['username'];
            $_SESSION['photo'] = $uinfo['info'];
            $role_id=M('role_user')->field('role_id')->where('user_id='.$uinfo['id'])->find();
            $_SESSION['role_id'] = $role_id['role_id'];
            $user_model = M(C('USER_AUTH_MODEL'),'mygame_','DB_CONFIG_ZHU');

            //登录记录
            $ldata = array();
            $ldata['lastlogin_ip'] = get_client_ip();
            $ldata['last_login_time'] = time();
            $ldata['login_count'] = array('exp', 'login_count+1');
            $user_model->where(array('id'=>$uinfo['id']))->save($ldata);

            //保存rbac权限
            $authId = $uinfo['id'];
            $_SESSION[C('USER_AUTH_KEY')] = $authId;
            //顶级权限的用户（管理员）
            if (in_array($username, array("admin"))) {
                $_SESSION[C('ADMIN_AUTH_KEY')] = true;
            }
            // 缓存访问权限
            Rbac::saveAccessList($authId);

            $ret['code'] = 1;
            $ret['msg'] = '登陆成功';
            $ret['data'] = U('/index');
            break;
        }while(0);
        exit(json_encode($ret));
	}
	// 用户登出
	public function loginout(){	
		$_SESSION['user']=null;
		$_SESSION['uid']=null;
		unset($_SESSION);
		session('[destroy]');	//销毁所有SESSION
		$this->redirect("/public/login");
	}

    /**
     * 获取渠道信息
     */
    public function getChannelByType() {
        $type = trim(htmlspecialchars($_REQUEST['type']));
        $map = array();
        if (is_numeric($type)) {
            $map['tid'] = $type;
        }
        $list = M('channel')->where($map)->order('addtime desc')->select();
        $this->ajaxReturn(array('code'=>1,'msg'=>'success','data'=>$list));
    }

    /**
     * auth登录
     */
    public function auth() {
        C('VAR_JSONP_HANDLER', 'jsonpCallback');
        $username = trim(htmlspecialchars($_REQUEST['account']));
        $password = trim(htmlspecialchars($_REQUEST['password']));
        if (!$username || !$password) {
            $this->ajaxReturn(array('code'=>-1,'msg'=>'账号或密码缺失'), 'JSONP');
        }
        if (!preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_-]{2,16}$/u', $username)) {
            $this->ajaxReturn(array('code'=>-2,'msg'=>'请输入合法的用户名'), 'JSONP');
        }
        if (strlen($password) < 5 || strlen($password) > 18) {
            $this->ajaxReturn(array('code'=>-3,'msg'=>'请输入6位数以上的密码'), 'JSONP');
        }

        $uinfo = M('user','mygame_','DB_CONFIG_ZHU')->where(array('username'=>$username))->find();

        if (!$uinfo) {
            $this->ajaxReturn(array('code'=>-6,'msg'=>'用户名不存在'), 'JSONP');
        }
        if ($uinfo['status'] == 0) {
            $this->ajaxReturn(array('code'=>-7,'msg'=>'帐号被禁用'), 'JSONP');
        }
        if ($uinfo['password'] != md5($password)) {
            $this->ajaxReturn(array('code'=>-8,'msg'=>'密码错误'), 'JSONP');
        }
        $_SESSION['userid'] = $uinfo['id'];
        $_SESSION['user'] = $uinfo['username'];
        $_SESSION['photo'] = $uinfo['info'];
        $role_id=M('role_user')->field('role_id')->where('user_id='.$uinfo['id'])->find();
        $_SESSION['role_id'] = $role_id['role_id'];
        $user_model = M(C('USER_AUTH_MODEL'),'mygame_','DB_CONFIG_ZHU');

        //登录记录
        $ldata = array();
        $ldata['lastlogin_ip'] = get_client_ip();
        $ldata['last_login_time'] = time();
        $ldata['login_count'] = array('exp', 'login_count+1');
        $user_model->where(array('id'=>$uinfo['id']))->save($ldata);

        //保存rbac权限
        $authId = $uinfo['id'];
        $_SESSION[C('USER_AUTH_KEY')] = $authId;
        //顶级权限的用户（管理员）
        if (in_array($username, array("admin"))) {
            $_SESSION[C('ADMIN_AUTH_KEY')] = true;
        }
        // 缓存访问权限
        Rbac::saveAccessList($authId);

        $this->ajaxReturn(array('code'=>1,'msg'=>'success'), 'JSONP');
    }
}