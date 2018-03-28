<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class GoodsController extends CommonController {
	//商品列表
	public function index(){		
		$this->display();
	}
	//添加商品
	public function goodadd(){
		//查找模型对应的表格和对应的类名
		$classname =ucfirst(strtolower('goods'));
		import('Common/Vendor/Sysmodel/'.$classname);		
		$class    = new $classname();
		if ($_POST) {
			$ret = array("code"=>-1,"msg"=>'',"data"=>"");
            do{ 
				$data = $_POST;				
				//检查数据
				//$checkresult = $class->checkData($data);				
				//if($checkresult['code']!=1){
				//	$ret = $checkresult;
				//	break;
				//}
				//整理数据
				//$data = $class->filter($data);				
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
				break;
			}while(0);
			exit(json_encode($ret));
		}else{
			//这些案例所有都有对应栏目，所以是公用的		
			$html = $class->get_html();			
			$this->assign('html',$html);	
			$this->display();
		}
	}
	//编辑商品
	public function goodedit(){
		$this->display();
	}
	//删除商品
	public function gooddelete(){
		$this->display();
	}
	
	//商品类型列表
	public function goods_type_list(){		
		$this->display();
	}
	//商品类型添加
	public function goods_type_add(){
		//查找模型对应的表格和对应的类名
		$classname =ucfirst(strtolower('goodstype'));
		import('Common/Vendor/Sysmodel/'.$classname);		
		$class    = new $classname();
		if ($_POST) {
			$ret = array("code"=>-1,"msg"=>'',"data"=>"");
            do{ 
				$data = $_POST;
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
				break;
			}while(0);
			exit(json_encode($ret));
		}else{				
			$html = $class->get_html();			
			$this->assign('html',$html);	
			$this->display();
		}		
	}
	//商品类型编辑
	public function goods_type_edit(){		
		$this->display();
	}
	//商品类型删除
	public function goods_type_delete(){		
		$this->display();
	}
	
	//商品标签列表
	public function goods_label_list(){	
		$classname =ucfirst(strtolower('goodslabel'));
		import('Common/Vendor/Sysmodel/'.$classname);		
        $class    = new $classname();		
		$fileds =$class->getFields();	
		//查找对应的栏目id		
		$content = M($classname);
		$contentmap=array();
		$count = $content	
				->where($contentmap)					
				->count();	
		$page = new \Think\Page($count, 5);
		
		$list = $content
				->where($contentmap)
				->limit($page->firstRow.','.$page->listRows)
				->order('sort desc,id desc')
				->select();		
		$this->assign('list',$list);
		$this->assign ('page', $page->show () );
		$this->assign('fileds',$fileds);		
		$this->display();
	}
	//商品标签添加
	public function goods_label_add(){		
		//查找模型对应的表格和对应的类名
		$classname =ucfirst(strtolower('goodslabel'));
		import('Common/Vendor/Sysmodel/'.$classname);		
		$class    = new $classname();
		if ($_POST) {
			$ret = array("code"=>-1,"msg"=>'',"data"=>"");
            do{ 
				$data = $_POST;
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
				break;
			}while(0);
			exit(json_encode($ret));
		}else{				
			$html = $class->get_html();			
			$this->assign('html',$html);	
			$this->display();
		}	
	}
	//商品标签编辑
	public function goods_label_edit(){		
		//$this->display();
	}
	//商品标签删除
	public function goods_label_delete(){		
		//$this->display();
	}
	
}