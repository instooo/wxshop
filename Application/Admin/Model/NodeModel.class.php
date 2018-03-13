<?PHP
namespace Admin\Model;
use Think\Model;
class NodeModel extends Model{
	public $dataTreeData=array();
	
	public function getNodeList(){
		$where=array();		
		$data	=	$this->field('id,name,title,zhu_module,access_name,pid,level,ismenu,sort,(pid*level*sort) as sortby')->where($where)->order('sortby asc,sort asc')->select();
		return $data;
	}
	
	
	
	public function getModulelist(){
		$where=array();		
		$data	=	$this->field('zhu_module,access_name,level')->where($where)->group('zhu_module,access_name')->select();
		return $data;
	}
	
	
	public function getModulelistTree(){
		$where=array();		
		$data	=	$this->getModulelist();		
		//根据level获取树形		
		$tree=$this->getLevelNode(0,0,$data);		
		return $tree;
	}
	
	public function getModulelistByRoleId($roleid){
		$where=array();	
		$where['access.role_id']=$roleid;		
		$data	=	$this
					->join(C('DB_PREFIX').'access as access on access.node_id = id','LEFT')
					->field('wxshop_node.zhu_module,wxshop_node.access_name,wxshop_node.level')
					->where($where)
					->group('wxshop_node.zhu_module,wxshop_node.access_name')
					->select();			
		//根据level获取树形
		return $data;
	}
	
	public function getModulelistByRoleIdTree($roleid){
		$where=array();	
		$where['access.role_id']=$roleid;		
		$data	=	$this->getModulelistByRoleId($roleid);					
		//根据level获取树形		
		$tree=$this->getLevelNode(0,0,$data);		
		return $tree;
	}
	
	
	//根据level获取树形
	public function getLevelNode($access_name,$level=0,$datalist){
		$arr=array();
		foreach($datalist as $val){			
			if($level==$val['level'] && $access_name == $val['zhu_module']){					
				$temp=$level+1;
				$val['child']=$this->getLevelNode($val['access_name'],$temp,$datalist);
				if(empty($val['child'])){
					unset($val['child']);
				}
				$arr[]=$val;				
			}
		}	
		return $arr;
	}
	
	public function getNodeTreeMap($arr){
		foreach($arr as $key=>$val){
			if($val){
				$where[$key]=$val;
			}
		}			
		$datalist	= $this->field('id,name,title,zhu_module,access_name,pid,level,ismenu,sort,(pid*level*sort) as sortby')->where($where)->order('sortby asc,sort asc')->select();		
		$tree=$this->getChildNode(0,$datalist);
		return $tree;
	}
	
	public function getNodeTree($uid=false){
		if($uid)
			$datalist	=	$this->getNodeListByUid($uid);
		else
			$datalist	=	$this->getNodeList();
		$tree=$this->getChildNode(0,$datalist);
		return $tree;
	}
	
	//用户所有用的权限
	public function getNodeListByUid($uid){
		$sql	=	"
		select node.id,node.name,node.title,node.pid,node.level,node.ismenu,node.sort,(node.pid*node.level*node.sort) as sortby from wxshop_node as node left join wxshop_access as access on access.node_id = node.id left join wxshop_role_user as ru on ru.role_id = access.role_id left join wxshop_user as user on user.id = ru.user_id 	where user.id	=	$uid ORDER BY sort ASC";
		$rs	=	M('')->query($sql);		
		return $rs;
	}
	
	//角色所拥有的权限
	public function getNodeListByRoleId($roleid){
		$sql	=	"
		select node.id,node.name,node.title,node.pid,node.level,node.ismenu,node.sort,(node.pid*node.level*node.sort) as sortby from mygame_node as node left join mygame_access as access on access.node_id	= node.id 	where access.role_id	=	{$roleid} ORDER BY sortby ASC
		";
		$rs	=	M('')->query($sql);
		return $rs;
	}
	
	//递归成树
	public  function getChildNode($id,$datalist,$pid=false){
		$arr=array();
		foreach($datalist as $val){
			if($id==$val['pid']){
				$temp=$val['id'];
				$val['child']=$this->getChildNode($temp,$datalist);
				if(empty($val['child'])){
					unset($val['child']);
				}
				$arr[]=$val;
			}
		}
		return $arr;
	}
}