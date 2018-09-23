<?php
/**
 * Created by van23qf.
 * User: van23qf
 * Date: 2018/7/31
 * Time: 10:08
 */

namespace Api\Api\Common;


use Api\Api\Base;

class Banner extends Base {

    /**
     * 分类列表
     * @return array
     */
    public function getList() {
        $typeid = $this->request['typeid'];
        $typeid = $typeid?$typeid:1;		
		$limit = $this->request['limit']?$this->request['limit']:'0,3';	
		$map['a.type'] = $typeid;
        $list = M('operation_ad a')
            ->field('a.*,b.appid,b.path')
            ->join('wxgame_game b on a.gid=b.id')
            ->where($map)
            ->limit($limit)
            ->select();
        return array('code'=>1,'msg'=>'success','data'=>$list);
    }   

}