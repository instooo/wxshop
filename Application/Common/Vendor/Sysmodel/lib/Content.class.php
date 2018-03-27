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
						$tpl =$this->get_input();
						$html.=sprintf($tpl,$val['1'],$val['0'],$val['3']);						
						break;
					case 'editor':
						$tpla =$this->get_editor();
						$html=sprintf($tpla,$val['1'],$val['0'],$val['0'],$val['2']);	
						print_r($html);die;					
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
		print_r($html);die;
		return $html;
	}
	//输入框
	private function get_input(){
		$str = '
		<div class="layui-form-item">
		<label class="layui-form-label textright" style="width:70px;"><font color="red">*</font>%s</label>
		<div class="layui-input-block" style="margin-left:100px;">
			<input type="text" name="%s" lay-verify="required" lay-verType="tips" placeholder="请%s" autocomplete="off" class="layui-input">
		</div>
		</div>';
		return $str;
	}
	//文本编辑框
	private function get_editor(){
		$str='<div class="layui-form-item">
		<label class="layui-form-label textright" style="width:70px;"><font color="red">*</font>%s</label>
			<div class="layui-input-block" style="margin-left:100px;">
				<textarea name="%s" id="%s" data-type="%s" style="width:100%;height:300px;"></textarea>	
			</div>
		</div>';
	 return $str;
	}
}