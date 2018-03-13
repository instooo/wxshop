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
	//将树形展示出来
	public function  getTreeData($datalist,$fix){
		if(empty($datalist))
			return array();
		foreach ($datalist as &$val){
			$val['titleN']=$fix.$val['title'];
			$child	=$val['child'];	
			$val['hvChild']=	empty($child)?0:1;
			unset($val['child']);
			array_push($this->dataTreeData, $val);
			if(!empty($child)){
				$this->getTreeData($child,$fix.'--');
				
			}
			
		}	
	}
	
	//树形菜单栏
	public function makeHtml($datalist,$level=0){
		$html	=	"<ul class='cps_list clearfix'>";
		
		foreach ($datalist as $val){
			if ($val['hv'] == 1) $checked = 'checked';
			else $checked = '';
			$html .= '<li>';
			$html .= '<div class="cps_line"><label>'.$val['title'].'<input type="checkbox" class="cps_check sub_check1" name="id[]" value="'.$val['id'].'" '.$checked.' /></label></div>';
			if(!empty($val['child'])) {
				foreach ($val['child'] as $vc) {
					if ($vc['hv'] == 1) $checked = 'checked';
					else $checked = '';
					$html .= '<div class="cps_line"><label style="padding-left: 80px;">'.$vc['title'].'<input type="checkbox" class="cps_check sub_check2" name="id[]" value="'.$vc['id'].'" '.$checked.' /></label></div>';
					if (!empty($vc['child'])) {
						$html .= '<ul class="sub_ul"><li class="clearfix">';
						foreach ($vc['child'] as $v) {
							if ($v['hv'] == 1) $checked = 'checked';
							else $checked = '';
							$html .= '<label>'.$v['title'].'<input type="checkbox" class="cps_check sub_check3" name="id[]" value="'.$v['id'].'" '.$checked.' /></label>';
						}
						$html .= '</li></ul>';
					}
				}
			}
			$html .= '</li>';
		}
		$html.='</ul>';
		return $html;
	}

	public function makeHtml1($datalist,$level=0){
		$html	=	"<ul class='ullevel{$level} cps_list clearfix'>";

		foreach ($datalist as $val){
			if($val['hv']==1){
				$html   .= "<li class='level{$val['level']}' id='{$val['id']}'><p>{$val['title']}<input type=\"checkbox\" name='id[]' value=\"{$val['id']}\" checked/></p>";
			}else
				$html   .= "<li	class='level{$val['level']}' id='{$val['id']}'><p>{$val['title']}<input type=\"checkbox\" name='id[]' value=\"{$val['id']}\" /></p>";
			if(!empty($val['child'])){
				$html	.=	$this->makeHtml($val['child'],$level+1);
			}
			$html.="</li>";
		}
		$html.='</ul>';
		return $html;
	}


	private function getNodeById($data,$id){
		$selectId	=	array();
		foreach ($data as $v){
			if($v['id']==$id){
				$selectId	=	$v;
				break;
			}
		}
		return $selectId;
	}
	private function getHref($data,$id){
		$href	=	'';
		$selectId	=	$this->getNodeById($data, $id);
		if(!empty($selectId)){
			//如果是4级的话
			$level	=	$selectId['level'];
			$action	=	$selectId['name'];
			if($level==4){
				$idV3	=	$this->getNodeById($data,$selectId['pid']);
				$idV2	=	$this->getNodeById($data,$idV3['pid']);
			}else if($level ==	3){
				$idV2	=	$this->getNodeById($data,$selectId['pid']);
			}
			$model	=	$idV2['name'];
		}
		return U($model.'/'.$action);
	}
	

    //树形菜单栏
	public function makeMenue($tree, $datalist) {
		$html = '<ul class="items">';
		$html .= '<li class="home nav_item"><a class="link" href="/index/main"><span></span>主页</a></li>';
		foreach ($tree[0]['child'] as $val) {
			if($val['pid']!=0 && $val['ismenu']) {
				if ($val['name'] == 'permission') {
					$html .= '<li class="user nav_item">';
				}elseif ($val['name'] == 'complaint') {
					$html .= '<li class="system nav_item">';
				}elseif ($val['name'] == 'template') {
					$html .= '<li class="role nav_item">';
				}else {
					$html .= '<li class="power nav_item">';
				}

				$html .= '<a href="javascript:;"><span></span>'.$val['title'].'<i></i></a>';
				if (is_array($val['child'])) {
					$html .= '<ul class="sub_list">';
					foreach ($val['child'] as $v1) {
                        if ($v1['ismenu'] != 1) continue;
						if (is_array($v1['child'])) {
                            $childIsShow = false;
                            foreach ($v1['child'] as $v2) {
                                if ($v2['ismenu'] == 1) {
                                    $childIsShow = true;
                                    break;
                                }
                            }
                            if ($childIsShow) {
                                $html .= '<li>';
                                $html .= '<a href="javascript:;">'.$v1['title'].'<i style="margin-left:-60px;"></i></a>';
                                $html .= '<ul class="sub">';
                                foreach ($v1['child'] as $v2) {
                                    if ($v2['ismenu'] != 1) continue;
                                    $html .= '<li><a style="margin-left: 50px;" class="link" href="'.$this->getHref($datalist,$v2["id"]).'">'.$v2['title'].'</a></li>';
                                }
                                $html .= '</ul></li>';
                            }else {
                                $html .= '<li><a style="margin-left: 50px;" class="link" href="'.$this->getHref($datalist,$v1["id"]).'">'.$v1['title'].'<i style="background:none;"></i></a></li>';
                            }
						}else {
							$html .= '<li><a style="margin-left: 50px;" class="link" href="'.$this->getHref($datalist,$v1["id"]).'">'.$v1['title'].'<i style="background:none;"></i></a></li>';
						}
					}
					$html .= '</ul>';
				}
				$html .= '</li>';
			}
		}
		$html .= '</ul>';
		return $html;
	}
	
}