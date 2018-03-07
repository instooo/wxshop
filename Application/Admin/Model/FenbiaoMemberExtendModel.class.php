<?php
/** 
* 高级模型--测试分表用  
* @author         Ldan<youxi_Ldan@163.com> 
* @since          1.0
* @Date           2015-07-13 
*/
namespace Admin\Model;
use Think\Model\AdvModel; 
class FenbiaoMemberExtendModel extends AdvModel{
	protected $tableName = 'member_extend_info'; 
	protected $partition = array(
		'field' => 'uid',// 要分表的字段 通常数据会根据某个字段的值按照规则进行分表
		'type' => 'mod',// 分表的规则 包括id year mod md5 函数 和首字母		
		'num' => '20',// 分表的数目 可选 实际分表的数量
	);
    protected $connection = 'DB_CONFIG_CHONG';
    protected $tablePrefix = 'mygame_';
	//获得操作表名
	public function getDao($data = array()) {		
		if(!$data['uid']){
			$table = 'mygame_'.$this->tableName;
		}else{
			$table = $this->getPartitionTableName ( $data );
		}					
		return $this->table ( $table );
	}
	//获得具体操作表名--用于测试获得表名功能，做完后需要删除
	public function getDaotest($data = array()) {		
		if(!$data['uid']){
			$table = 'mygame_'.$this->tableName;
		}else{
			$table = $this->getPartitionTableName ( $data );
		}		
		return $table;
	}
}
?>