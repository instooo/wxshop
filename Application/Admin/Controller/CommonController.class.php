<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;
class CommonController extends Controller {
    public $meminfo;
    /**
     * 后台控制器初始化
     */
    public function __construct() {
        parent::__construct();

        //rbac 自带的游客验证
        Rbac::checkLogin();
        //如果是Index模块另外判断
        if(strtoupper(CONTROLLER_NAME)=="INDEX"){
            if(!isset($_SESSION['userid'])){
                redirect(PHP_FILE.C('USER_AUTH_GATEWAY'));
            }
        }
        //验证权限
        if(!Rbac::AccessDecision()){
            if(IS_AJAX){
                $this->ajaxReturn(array("state"=>-1,"msg"=>'没有权限',"data"=>""));
            }else
                $this->error("没有权限");
        }

        //账户信息
        $where = array();
        $where['User.id'] = $_SESSION['userid'];
        $this->meminfo = D('UserView')->where($where)->find();
        $this->assign("meminfo",$this->meminfo);

        if($_SESSION[C('ADMIN_AUTH_KEY')])
            $datalist   =   D('Node')->getNodeList();
        else
            $datalist   =   D('Node')->getNodeListByUid($_SESSION[C('USER_AUTH_KEY')]);
        $tree       =   D('Node')->getChildNode(0,$datalist);
        $this->assign("tree",$tree);

    }
    public function _initialize(){

    }

    /**
     * 获取渠道列表
     * */
    public function getChannel() {
		/*if ($this->meminfo['id']) {
			$return_data=S("return_data".$this->meminfo['id']);
			if(false){
				return $return_data;
			}else{
				$clist = M('user_channel uc')
					->field('c.id,c.pid,c.short_name,c.name')
					->join('LEFT JOIN mygame_channel c ON c.id=uc.cid')
					->where(array('uc.user_id'=>$this->meminfo['id']))
					->select();
				$sub_channel = array();
				$sec_channel = array();
				foreach ($clist as $val) {
					if ($val['pid'] > 0) {
						$sec_channel[] = $val;
					}else {
						$sub_channel[] = $val;
					}
				}
				if($sub_channel){
					 $sub_channels = $_REQUEST['sub_channel'];
					 if($sub_channels){
						 $secmap['pid']=$sub_channels;
						 $channel_level = M('channel')->where($secmap)->select();
						 $sec_channel =$channel_level;
					 }else{
						 $topArr =array_column($sub_channel, 'id');
						 $secmap['pid']=array('in',$topArr);
						 $channel_level = M('channel')->where($secmap)->select();
						 $sec_channel =$channel_level;
					 }
				}
				$return_data=array('sub_channel'=>$sub_channel,'sec_channel'=>$sec_channel);
				S('return_data'.$this->meminfo['id'],$return_data);
				return $return_data;
			}
		}else return null;*/
    }

    /**
     * 获取一二级渠道列表
     * */
    public function getUserChannel() {
        if ($this->meminfo['id']) {
            $return_data=S("return_data".$this->meminfo['id']);
            if(false){
                return $return_data;
            }else{
                $clist = M('user_channel uc')
                    ->field('c.id,c.pid,c.short_name,c.name')
                    ->join('LEFT JOIN mygame_channel c ON c.id=uc.cid')
                    ->where(array('uc.user_id'=>$this->meminfo['id']))
                    ->select();
                $sub_channel = array();
                $sec_channel = array();
                foreach ($clist as $val) {
                    if ($val['pid'] > 0) {
                        $sec_channel[] = $val;
                    }else {
                        $sub_channel[] = $val;
                    }
                }
                if($sub_channel){
                     $sub_channels = $_REQUEST['sub_channel'];
                     if($sub_channels){
                         $secmap['pid']=$sub_channels;
                         $channel_level = M('channel')->where($secmap)->select();
                         $sec_channel =$channel_level;
                     }else{
                         $topArr =array_column($sub_channel, 'id');
                         $secmap['pid']=array('in',$topArr);
                         $channel_level = M('channel')->where($secmap)->select();
                         $sec_channel =$channel_level;
                     }
                }
                $return_data=array('sub_channel'=>$sub_channel,'sec_channel'=>$sec_channel);
                S('return_data'.$this->meminfo['id'],$return_data);
                return $return_data;
            }
        }else return null;
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
        $map['uc.user_id']=$this->meminfo['id'];
        $map['c.pid']=0;
        $list = M('user_channel uc')
            ->field('c.id,c.pid,c.short_name,c.name,c.tid')
            ->join('LEFT JOIN mygame_channel c ON c.id=uc.cid')
            ->where($map)
            ->order('c.addtime desc')
            ->select();
        $this->ajaxReturn(array('code'=>1,'msg'=>'success','data'=>$list));
    }

	/**
     * 获取一级渠道列表
     * */
    public function getTotalChannel() {
		if ($this->meminfo['id']) {
			$return_data=S("return_data".$this->meminfo['id']);
			if(false){
				return $return_data;
			}else{
				$map['uc.user_id']=$this->meminfo['id'];
				$map['c.pid']=0;
				$clist = M('user_channel uc')
					->field('c.id,c.pid,c.short_name,c.name,c.tid')
					->join('LEFT JOIN mygame_channel c ON c.id=uc.cid')
					->where($map)
					->order('c.short_name asc')
					->select();
				return $clist;			
			}
		}else return null;
    }

