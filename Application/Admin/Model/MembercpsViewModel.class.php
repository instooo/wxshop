<?php 
 namespace Admin\Model;
 use Think\Model\ViewModel; 
  class MembercpsViewModel extends ViewModel{ 	
  		public $viewFields  = array(
  				'member_cps'=>array('uid','is_fcm','safe_a','safe_q','id_card','username','nickname','email','avatar','level','phone','point','money','user_status','username','_type'=>'LEFT'),
  				'member_extend_info_cps'=>array('uid','grouping','realname','from_soical','total_channels','sub_channels','gid','sid','subsign','lastlogin_time','lastlogin_ip','register_time','register_ip','_on'=>'member_cps.uid=member_extend_info_cps.uid'),
  		);
        protected $connection = 'DB_CONFIG_CHONG';
        protected $tablePrefix = 'mygame_';
  }
?>