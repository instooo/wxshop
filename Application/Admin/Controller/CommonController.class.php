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
	public function getMethod(){
		$arr = getAction($_POST['controller']);		
		$html = "";
		foreach($arr as $val){
			$html.="<option value=''></option><option value=".$val.">".$val."</option>";
		}
		exit(json_encode($html));
	}
	
	//公用添加方法
	public function data_list($table){
		$module = new \Admin\Logic\Common\Module($table);
		$data = $module->module_list();
		$this->assign('data',$data);		
		$this->display();		
	}
	
	//获取html
	public function data_get_html($table,$ext){
		$module = new \Admin\Logic\Common\Module($table);		
		$data = $module->module_add($ext);		
		return $data;				
		
	}
	//公用添加方法
	public function data_add($table){		
		$module = new \Admin\Logic\Common\Module($table);		
		$data = $module->module_add();		
		if ($_POST) {
			exit(json_encode($data));
		}else{
			$this->assign('html',$data);	
			$this->display();
		}		
	}
	//公用更新方法
	public function data_edit($table){
		$id =  $_REQUEST ['id'];
		$module = new \Admin\Logic\Common\Module($table);
		$data = $module->module_edit($id);
		if ($_POST) {
			exit(json_encode($data));
		}else{
			$this->assign('html',$data);	
			$this->display();
		}		
	}
	//公用删除方法
	public function data_delete($table){
		$id =  $_REQUEST ['id'];
		$module = new \Admin\Logic\Common\Module($table);
		$data = $module->module_delete($id);		
		exit(json_encode($data));
			
	}
	
}
                                