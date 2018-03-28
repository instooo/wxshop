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
						$html.=sprintf($tpl,$val['1'],$val['0'],$val['1']);						
						break;
					case 'editor':						
						$tpl =$this->get_editor();	
						$html.=sprintf($tpl,$val['1'],$val['0'],$val['0'],$val['2']);						
						break;
					case 'text':
						$tpl =$this->get_text();
						$html.=sprintf($tpl,$val['1'],$val['1'],$val['0']);						
						break;	
					case 'date':
						$tpl =$this->get_date_input();
						$html.=sprintf($tpl,$val['1'],$val['1'],$val['0'],$val['0'],$val['0']);						
						break;		
					case 'select':
						$html.=$this->get_select($val);
						break;
					case 'radio':
						$html.=$this->get_radio($val);
						break;			
					case 'one_file':
						$tpl =$this->get_one_file();
						$html.=sprintf($tpl,$val['0'],$val['1'],$val['0'],$val['0'],$val['0']);						
						break;					
					case 'duo_file':
						$tpl =$this->get_duo_file();
						$html.=sprintf($tpl,$val['0'],$val['1'],$val['0'],$val['0'],$val['0']);						
						break;
				}	
			}
			
		}		
		return $html;
	}
	//输入框
	private function get_input(){
		$str = '
		<div class="layui-form-item">
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>%s</label>
		<div class="layui-input-block" style="margin-left:100px;">
			<input type="text" name="%s" lay-verify="required" lay-verType="tips" placeholder="请%s" autocomplete="off" class="layui-input">
		</div>
		</div>';
		return $str;
	}
	//文本编辑框
	private function get_editor(){
		$str='
		<div class="layui-form-item">
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>%s</label>
		<div class="layui-input-block" style="margin-left:100px;">
		<textarea name="%s" id="%s" data_type="%s"></textarea>			
		</div>
		</div>';
		return $str;		
	}
	//text
	private function get_text(){
		$str='
		<div class="layui-form-item">
			<label class="layui-form-label textright" style="width:100px;">
			<font color="red">*</font>%s</label>
			<div class="layui-input-block" style="margin-left:100px;">
			 <textarea placeholder="请输入%s" name="%s" class="ant-col-md-24 ant-input" style="height:100px;"></textarea>		
			</div>
			</div>';		
		return $str;
	}
	//date
	private function get_date_input(){
		$str = '
		<div class="layui-form-item">
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>%s</label>
		<div class="layui-input-block" style="margin-left:100px;">
			<input type="text" name="%s" lay-verify="required" lay-verType="tips" placeholder="请%s" autocomplete="off" class="layui-input" id="%s">
		</div>
		</div>';
		$str.='<script type="text/javascript">
			layui.use("laydate", function(){
			  var laydate = layui.laydate;  
			  //执行一个laydate实例
			  laydate.render({
				elem: "#%s" 
			  });
			});
			</script>';		
		return $str;
	}
	//radio
	private function get_radio($val){
		$str = '
		<div class="layui-form-item">
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>'.$val[1].'</label>
		<div style="margin-left:100px;">
		<div class="ant-radio-group" id="public" style="line-height:28px;vertical-align: middle;">';
		foreach($val[3]['many_data'] as $k=>$v){
			$str.=$v[1].'<input type="radio" name="'.$val[0].'" value="'.$v[0].'">';	
		}
		$str.='</div></div>
		</div>';
		return $str;
	}
	//select
	private function get_select($val){
		$str = '
		<div class="layui-form-item">
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>'.$val[1].'</label>
		<div class="layui-input-block" style="margin-left:100px;">
		<select name="order_status" lay-verify="required">';
		$str.='<option value="'.$val[3]['default'][0].'">'.$val[3]['default'][1].'</option>';
		foreach($val[3]['many_data'] as $k=>$v){
			$str.='<option value='.$v[0].'>'.$v[1].'</option>';	
		}
		$str.='</select></div></div>';
		return $str;
	}
	//单文件
	private function get_one_file(){
		$str = '
		<div class="layui-form-item">	
		<input type="hidden" name="%s" value="">	
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>%s</label>
		<div class="layui-input-block" style="margin-left:100px;">
			<div class="upload-category clearfix">						
			<a href="javascript:void(0);" id="%s" class="category-y fl">上传预览图</a>
			<span class="category-notice fl" data-tag="preview">支持jpg、png格式,RGB模式,单张</span>
			<span class="notice fl" data-name="preview">
			  <i></i>
			  预览图上传错误
			</span>
			<div class="wait-upload"></div>
		  </div>	
		</div>
		
		</div>';
		
		$str.= "<script type='text/javascript'>
				upload('#%s','%s');
		</script>";
		return $str;
	}
	//单文件
	private function get_duo_file(){
		$str = '
		<div class="layui-form-item">	
		<input type="hidden" name="%s" value="">	
		<label class="layui-form-label textright" style="width:100px;">
		<font color="red">*</font>%s</label>
		<div class="layui-input-block" style="margin-left:100px;">
			<div class="upload-category clearfix">						
			<a href="javascript:void(0);" id="%s" class="category-y fl">上传预览图</a>
			<span class="category-notice fl" data-tag="preview">支持jpg、png格式,RGB模式,单张</span>
			<span class="notice fl" data-name="preview">
			  <i></i>
			  预览图上传错误
			</span>
			<div class="wait-upload"></div>
		  </div>	
		</div>		
		</div>';		
		$str.= "<script type='text/javascript'>				
				upload_many('#%s','%s',10);
		</script>";
		return $str;
	}
}