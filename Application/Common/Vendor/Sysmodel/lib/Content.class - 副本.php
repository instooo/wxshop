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
						$html.="<tr><td>".$val['1']."</td><td><input type='text' class='manager-input s-input' name='".$val['0']."' id='".$val['0']."' style='width:300px' value='".$val['3']."'></td></tr>";
						break;
					case 'editor':
						$html.="<tr><td>".$val['1']."</td><td><textarea name='".$val['0']."' id='".$val['0']."' data-type='".$val[2]."'></textarea></td></tr>";
						break;
					case 'text':
						$html.="<tr><td>".$val['1']."</td><td><textarea type='text' class='manager-input s-input' name='".$val['0']."' id='".$val['0']."' style='width:400px; height:100px'></textarea></td></tr>";
						break;
					case 'file':
						$htmla="<input type='hidden' class='manager-input s-input' name='".$val['0']."'  style='width:300px' value=''>";						
						$html.='<tr><td>'.$htmla.$val['1'].'</td><td><div class="upload-category clearfix">						
						<a href="javascript:void(0);" id="'.$val['0'].'" class="category-y fl">上传预览图</a>
						<span class="category-notice fl" data-tag="preview">支持jpg、png格式，RGB模式，单张（宽、高大于1200px）</span>
						<span class="notice fl" data-name="preview">
						  <i></i>
						  预览图上传错误
						</span>
						<div class="wait-upload"></div>
					  </div></td></tr>';
					   $script = "<script type='text/javascript'>
								upload('#".$val['0']."','".$val['0']."');
								</script>";
						$html.=$script;
						break;
					case 'mu_file':
						$htmlb="<input type='hidden' class='manager-input s-input' name='".$val['0']."'  style='width:300px' value=''>";						
						$html.='<tr><td>'.$htmlb.$val['1'].'</td><td><div class="upload-category clearfix">						
						<a href="javascript:void(0);" id="'.$val['0'].'" class="category-y fl">上传预览图</a>
						<span class="category-notice fl" data-tag="preview">支持jpg、png格式，RGB模式，单张（宽、高大于1200px）</span>
						<span class="notice fl" data-name="preview">
						  <i></i>
						  预览图上传错误
						</span>
						<div class="wait-upload"></div>
					  </div></td></tr>';
					   $script = "<script type='text/javascript'>
								upload_many('#".$val['0']."','".$val['0']."','".$val['3']."');
								</script>";
						$html.=$script;
						break;					
					case 'date':
						$html.="<tr><td>".$val['1']."</td><td><input type='text' class='manager-input s-input' name='".$val['0']."' id='".$val['0']."' value='".date('Y-m-d H:i:s',time())."' style='width:300px' readonly/></td></tr>";						
						$script = '<script type="text/javascript">
								laydate.render({
								  elem: '.$val["0"].',
								  type: "datetime"	
								});
									</script>';
						$html.=$script;
						break;
					case 'radio':
						$html.="<tr><td>".$val['1']."</td><td>";
						foreach($fields['val'][$val['0']] as $k=>$v){
							$tmpdata = explode(":",$v);
							$html.='<input name='.$val["1"].' value="'.$tmpdata[1].'" type="radio" '.$tmpdata[2].'>'.$tmpdata["0"];		
						}						
						$html.="</td></tr>";
						break;
				}	
			}
			
		}
		return $html;
	}
	
	//检测上传的数据
	public function checkData($data,$_validate,$common_fields){	
		$ret = array('code'=>-1,'msg'=>'','data'=>'');
		do{
			if($_validate){
				$_validate = array_merge($this->_validate_mr,$_validate);
			}else{
				$_validate = $this->_validate;
			}	
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
					$ret = array('code'=>1,'msg'=>'验证通过','data'=>'');					
					break;		
				}		
			}else{
				$ret = array('code'=>1,'msg'=>'验证通过','data'=>'');
				break;
			}
		}while(0);	
		return $ret;
	}
	
	//处理数据
	public function filter($data,$fields){
		return $data;
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
	
}