<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\ArrayTree;

class SystemController extends CommonController {
	/**
     * 渠道类型列表
     * */
    public function channel_type_list() {
    	$count = M('channel_type')->count();
    	$page = new \Think\Page($count, 20);
    	$list = M('channel_type')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
    	$this->assign('list', $list);
    	$this->assign('page', $page->show());
		$this->display('channel_type_list');
    }

    /**
     * 增加渠道类型
     * */
    public function channel_type_add() {
        if(IS_POST){
            $ret = array('code'=>-1,'msg'=>'');
            do{ 
            $data = array();
            $data['name'] = I('post.name', '', 'htmlspecialchars');
            $data['description'] = I('post.description', '', 'htmlspecialchars');
            $data['status'] = I('post.status', '', 'intval');
            $data['addtime'] = time();
            if (!$data['name'] || !$data['description']) {
                $ret['code'] = -2;
                $ret['msg'] = '参数不全';
                break;
            }

            //游戏名和游戏标识不能重复
            $map['name'] = $data['name'];
            $result = M('channel_type','mygame_','DB_CONFIG_ZHU')->where($map)->find();
            if ($result) {
                $ret['code'] = -3;
                $ret['msg'] = '渠道名称已存在';
                break;
            }
            unset($map);
            unset($result);
            
            $rs = M('channel_type','mygame_','DB_CONFIG_ZHU')->add($data);
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
        }else{
            $this->display('channel_type_add');
        }
    }
	/**
     * 编辑渠道类型
     * */
    public function channel_type_edit() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $data = array();
            $data['tid'] = I('post.tid','','intval');
            $data['name'] = I('post.name', '', 'htmlspecialchars');
            $data['description'] = I('post.description', '', 'htmlspecialchars');
            $data['status'] = I('post.status', 0, 'intval');
            $data['addtime'] = time();
            if (!$data['name'] || !$data['description'] || !is_numeric($data['tid']) || !is_numeric($data['status'])) {
                $ret['code'] = -2;
                $ret['msg'] = '参数错误';
                break;
            }

            //渠道名不能重复,过滤当前修改项
            $map['name'] = $data['name'];
            $map['tid'] = array('neq',$data['tid']);
            $result = M('channel_type','mygame_','DB_CONFIG_ZHU')->where($map)->find();
            if ($result) {
                $ret['code'] = -3;
                $ret['msg'] = '渠道类型名已存在';
                break;
            }
            unset($result);

            //修改渠道
            unset($map);
            $map['tid'] =$data['tid'];
            $rs = M('channel_type','mygame_','DB_CONFIG_ZHU')->where($map)->save($data);

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
     * 删除渠道类型
     * */
    public function channel_type_dele() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $tid = I('post.tid');
            if (!is_numeric($tid)) {
                $ret['code'] = -2;
                $ret['msg'] = '参数错误';
                break;
            }

            $map['tid']=$tid;
            $rs = M('channel_type','mygame_','DB_CONFIG_ZHU')->where($map)->delete();
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
     * 游戏列表
     * */
	public function game_list() {
    	$count = M('game')->count();
    	$page = new \Think\Page($count, 20);
    	$list = M('game')
            ->limit($page->firstRow.','.$page->listRows)
            ->order('id desc')
            ->select();
    	$this->assign('list', $list);
    	$this->assign('page', $page->show());
		$this->display('game_list');
    }

    //随机数
    private function createRandomStr($length)
    {
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';//62个字符
        $strlen = 62;
        while ($length > $strlen) {
            $str .= $str;
            $strlen += 62;
        }
        $str = str_shuffle($str);
        return substr($str, 0, $length);
    }
	/**
     * 增加游戏
     * */
    public function game_add() {
        if(IS_POST){
            $ret = array('code'=>-1,'msg'=>'');
            do{                    
            $data = array();
            $data['gid'] = I('post.gid','','intval');
            $data['game'] = I('post.game','', 'htmlspecialchars');
            $data['description'] = I('post.description','', 'htmlspecialchars');
            $data['status'] = I('post.status', '', 'intval');
            if (!is_numeric($data['gid']) || !$data['game'] || !$data['description']) {
                $ret['code'] = -2;
                $ret['msg'] = '参数不全';
                break;
            }

            //游戏名和游戏标识不能重复
            $map['gid'] = $data['gid'];
            $result = M('game','mygame_','DB_CONFIG_ZHU')->where($map)->find();
            if ($result) {
                $ret['code'] = -3;
                $ret['msg'] = '游戏id已存在';
                break;
            }
            unset($map);
            unset($result);
            $map['game'] = $data['game'];
            $result = M('game','mygame_','DB_CONFIG_ZHU')->where($map)->find();
            if ($result) {
                $ret['code'] = -5;
                $ret['msg'] = '游戏名已存在';
                break;
            }
            
            $rand = $this->createRandomStr(10);
            $secret = 'secret';
            $data['secret'] = md5(md5($rand).$secret);

            $rs = M('game','mygame_','DB_CONFIG_ZHU')->add($data);
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
        }else{
            $this->display('game_add');
        }        
    }
    /**
     * 编辑游戏
     * */
	public function game_edit() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -1;
                $ret['msg'] = '非法请求';
                break;
            }
            $data = array();
            $data['id'] = I('post.id','','intval');
            $data['gid'] = I('post.gid','','intval');
            $data['game'] = I('post.game', '', 'htmlspecialchars');
            $data['description'] = I('post.description', '', 'htmlspecialchars');
            $data['status'] = I('post.status', 0, 'intval');
            $data['secret'] = I('post.secret', '', 'htmlspecialchars');
            if (!$data['game'] || !$data['secret'] || !$data['description'] || !is_numeric($data['id']) || !is_numeric($data['status']) || !is_numeric($data['gid'])) {
                $ret['code'] = -2;
                $ret['msg'] = '参数错误';
                break;
            }

            //游戏名和游戏标识不能重复,过滤当前修改项
            $map['gid'] = $data['gid'];
            $map['id'] = array('neq',$data['id']);
            $result = M('game','mygame_','DB_CONFIG_ZHU')->where($map)->find();
            if ($result) {
                $ret['code'] = -3;
                $ret['msg'] = '游戏id已存在';
                break;
            }
            unset($map);
            unset($result);
            $map['game'] = $data['game'];
            $map['id'] = array('neq',$data['id']);
            $result = M('game','mygame_','DB_CONFIG_ZHU')->where($map)->find();
            if ($result) {
                $ret['code'] = -5;
                $ret['msg'] = '游戏名已存在';
                break;
            }       
            //修改游戏
            unset($map);
            $map['id'] =$data['id'];
            $rs = M('game','mygame_','DB_CONFIG_ZHU')->where($map)->save($data);
            if ($rs === false) {
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
     * 删除游戏
     * */	
	public function game_dele() {
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

            //删除数据
            $map['id']=$id;
            $rs = M('game','mygame_','DB_CONFIG_ZHU')->where($map)->delete();
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
     * 渠道列表
     * */
	public function channel_list() {
        $rechannel = $this->getTotalChannel();
	    if($this->meminfo['username'] == 'admin'){
            //查找一级渠道
            $map['pid']=0;
            $topchannel = M('channel')->where($map)->select();
            unset($map);
        }else{
            $topchannel = $this->getTotalChannel();
            $topid = array_column($topchannel,'id');
            $map['pid'] = array('in',$topid);
        }
		$channelid = $_REQUEST['channelid'];
		if($channelid){
			$map['pid']=$channelid;
		}	
        if ($_REQUEST['s_username']) {
            $this->assign('s_username', $_REQUEST['s_username']);
            $map['name'] = array('like', '%'.trim(htmlspecialchars($_REQUEST['s_username'])).'%');
        }
        $start = $_REQUEST['start'];
        $end = $_REQUEST['end'];
        if ($start && $end) {
            $start_time = strtotime($start.' 00:00:00');
            $end_time = strtotime($end.' 23:59:59');
            $this->assign('start', $start);
            $this->assign('end', $end);
            $map['addtime'] = array('between', array($start_time, $end_time));
        }

    	$count = M('channel')->where($map)->count();
    	$page = new \Think\Page($count, 20);
    	$list = M('channel c') 
			->where($map)
	    	->limit($page->firstRow.','.$page->listRows)
			->order('id desc')
	    	->select();
		$this->assign('topchannel', $topchannel);
		$this->assign('channelid', $channelid);		
    	$this->assign('list', $list);
    	$this->assign('page', $page->show());
		$this->display('channel_list');
    }
	
	//添加渠道
    public function channel_add() {
		if(IS_POST){
			$ret = array('code'=>-1,'msg'=>'');
			do{						
				$data = array();
				$data['pid'] = I('post.pid','','intval');
				$data['tid'] = I('post.tid','','intval');
				$data['name'] = I('post.name', '', 'htmlspecialchars');
				$data['short_name'] = I('post.short_name', '', 'htmlspecialchars');
				$data['description'] = I('post.description', '', 'htmlspecialchars');
				$data['status'] = I('post.status', '', 'intval');
				$data['addtime'] = time();
				
				if (!$data['name'] || !$data['description'] || !$data['short_name'] || !is_numeric($data['pid']) || !is_numeric($data['tid'])) {
					$ret['code'] = -2;
					$ret['msg'] = '参数不全';
					break;
				}
				//渠道名和渠道标识不能重复
				$map['name'] = $data['name'];
				$result = M('channel','mygame_','DB_CONFIG_ZHU')->where($map)->find();
				if ($result) {
					$ret['code'] = -3;
					$ret['msg'] = '渠道已存在';
					break;
				}
				unset($map);
				unset($result);
				$map['short_name'] = $data['short_name'];
				$result = M('channel','mygame_','DB_CONFIG_ZHU')->where($map)->find();
				if ($result) {
					$ret['code'] = -5;
					$ret['msg'] = '渠道标识已存在';
					break;
				}				
				
				$rs = M('channel','mygame_','DB_CONFIG_ZHU')->add($data);
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
		}else{
			if($this->meminfo['username'] == 'admin'){
                //查找顶级渠道
                $channel_list = M('channel')->where(array('pid'=>0))->select();
                //查找渠道类型
                $channel_type_list = M('channel_type')->select();
			    $this->assign('admin',true);
            }else{
                $channel_list = $this->getTotalChannel();
                $typeid = array_unique(array_column($channel_list,'tid'));
                $typeid_map['tid'] = array('in',$typeid);
                $type_model = M('channel_type');
                $channel_type_list = $type_model->where($typeid_map)->field('tid,name')->select();
            }
			$this->assign('channel_list',$channel_list);
			$this->assign('channel_type_list',$channel_type_list);
			$this->display('channel_add');
		}		
    }
	
	//批量增加渠道
	public function channel_add_pl() {
		if(!IS_POST){
            if($this->meminfo['username'] == 'admin'){
                //查找顶级渠道
                $channel_list = M('channel','mygame_','DB_CONFIG_ZHU')->where(array('pid'=>0))->select();
                //查找渠道类型
                $channel_type_list = M('channel_type','mygame_','DB_CONFIG_ZHU')->select();
                $this->assign('admin',true);
            }else{
                $channel_list = $this->getTotalChannel();
                $typeid = array_unique(array_column($channel_list,'tid'));
                $typeid_map['tid'] = array('in',$typeid);
                $type_model = M('channel_type');
                $channel_type_list = $type_model->where($typeid_map)->field('tid,name')->select();
            }
			$this->assign('channel_list',$channel_list);
			$this->assign('channel_type_list',$channel_type_list);
			$this->display('channel_add_pl');
		}else{
			$ret = array('code'=>-1,'msg'=>'');
            do {
                $request = json_decode($_POST['data'], true);
                if (!$request) {
                    $ret['code'] = -2;
                    $ret['msg'] = '参数错误!';
                    break;
                }  			
                $return = array('success'=>array(),'fail'=>array());
                foreach ($request as $val) {
                    $r = $this->channel_multiadd_one($val);
                    if ($r['code'] == 1) $return['success'][] = $r;
                    else $return['fail'][] = $r;
                }
                $ret['code'] = 1;
                $ret['msg'] = '批量注册完毕';
                $ret['info'] = $return;
            }while(0);
            exit(json_encode($ret));
		}
	}

    //批量增加渠道
    public function channel_pl_dao() {
        if(!IS_POST){
            //查找顶级渠道
            $channel_list = M('channel')->where(array('pid'=>0))->select();
            //查找渠道类型
            $channel_type_list = M('channel_type')->select();           
            $this->assign('channel_list',$channel_list);
            $this->assign('channel_type_list',$channel_type_list);
            $this->display('channel_pl_dao');
        }else{
            $ret = array('code'=>-1,'msg'=>'');
            do {
                //接收数据
                $data['pid'] = I('post.pid','','intval');
                $data['tid'] = I('post.tid','','intval');
                $data['status'] = 1;
                $data['addtime'] = time();

                $file = $_FILES;

                //当文件存在
                if($file){
                    $tmparr=$data;
                    $arraydate='';
                    $content = file_get_contents($file['file']['tmp_name']);                    
                    $arraydate = rtrim($content);
                    $account_arr = explode("\r\n",$arraydate);					
                    $tmparr['name']=$account_arr; 
                    //渠道名和渠道标识不能重复
                    $map['name'] = array('in',$tmparr['name']);
                    $result = M('channel','mygame_','DB_CONFIG_ZHU')->field('name')->where($map)->select();
                    foreach($result  as $key=>$val){
                        $tmp_str .=$val['name']."<br/>"; 
                    }
                    if ($result) {
                        $ret['code'] = -3;
                        $ret['msg'] = $tmp_str.'渠道已存在';
                        break;
                    }
                    unset($map);
                    unset($result);
                    $add_picdata = array();
                    //遍历读数据 
                    $temparray = $tmparr;
                    unset($temparray['name']);
                    foreach($tmparr['name'] as $key=>$val){  
                         $temparray['name'] = trim($val);
                         $temparray['short_name'] = trim($val);
                         $temparray['description'] = trim($val);
                         $add_picdata[]=$temparray;     
                    }

                    $addresult = M('channel','mygame_','DB_CONFIG_ZHU')->addAll($add_picdata);
                    if($addresult){
                        $ret['code'] = 1;
                        $ret['msg'] = '添加成功';
                        break;
                    }else{
                       $ret['code'] = 0;
                       
                    }       
                }else { 
                    $addresult = M('channel')->add($data);
                    if(!$addresult){
                        $ret['code'] = 0;
                        $ret['msg'] = '添加失败';
                        break;
                    }
                }
            }while(0);
            exit(json_encode($ret));
        }
    }

    private function channel_multiadd_one($request) {
		$data = array();
		$data['pid'] = $request['pid'];
		$data['tid'] = $request['tid'];
		$data['name'] = $request['username'];
		$data['short_name'] = $request['username'];
		$data['description'] = $request['username'];
		$data['status'] = 1;
		$data['addtime'] = time();
		$temp['tag'] = $request['tag_username'];
		if (!$data['name'] || !$data['description'] || !$data['short_name'] || !is_numeric($data['pid']) || !is_numeric($data['tid'])) {
			$ret['code'] = -2;			
            $temp['show'] = '参数不全';
            $ret['msg'][] = $temp;		
			return $ret;
		}
		//渠道名和渠道标识不能重复
		$map['name'] = $data['name'];
		$result = M('channel','mygame_','DB_CONFIG_ZHU')->where($map)->find();
		if ($result) {
			$ret['code'] = -3;			
            $temp['show'] = '渠道已存在';
            $ret['msg'][] = $temp;				
			return $ret;
		}
		unset($map);
		unset($result);
		$map['short_name'] = $data['short_name'];
		$result = M('channel','mygame_','DB_CONFIG_ZHU')->where($map)->find();
		if ($result) {
			$ret['code'] = -5;
			$temp['show'] = '渠道标识已存在';
            $ret['msg'][] = $temp;		
			return $ret;
		}				
		
		$rs = M('channel','mygame_','DB_CONFIG_ZHU')->add($data);
		if (!$rs) {
			$ret['code'] = -4;
			$temp['show'] = '添加失败';
            $ret['msg'][] = $temp;					
			return $ret;
		}
		$ret['code'] = 1;
		$ret['msg'] = '添加成功';
		return $ret;
    }
   //修改渠道
   public function channel_edit() {		
		if(IS_POST){
			$ret = array('code'=>-1,'msg'=>'');
			do{				
				//接收参数
				$data = array();
				$data['id'] = I('post.id','','intval');
				$data['pid'] = I('post.pid','','intval');
				$data['tid'] = I('post.tid','','intval');
				$data['name'] = I('post.name', '', 'htmlspecialchars');
				$data['short_name'] = I('post.short_name', '', 'htmlspecialchars');
				$data['description'] = I('post.description', '', 'htmlspecialchars');
				$data['status'] = I('post.status', '', 'intval');
				if (!$data['name'] || !$data['short_name'] || !$data['description'] || !is_numeric($data['pid']) || !is_numeric($data['tid']) || !is_numeric($data['status']) || !is_numeric($data['id'])) {
					$ret['code'] = -2;
					$ret['msg'] = '参数错误';
					break;
				}
				
				//渠道名和渠道标识不能重复,过滤当前修改项
				$map['name'] = $data['name'];
				$map['id'] = array('neq',$data['id']);
				$result = M('channel','mygame_','DB_CONFIG_ZHU')->where($map)->find();
				if ($result) {
					$ret['code'] = -3;
					$ret['msg'] = '渠道已存在';
					break;
				}
				unset($map);
				unset($result);
				$map['short_name'] = $data['short_name'];
				$map['id'] = array('neq',$data['id']);
				$result = M('channel','mygame_','DB_CONFIG_ZHU')->where($map)->find();
				if ($result) {
					$ret['code'] = -5;
					$ret['msg'] = '渠道标识已存在';
					break;
				}		
				//修改渠道
				unset($map);
				$map['id'] =$data['id'];
				$rs = M('channel','mygame_','DB_CONFIG_ZHU')->where($map)->save($data);
				if ($rs === false) {
					$ret['code'] = -3;
					$ret['msg'] = '修改失败';
					break;
				}
				$ret['code'] = 1;
				$ret['msg'] = '修改成功';
				break;
			}while(0);
			exit(json_encode($ret));
		}else{
			$id = I('get.id','','intval');
			//查询顶级渠道,并且不可以选自己
			$map['pid']=0;
			$map['id']=array('neq',$id);
            if($this->meminfo['username'] == 'admin'){
                //查找顶级渠道
                $channel_list = M('channel')->where(array('pid'=>0))->select();
                //查找渠道类型
                $channel_type_list = M('channel_type')->select();
                $this->assign('admin',true);
            }else{
                $channel_list = $this->getTotalChannel();
                $typeid = array_unique(array_column($channel_list,'tid'));
                $typeid_map['tid'] = array('in',$typeid);
                $type_model = M('channel_type');
                $channel_type_list = $type_model->where($typeid_map)->field('tid,name')->select();
            }

			unset($map);
			//查询当前编辑渠道
			$map['id']=$id;
			$channel_info = M('channel c')				
				->join('LEFT JOIN mygame_channel_type ct ON c.tid=ct.tid')				
				->field('c.*,ct.name as cname')
				->where($map)
				->order('id desc')
				->find();
//			print_r($channel_info);die;
			$this->assign('channel_info', $channel_info);
			$this->assign('channel_list',$channel_list);			
			$this->assign('channel_type_list',$channel_type_list);	
			$this->display('channel_edit');    
		}    		
    }

	/**
     * 删除渠道
     * */
	public function channel_dele() {
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
			
			//删除数据
			$map['id']=$id;
            $rs = M('channel','mygame_','DB_CONFIG_ZHU')->where($map)->delete();
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
     * 用户渠道管理
     * */
    public function userChannel() {
        $map = array();
        if ($_REQUEST['s_username']) {
            $this->assign('s_username', $_REQUEST['s_username']);
            $map['username'] = array('like', '%'.trim(htmlspecialchars($_REQUEST['s_username'])).'%');
        }
        $count = M('user')
            ->where($map)
            ->count();
        $page = new \Think\Page($count, 20);
        $list = M('user')
            ->field('id,username')
            ->where($map)
            ->order('create_time desc')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();

        $channel = M('user_channel uc')
            ->field('uc.user_id,c.short_name,c.id')
            ->join('LEFT JOIN mygame_channel c ON c.id=uc.cid')
            ->where(array('uc.user_id'=>array('in',array_column($list,'id'))))
            ->select();
        foreach ($list as $k=>$v) {
            $temp = array();
            foreach ($channel as $vv) {
                if ($vv['user_id'] == $v['id']) {
                    $str .= $vv['id'].'-';
                    $temp[] = $vv;
                }
            }
            $list[$k]['channel_id'] = rtrim($str,'-');
            $list[$k]['clist'] = $temp;
            unset($str);
        }
        $this->assign('list', $list);
        $this->assign('pagebar', $page->show());

        $clist1 = M('channel')->where(array('pid'=>0))->select();
        $this->assign('clist1', $clist1);
        $this->display();
    }

    /**
     * 编辑用户渠道
     * */
    public function doEditUserChannel() {
        $ret = array('code'=>-1,'msg'=>'');
        do{
            if (!IS_POST) {
                $ret['code'] = -2;
                $ret['msg'] = '非法提交';
                break;
            }
            $user_id = trim(htmlspecialchars($_REQUEST['user_id']));
            $channel = $_REQUEST['channel'];
            if (!is_numeric($user_id)) {
                $ret['code'] = -3;
                $ret['msg'] = '非法参数';
                break;
            }
            if (count($channel) == 0) {
                $ret['code'] = -4;
                $ret['msg'] = '渠道为空';
                break;
            }
            $uinfo = M('user','mygame_','DB_CONFIG_ZHU')->where('id='.$user_id)->find();
            if (!$uinfo) {
                $ret['code'] = -5;
                $ret['msg'] = '账户不存在';
                break;
            }
            $clist = M('channel','mygame_','DB_CONFIG_ZHU')->where(array('id'=>array('in',$channel)))->select();

            //清除原有渠道权限
            M('user_channel','mygame_','DB_CONFIG_ZHU')->where('user_id='.$user_id)->delete();

            //重新添加渠道权限
            $cdata = array();
            foreach ($clist as $val) {
                $temp = array();
                $temp['user_id'] = $user_id;
                $temp['cid'] = $val['id'];
                $cdata[] = $temp;
            }
            $rs = M('user_channel','mygame_','DB_CONFIG_ZHU')->addAll($cdata);
            if (!$rs) {
                $ret['code'] = -6;
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
     * 用户指定游戏
     */
    public function user_games()
    {
        $gamelist = M('game')->select();
        $gamelist = array_column($gamelist, 'game', 'gid');
        if (IS_POST) {
            $user_id = intval($_POST['user_id']);
            $usergames = M('user_games')->where(array('user_id' => $user_id))->find();
            if (!$usergames) {
                M('user_games')->add(array('user_id' => $user_id, 'games' => ''));
            }
            if ($_POST['act'] == 'info') {
                $usergames['games'] = explode(',', $usergames['games']);
                $this->ajaxReturn(array('code' => 1, 'msg' => 'success', 'data' => $usergames), 'JSON');
            }
            $save['games'] = $_POST['games'];
            $rs = M('user_games')->where(array('user_id' => $user_id))->save($save);
            if (false === $rs) {
                $this->ajaxReturn(array('code' => -2, 'msg' => '修改失败'), 'JSON');
            }
            $this->ajaxReturn(array('code' => 1, 'msg' => 'success'), 'JSON');
        } else {
            $map = array();
            if ($_REQUEST['s_username']) {
                $this->assign('s_username', $_REQUEST['s_username']);
                $map['u.username'] = array('like', '%' . trim(htmlspecialchars($_REQUEST['s_username'])) . '%');
            }
            $count = M('user u')
                ->join('left join mygame_user_games ug on ug.user_id=u.id')
                ->where($map)
                ->count();
            $page = new \Think\Page($count, 20);
            $list = M('user u')
                ->field('u.id,u.username,ug.games')
                ->join('left join mygame_user_games ug on ug.user_id=u.id')
                ->where($map)
                ->order('u.create_time desc')
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
            foreach ($list as $k => $v) {
                if ($v['games']) {
                    $list[$k]['games'] = explode(',', $v['games']);
                }
            }
            $this->assign('list', $list);
            $this->assign('gamelist', $gamelist);
            $this->assign('pagebar', $page->show());
            $this->display();
        }
    }
}