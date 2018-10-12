<?php
/**
 * Created by van23qf.
 * User: van23qf
 * Date: 2018/7/31
 * Time: 10:08
 */

namespace Api\Api\Common;


use Api\Api\Base;

class Game extends Base {

    /**
     * 根据标签获取游戏列表
	 *
     * @return array
     */
    public function getListBytagid() {
        $tagids = $this->request['tags'];
        $tagidarr = explode(',',trim($tagids,','));
		$tagidar[]=-1;//加一个数值，防止报错
		$map['tag_short_des']=array('in',$tagids);
		$list = array();
		foreach($tagidarr as $val){
			$map['tag_short_des']=$val;
			$gameids = M('game_tag')->where($map)->select(); 
			$gameids = array_column($gameids,'game_id');
			$gameids[]=-1;
			$where['id'] = array('in',$gameids);
			$list[$val] = M('game')->where($where)->limit(30)->select();			
		}
		return array('code'=>1,'msg'=>'success','data'=>$list);		
    }

    /**
     * 根据标签获取游戏列表
     *
     * @return array
     */
    public function getListBytag() {
        $tagids = $this->request['tags'];
        $page = $this->request['page'];
        $start = $page*30;
        $limit_str = $start.",30";
        $map['tag_short_des']=$tagids;
        $gameids = M('game_tag')->where($map)->select();
        $gameids = array_column($gameids,'game_id');
        $gameids[]=-1;
        $where['id'] = array('in',$gameids);
        $list= M('game')->where($where)->limit($limit_str)->order('sort desc')->select();
        return array('code'=>1,'msg'=>'success','data'=>$list);
    }

}