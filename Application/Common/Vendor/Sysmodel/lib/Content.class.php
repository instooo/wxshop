<?php
//定义接口
class Content{
	
	protected $_validate_mr = array(
		array('title','require','不能为空'), //默认情况下用正则进行验证
		array('weight','number','必须为整数'), // 在新增的时候验证name字段是否唯一	
	);
	
	//获取模型字段和类型
    function getFields(){
		$fields = array();
		return $fields;
	}
	//根据类型获得不同类型的前端代码
	public function get_html($fields){
		$html = "";		
		foreach($fields as $key=>$val){
			if($key!=='val'){			
				switch($val[2]){
					case 'input':
						$html.=$this->get_input($val);											
						break;
					case 'editor':						
						$html.=$this->get_editor($val);									
						break;
					case 'text':
						$html.=$this->get_text($val);										
						break;	
					case 'date':
						$html.=$this->get_date_input($val);										
						break;		
					case 'select':
						$html.=$this->get_select($val);
						break;
					case 'radio':
						$html.=$this->get_radio($val);
						break;			
					case 'one_file':
						$html.=$this->get_one_file($val);											
						break;					
					case 'duo_file':
						$html.=$this->get_duo_file($val);											
						break;
				}	
			}
			
		}		
		return $html;
	}
	//根据类型获得不同类型的前端代码
	public function edit_html($fields,$info){		
		$html = "";		
		$html .= "<input type='hidden' name='id' id='id'  value='".$info['id']."'>";	
		foreach($fields as $key=>$val){
			if($key!=='val'){			
				switch($val[2]){
					case 'input':
						$html.=$this->get_input($val,$info);											
						break;
					case 'editor':						
						$html.=$this->get_editor($val,$info);									
						break;
					case 'text':
						$html.=$this->get_text($val,$info);										
						break;	
					case 'date':
						$html.=$this->get_date_input($val,$info);										
						break;		
					case 'select':
						$html.=$this->get_select($val,$info);
						break;
					case 'radio':
						$html.=$this->get_radio($val,$info);
						break;			
					case 'one_file':
						$html.=$this->get_one_file($val,$info);											
						break;					
					case 'duo_file':
						$html.=$this->get_duo_file($val,$info);											
						break;
				}	
			}
			
		}		
		return $html;
	}
	
	//处理数据
	public function filter($data,$fields){
		return $data;
	}
	
	
	//检测输入数据
	public function checkData($data,$_validate,$common_fields){		
		$ret = array('code'=>-1,'msg'=>'','data'=>'');
		do{			
			if($_validate){	
				foreach($common_fields as $key=>$val){
					$common_fieldsnew[$val[0]]=$val;
				}
				foreach($_validate as $key=>$val){					
					$check = $this->regex($data[$val[0]],$val[1]);					
					if(!$check){		
						$ret['code'] = -2;
						$ret['msg'] =$common_fieldsnew[$val[0]][1].$val[2];
						break;
					}
				}				
				if($ret['code']!=-2){					
					$ret = array('code'=>1,'msg'=>'success','data'=>'');					
					break;		
				}					
			}else{
				$ret = array('code'=>1,'msg'=>'success','data'=>'');
				break;
			}
		}while(0);			
		return $ret;
	}
	
	
	/**
     * 使用正则验证数据
     * @access public
     * @param string $value  要验证的数据
     * @param string $rule 验证规则
     * @return boolean
     */
    public function regex($value,$rule) {
        $validate = array(
            'require'   =>  '/\S+/',
            'email'     =>  '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
            'url'       =>  '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(:\d+)?(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
            'currency'  =>  '/^\d+(\.\d+)?$/',
            'number'    =>  '/^\d+$/',
            'zip'       =>  '/^\d{6}$/',
            'integer'   =>  '/^[-\+]?\d+$/',
            'double'    =>  '/^[-\+]?\d+(\.\d+)?$/',
            'english'   =>  '/^[A-Za-z]+$/',
        );
        // 检查是否有内置的正则表达式
        if(isset($validate[strtolower($rule)]))
            $rule       =   $validate[strtolower($rule)];
        return preg_match($rule,$value)===1;
    }
	