    /**
     * 一级渠道->二级渠道
     * */
    public function getChildChannel() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            $sub_channel = $_REQUEST['sub_channel'];
            if (!$sub_channel) {
                $ret['code'] = -2;
                $ret['msg'] = '请先选择一级渠道';
                break;
            }
            $where['pid']=$sub_channel;
            $seclist = M('channel')->where($where)->select();
            if (!$seclist) {
                $ret['code'] = -3;
                $ret['msg'] = '该渠道暂无下线';
                break;
            }
            $sec_name = array_column($seclist,'name');
            $ret['sec_data'] = $sec_name;
            $ret['code'] = 1;
            $ret['seclist'] = $seclist;
            $ret['msg'] = 'success';

            break;
        }while(0);
        exit(json_encode($ret));
    }

    /**
     * 获取二级渠道
     * */
    public function getSecChannel() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            $sub_channel = $_REQUEST['sub_channel'];
            if ($sub_channel) {
                $where['pid']=$sub_channel;
                $seclist = M('channel')->where($where)->select();
            }else{
                $cid = $this->getChannel();
                $seclist= $cid['sec_channel'];
            }
            if (!$seclist) {
                $ret['code'] = -3;
                $ret['msg'] = '该渠道暂无下线';
                break;
            }
            $sec_name = array_column($seclist,'name');
            $ret['sec_data'] = $sec_name;
            $ret['code'] = 1;
            $ret['seclist'] = $seclist;
            $ret['msg'] = 'success';

            break;
        }while(0);
        exit(json_encode($ret));
    }

	/**
	*二级栏目检索功能
	*/
	protected function get_sec_channels(){
		$sec_search = $_REQUEST['secsearch'];
		$name = $this->isEscape($sec_search,true);
		$meminfo = $this->meminfo;
		$uc_model = M('user_channel');
		$where['user_id'] = $meminfo['id']; 
		$cid_list = $uc_model->where($where)->select();
		$cid = array_column($cid_list,'cid');
		$map['pid'] = array('in',$cid);
		$map['name'] = array('like','%'.$name.'%');
		$channel_model = M('channel');
		$channel_list = $channel_model->field('id')->where($map)->select();
		$channelArr = array_column($channel_list,'id');
		return $channelArr;
	}
	/**
	*下线条件筛选
	*/
	protected function channelReturn($channel){
		$secChannel = $channel['sec_channel'];
		$sec_data = array();
		$sec_data = array_column($secChannel,'name');
		$this->assign('sec_data',json_encode($sec_data));
		$sec_search = $_REQUEST['secsearch'];
		$name = $this->isEscape($sec_search,true);
		//接收栏目
        $sec_channels = $_REQUEST['sec_channel'];
        $sub_channels = $_REQUEST['sub_channel'];
				
        if ($sec_channels) {
            //获得二级栏目后，查询他的一级栏目并展示
            $channelArr[]= $sec_channels;
            $sub_channel_val = M('channel')->getFieldById($sec_channels, 'pid');
            $this->assign('sub_channel_val', $sub_channel_val);
            $this->assign('sec_channel_val', $sec_channels);           
             //获得二级栏目后，二级栏目列表
            $seclist = M('channel')->where(array('pid'=>$sub_channel_val))->select();
            $this->assign('sec_channel', $seclist);
        }else if($sub_channels){//当选择一级栏目时候
            $channelArr = array(); 
            $channelArr[] = $sub_channels;
			$condition['pid'] = $sub_channels;
			if($sec_search){
				$condition['name'] = array('like','%'.$name.'%');
				$this->assign('sec_search',$sec_search);
			}
            $seclist = M('channel')->where($condition)->select();
			
            $channelArr = array_merge($channelArr, array_column($seclist, 'id'));
            $this->assign('sub_channel_val', $sub_channels);
            $this->assign('sec_channel', $seclist);
        }else{
            //默认查询所有栏目下内容
			if($sec_search){
				$dataArr= $this->get_sec_channels();
				$this->assign('sec_search',$sec_search);
				if($dataArr){
					$channelArr = $dataArr;
				}else{
					$channelArr = array_column($channel['sub_channel'],'id');//顶级
				}
			}else{
				if($this->meminfo['username'] == 'admin'){
					$channelArr = null;
				}else{
					$channelArra = array_column($channel['sub_channel'],'id');//顶级
					$channelArrb = array_column($channel['sec_channel'],'id');//子级
					$channelArr = array_merge($channelArra,$channelArrb);
				}
				
			}                   
        }
		return $channelArr;
	}
	
	/**
	*特殊字符处理函数
	*/
	public function isEscape($val, $isboor = false) {
    if (! get_magic_quotes_gpc ()) {
        $val = addslashes ( $val );
    }
    if ($isboor) {
        $val = strtr ( $val, array (
                "%" => "\%",
                "_" => "\_" 
        ) );
    }
    return $val;
}

    /**
     * 获取当前用户可以查看的游戏
     */
    public function getUserGames() {
        $usergames = M('user_games')->where(array('user_id'=>$_SESSION['userid']))->find();
        $map = array();
        if ($usergames['games']) {
            $games = explode(',', $usergames['games']);
            $map['gid'] = array('in', $games);
        }
        $gamelist = M('game')->field('gid,game')->where($map)->order('gid desc')->select();
        return $gamelist;
    }

}
                                