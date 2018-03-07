<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class UserViewModel extends ViewModel {
	
	public $viewFields = array(
			'User'=>array('id','username','nickname','realname','password','bind_account','last_login_time','last_login_ip','login_count','verify','email','remark','create_time','update_time','status','info'),
			'Role_user'=>array('_on'=>'User.id=Role_user.user_id','_type'=>'LEFT'),
			'Role'=>array('id'=>'roleid','name'=>'rolename','_on'=>'Role_user.role_id=Role.id','_type'=>'LEFT'),		
	);
	
	
	
}