	//输入框$html.=sprintf($tpl,,$val['0'],$val['1']);	
	private function get_input($val,$info){
		$str = '
		<div class="layui-form-item">
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>'.$val['1'].'</label>
		<div class="layui-input-block" style="margin-left:100px;">
			<input type="text" name="'.$val['0'].'" lay-verify="required" lay-verType="tips" placeholder="请'.$val['1'].'" autocomplete="off" class="layui-input" value="'.$info[$val[0]].'">
		</div>
		</div>';
		return $str;
	}
	//文本编辑框$html.=sprintf($tpl,$val['1'],$val['0'],$val['0'],$val['2']);				
	private function get_editor($val,$info){
		$str='
		<div class="layui-form-item">
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>'.$val['1'].'</label>
		<div class="layui-input-block" style="margin-left:100px;">
		<textarea name="'.$val['0'].'" id="'.$val['0'].'" data_type="'.$val['2'].'">'.$info[$val[0]].'</textarea>			
		</div>
		</div>';
		return $str;		
	}
	//text$html.=sprintf($tpl,$val['1'],$val['1'],$val['0']);		
	private function get_text($val,$info){
		$str='
		<div class="layui-form-item">
			<label class="layui-form-label textright" style="width:100px;">
			<font color="red">*</font>'.$val['0'].'</label>
			<div class="layui-input-block" style="margin-left:100px;">
			 <textarea placeholder="请输入'.$val['1'].'" name="'.$val['0'].'" class="ant-col-md-24 ant-input" style="height:100px;">'.$info[$val[0]].'</textarea>		
			</div>
			</div>';		
		return $str;
	}
	//date
	private function get_date_input($val,$info){
		$str = '
		<div class="layui-form-item">
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>'.$val['1'].'</label>
		<div class="layui-input-block" style="margin-left:100px;">
			<input type="text" name="'.$val['0'].'" lay-verify="required" lay-verType="tips" placeholder="请'.$val['1'].'" autocomplete="off" class="layui-input" id="'.$val['0'].'" value="'.date('Y-m-d H:i:s',$info[$val[0]]).'" readonly>
		</div>
		</div>';
		$str.='<script type="text/javascript">
			layui.use("laydate", function(){
			  var laydate = layui.laydate;  
			  //执行一个laydate实例
			  laydate.render({
				elem: "#'.$val['0'].'" 
			  });
			});
			</script>';		
		return $str;
	}
	//radio
	private function get_radio($val,$info){
		$str = '
		<div class="layui-form-item">
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>'.$val[1].'</label>
		<div style="margin-left:100px;">
		<div class="ant-radio-group" id="public" style="line-height:28px;vertical-align: middle;">';
		foreach($val[3]['many_data'] as $k=>$v){
			if($v[0]==$info[$val[0]]){
				$str.=$v[1].'<input type="radio" name="'.$v[0].'" value="'.$v[0].'" checked>';	
			}else{
				$str.=$v[1].'<input type="radio" name="'.$v[0].'" value="'.$v[0].'">';	
			}
			
		}
		$str.='</div></div>
		</div>';
		return $str;
	}
	//select
	private function get_select($val,$info){
		$str = '
		<div class="layui-form-item">
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>'.$val[1].'</label>
		<div class="layui-input-block" style="margin-left:100px;">
		<select name="'.$val[0].'">';
		$str.='<option value="'.$val[3]['default'][0].'">'.$val[3]['default'][1].'</option>';
		foreach($val[3]['many_data'] as $k=>$v){
			foreach($v AS $i=>$j){
			  $arr[] = $j;
			}			
			if($arr[0]==$info[$val[0]]){
				$str.='<option value='.$arr[0].' selected>'.$arr[1].'</option>';	
			}else{
				$str.='<option value='.$arr[0].'>'.$arr[1].'</option>';	
			}
			unset($arr);
		}
		$str.='</select></div></div>';
		return $str;
	}
	//单文件
	private function get_one_file($val,$info){
		//对图片进行处理	
		if(trim($info[$val['0']],'|')){						
			$picarr = explode("|",trim($info[$val['0']],'|'));
			$pic_html="";						
			foreach($picarr as $k=>$v){
				$pic_html.='<div class="preview-small imgWrap fl" data-src="'.$v.'" style="background: url('.$v.') center center no-repeat;"><i class="position-ab havepic"></i></div>';
			}	
		}
		$str = '
		<div class="layui-form-item">	
		<input type="hidden" name="'.$val[0].'" value="'.$info[$val['0']].'">	
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>'.$val[1].'</label>
		<div class="layui-input-block" style="margin-left:100px;">
			<div class="upload-category clearfix">						
			<a href="javascript:void(0);" id="'.$val[0].'" class="category-y fl">上传预览图</a>
			<span class="category-notice fl" data-tag="preview">支持jpg、png格式,RGB模式,单张</span>
			<span class="notice fl" data-name="preview">
			  <i></i>
			  预览图上传错误
			</span>
			<div class="wait-upload">'.$pic_html.'</div>
		  </div>	
		</div>
		
		</div>';
		
		$str.= "<script type='text/javascript'>
				upload('#".$val[0]."','".$val[0]."');
		</script>";
		return $str;
	}
	//单文件
	private function get_duo_file($val,$info){
		//对图片进行处理	
		if(trim($info[$val['0']],'|')){						
			$picarr = explode("|",trim($info[$val['0']],'|'));
			$pic_html="";						
			foreach($picarr as $k=>$v){
				$pic_html.='<div class="preview-small imgWrap fl" data-src="'.$v.'" style="background: url('.$v.') center center no-repeat;"><i class="position-ab havepic"></i></div>';
			}	
		}
		$str = '
		<div class="layui-form-item">	
		<input type="hidden" name="'.$val[0].'" value="">	
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>'.$val[1].'</label>
		<div class="layui-input-block" style="margin-left:100px;">
			<div class="upload-category clearfix">						
			<a href="javascript:void(0);" id="'.$val[0].'" class="category-y fl">上传预览图</a>
			<span class="category-notice fl" data-tag="preview">支持jpg、png格式,RGB模式,单张</span>
			<span class="notice fl" data-name="preview">
			  <i></i>
			  预览图上传错误
			</span>
			<div class="wait-upload">'.$pic_html.'</div>
		  </div>	
		</div>		
		</div>';		
		$str.= "<script type='text/javascript'>				
				upload_many('#".$val[0]."','".$val[0]."',10);
		</script>";
		return $str;
	}
}