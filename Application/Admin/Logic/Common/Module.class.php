<?php
/**
 * Created by Notepad++.
 * User: dengxiaolong
 * Date: 2018/03/29
 * Time: 11:08
 */

namespace Admin\Logic\Common;
class Module {	
     //默认查询7天时间的数据
	var $table;
	
	public  function __construct($table){		
		$this->table = $table;
	}
    public function Module($table) {
    	$this->__construct($table);
    }
	
	//所有数据列表
	public function module_list(){
		$classname =ucfirst(strtolower($this->table));
		import('Common/Vendor/Sysmodel/'.$classname);		
        $class    = new $classname();		
		$fileds =$class->getFields();	
		//查找对应的栏目id		
		$content = M($classname);
		$contentmap=array();
		$count = $content	
				->where($contentmap)					
				->count();	
		$page = new \Think\Page($count, 10);
		
		$list = $content
				->where($contentmap)
				->limit($page->firstRow.','.$page->listRows)
				->order('sort desc,id desc')
				->select();	
		$showfields = $class->getShowFields();
		
		$data['list'] = $list;
		$data['showfields'] = $showfields ;
		$data['page'] = $page->show ();
		$data['fileds'] = $fileds;
		return $data;		
	}
	//数据添加
	public function module_add($ext){	
		
		//查找模型对应的表格和对应的类名
		$classname =ucfirst(strtolower($this->table));		
		import('Common/Vendor/Sysmodel/'.$classname);		
		$class    = new $classname();
		if ($_POST) {
			$ret = array("code"=>-1,"msg"=>'',"data"=>"");
            do{ 
				if(!$ext['data']){
					$data = $_POST;	
				}else{
					$data = $ext['data'];	
				}							
				//检查数据
				$checkresult = $class->checkData($data);					
				if($checkresult['code']!=1){
					$ret = $checkresult;
					break;
				}						
				//整理数据
				$data = $class->filter($data);	
				
				$content = M($classname);
				$data['addtime']=time();				
				$st = $content->data($data)->add();					
				if(!$st){
					$ret['code'] = 0;
					$ret['msg'] = '添加失败';
					break;					
				}			
				$ret['code'] = 1;
				$ret['msg'] = '添加成功';
				$ret['data'] = $st;
				break;
			}while(0);			
			return $ret;
		}else{			
			//这些案例所有都有对应栏目，所以是公用的		
			$html = $class->get_html($ext);	
			return $html;			
		}
	}
	//数据添加
	public function module_add_duo($ext){	
		
		//查找模型对应的表格和对应的类名
		$classname =ucfirst(strtolower($this->table));		
		import('Common/Vendor/Sysmodel/'.$classname);		
		$class    = new $classname();
		if ($_POST) {
			$ret = array("code"=>-1,"msg"=>'',"data"=>"");
            do{ 
				if(!$ext['data']){
					$data = $_POST;	
				}else{
					$data = $ext['data'];	
				}							
				//检查数据
				foreach($ext['data'] as $key=>$val){
					$checkresult = $class->checkData($val);					
					if($checkresult['code']!=1){
						$ret = $checkresult;
						break;
					}						
					//整理数据
					$data[$key] = $class->filter($val);	
				}			
				$content = M($classname);
				foreach($data as $key=>$val){
					$data[$key]=$val;
					$data[$key]['addtime']=time();	
				}				
				$st = $content->addAll($data);					
				if(!$st){
					$ret['code'] = 0;
					$ret['msg'] = '添加失败';
					break;					
				}			
				$ret['code'] = 1;
				$ret['msg'] = '添加成功';				
				break;
			}while(0);			
			return $ret;
		}
	}
	//数据添加
	public function module_edit($id){
		//查找模型对应的表格和对应的类名
		$classname =ucfirst(strtolower($this->table));
		import('Common/Vendor/Sysmodel/'.$classname);		
		$class    = new $classname();
		if ($_POST) {
			$ret = array("code"=>-1,"msg"=>'',"data"=>"");
            do{ 
				$data = $_POST;				
				//检查数据
				$checkresult = $class->checkData($data);				
				if($checkresult['code']!=1){
					$ret = $checkresult;
					break;
				}
				//整理数据
				$data = $class->filter($data);
				print_r($data);die;
				$content = M($classname);
				if(!$data['id']){
					$ret['code'] = -1;
					$ret['msg'] = '更新数据出错';
					break;	
				}
				$map['id'] = $data['id'];
				$st = $content->where($map)->save($data);					
				if($st===false){
					$ret['code'] = 0;
					$ret['msg'] = '更新数据失败';
					break;					
				}			
				$ret['code'] = 1;
				$ret['msg'] = '更新数据成功';
				break;
			}while(0);
			return $ret;
		}else{
			//这些案例所有都有对应栏目，所以是公用的
			$content = M($classname);			
			$where['id'] =$id;
			$detail_info = $content->where($where)->find();	
			$html = $class->edit_html($detail_info);
			return $html;			
		}
	}
	
	//数据删除
	public function module_delete($id){
		//查找模型对应的表格和对应的类名
		$classname =ucfirst(strtolower($this->table));
		import('Common/Vendor/Sysmodel/'.$classname);		
		$class    = new $classname();
		if ($_POST) {
			$ret = array("code"=>-1,"msg"=>'',"data"=>"");            
			do{ 				
				$content = M($classname);			
				$where['id'] =$id;
				$st = $content->where($where)->delete();			
				if($st){
					$ret['code'] = 0;
					$ret['msg'] = '删除成功';
					break;
				}else{
					$ret['code'] = -1;
					$ret['msg'] = '删除失败';
					break;
				}				
			}while(0);		
			return $ret;
		}else{
			$ret['code'] = -500;
			$ret['msg'] = '非法请求';			
			return $ret;			
		}
	}
	
}